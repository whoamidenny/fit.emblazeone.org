<?php
namespace backend\modules\clients\models;

use backend\modules\telegram\models\TelegramBot;
use common\models\WbpActiveRecord;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
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
 * @property integer $status
 * @property string $password write-only password
 */
class Client extends WbpActiveRecord implements IdentityInterface
{
    public static $imageTypes=['profile'];

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    public $newPassword;
    public $passwordConfirmation;

    public static function tableName()
    {
        return '{{%clients}}';
    }

    public function getName(){
        $name = trim($this->first_name.' '.$this->last_name);
        if(!$name) $name=$this->username;
        if(!$name) $name="UserId: ".$this->id;
        return $name;

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
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DISABLED]],

            [['passwordConfirmation','newPassword','username','status','email','goal_id','address', 'country','first_name','last_name'],'safe','on'=>['edit','add']],
            [['email'],'uniqueFunc','on'=>['edit']],
            ['passwordConfirmation', 'compare' ,'compareAttribute'=>'newPassword'],
            ['newPassword', 'string', 'length' => [8, 32]],
            ['email', 'email']
        ];
    }

    public function uniqueFunc($attribute){
        $query=self::find()->where(['email'=>$this->{$attribute}]);
        if($this->id) $query=$query->andWhere('id!='.$this->id);
        if($query->all())
            $this->addError($attribute, 'Email already exists');
    }

    public function beforeSave($insert)
    {
        $this->username=trim($this->username);
        if($this->scenario=='edit' || $this->scenario=='add'){
            if($this->newPassword){
                $this->setPassword($this->newPassword);
            }elseif($insert){
                $generatedPassword=$this->generatePassword();
                $this->setPassword($generatedPassword);
                $this->generated_password=$generatedPassword;
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

    public function getWeightTrackers(){
        return $this->hasMany(ClientWeightTracker::className(),['client_id'=>'id']);
    }

    public function getWeightTrackersLabels(){
        $trackers=$this->weightTrackers;
        $result=[];
        foreach ($trackers as $track){
            $result[]=sprintf("%02d",$track->week);
        }
        if(!$result) return '';
        return "'".implode("','", $result)."'";
    }

    public function getWeightTrackersValues(){
        $trackers=$this->weightTrackers;
        $result=[];
        foreach ($trackers as $track){
            $result[]=(int)$track->weight;
        }
        if(!$result) return 0;
        return implode(",", $result);
    }

    public function getPhotoTrackers(){
        return $this->hasMany(ClientPhotoTracker::className(),['client_id'=>'id']);
    }

    public function getWeightTrack(){
        $param=$this->getWeightTrackers()->where(['>','weight',0])->orderBy('week desc')->one();
        if($param) return $param;
    }

    public function getWeight(){
        if($this->weightTrack) return $this->weightTrack->weight;
        else return 0;
    }

    public static function getGoals(){
        return [
          1=>'WEIGHT (FAT) LOSS',
          2=>'WEIGHT (FAT) INCREASE',
          3=>'WEIGHT (FAT) RETENTION',
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'#',
            'newPassword'=>'New Password',
            'passwordConfirmation'=>'Confirm Password',
            'generated_password'=>'Generated password',
            'created_at'=>'Added date',
            'goal_id'=>'Your goal'
        ];
    }

    public function generatePassword(){
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!!!!!____####%%%%^^^^&&&&";
        $pass=[];
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, strlen($alphabet)-1);
            $pass[] = $alphabet[$n];
        }
        $password=implode('',$pass);
        $this->setPassword($password);
        return $password;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmailWithoutStatus($email)
    {
        return static::findOne(['email' => $email]);
    }


    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
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
        return $validate;
    }

    public function getGoal(){
        if(!$this->goal_id) return 'PLESE SELECT';
        return self::getGoals()[$this->goal_id];
    }

    public function getGender(){
        return '-';
    }

    public function getLocation(){
        return 'USA';
    }




}
