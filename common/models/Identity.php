<?php
namespace common\models;

use backend\modules\telegram\models\TelegramBot;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property string $password write-only password
 */
class Identity extends WbpActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = -1;
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;
    const STATUS_NEW = 2;

    const ROLE_USER = 10;
    const ROLE_ADMIN = 11;
    const ROLE_GOD = 12;

    const DEACTIVATE_WRONG_PASS=10;

    public static $statuses=[
        0=>'Disabled',
        1=>'Active',
    ];

    public static $imageTypes=['User','Documents'];

    public $prevStatus;

    public $newPassword;
    public $passwordConfirmation;

    public $room_ids_array=[];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function getName(){
        $name = trim($this->first_name.' '.$this->last_name);
        if(!$name) $name=$this->username;
        if(!$name) $name=$this->email;
        if(!$name) $name="UserId: ".$this->id;
        return $name;

    }

    public function generateLoginToken(){
        $this->login_token=md5(time().rand(1,100).$this->id).'_'.time();
        $this->save();
    }

    public function generateSmsPass(){
        $this->sms_pass=$this->generatePass();
        $this->save();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours=parent::behaviors();

        return ArrayHelper::merge($behaviours,[
            TimestampBehavior::className(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_DISABLED, self::STATUS_NEW]],

            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN, self::ROLE_GOD]],

            [['passwordConfirmation','newPassword','username','email','phone','status','wrong_pass_entered','role','first_name','last_name','middle_name','room_ids_array'],'safe','on'=>['edit','add']],
            [['passwordConfirmation','newPassword','username','email','phone','first_name','last_name','middle_name'],'safe','on'=>'editProfile'],
            [['username','email','phone'],'required','on'=>['editProfile']],
            [['username'],'uniqueFunc','on'=>['edit','editProfile']],
            ['passwordConfirmation', 'compare' ,'compareAttribute'=>'newPassword'],
            ['newPassword', 'string', 'length' => [8, 32]],
            ['email','email','on'=>['edit','editProfile']],

        ];
    }

    public function uniqueFunc($attribute){
        $query=self::find()->where(['username'=>$this->{$attribute}]);
        if($this->id) $query=$query->andWhere('id!='.$this->id);
        if($query->all())
            $this->addError($attribute, 'Username already exists');
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByLoginToken($token)
    {
        return static::findOne(['login_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByUsernameWithoutStatus($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmailWithoutStatus($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    public function getLoginToken(){
        $this->generateLoginToken();
        return $this->login_token;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $validate=Yii::$app->security->validatePassword($password, $this->password_hash);

        if(!$validate) {
            $this->wrong_pass_entered++;

            if($this->wrong_pass_entered==self::DEACTIVATE_WRONG_PASS){
                $this->status=0;
            }
        }else{
            $this->wrong_pass_entered=0;
        }
            $this->save();

        return $validate;
    }

    public function beforeSave($insert)
    {
        $this->username=trim($this->username);
        if($this->scenario=='edit' || $this->scenario=='add'){
            $this->role=Identity::ROLE_ADMIN;

            if($this->newPassword){
                $this->setPassword($this->newPassword);
            }
        }elseif($this->scenario=='editProfile'){
            if($this->newPassword){
                $this->setPassword($this->newPassword);
            }
        }
        return parent::beforeSave($insert);
    }


    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function attributeLabels()
    {
        return [
            'id'=>'#',
            'newPassword'=>'New Password',
            'passwordConfirmation'=>'Confirm Password',
            'generated_password'=>'Generated password',
            'created_at'=>'Added date'
        ];
    }

}
