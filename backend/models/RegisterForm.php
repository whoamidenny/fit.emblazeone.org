<?php

namespace backend\models;

use common\models\Identity;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RegisterForm extends Model
{
    public $username;
    public $password;
//    public $password;
    public $password_confirmation;
    public $email;
    public $first_name;
    public $last_name;
    public $middle_name;
    public $rooms;
    public $phone;
    public $agree = true;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'username','first_name','last_name','phone'], 'string', 'max' => 255],
            [['agree'], 'required', 'requiredValue' => 1, 'message' => 'Без согласия на обработку персональных данных, к сожалению, мы не можем собирать и обрабатывать информацию.'],
            ['email', 'validateUniqueEmail'],
            ['username', 'validateUniqueUsername'],
            [['phone'], 'udokmeci\yii2PhoneValidator\PhoneValidator','country'=>'UA','format'=>true],
            [['email', 'username'], 'filter', 'filter' => 'trim'],
            [['email'], 'email'],
            [['phone','username','rooms'], 'required'],

            [['username'], 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => Yii::t('login','{attribute} can contain only letters, numbers, and "_"')],


            ['password', 'string', 'length' => [8, 32]],
//            [['password'], 'filter', 'filter' => 'trim'],
            [['password','password_confirmation'], 'required'],
            [['password_confirmation'], 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('login','Passwords do not match')],


            [['first_name','last_name','middle_name','phone'],'safe']
        ];
    }

    public function validateUniqueEmail()
    {
        if(Identity::findByEmailWithoutStatus($this->email)) {
            $this->addError("email", Yii::t('login',"This email already registered"));
        }
    }

    public function validateUniqueUsername()
    {
        if(Identity::findByUsernameWithoutStatus($this->username)){
            $this->addError("username", Yii::t('login',"SORRY, THIS USERNAME IS TAKEN"));
        }
    }


    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function register()
    {
        if ($this->validate()) {
            $user= new Identity();
            $user->scenario='edit';
            $user->load($this->getAttributes(),'');

            $user->username=$this->username;
            $user->newPassword = $this->password;
            $user->email=$this->email;
            $user->status=Identity::STATUS_NEW;

            $user->save();

            foreach ($this->rooms as $room_id){
                $link=new UserRooms();
                $link->room_id=$room_id;
                $link->user_id=$user->id;
                $link->save();
            }


            return $user;
        } else {
            return false;
        }
        return true;
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('login','Username'),
            'password_new' => Yii::t('login','Password'),
            'password_confirmation' => Yii::t('login','Password Confirm'),
            'first_name' => Yii::t('login','First Name'),
            'last_name' => Yii::t('login','Last Name'),
            'middle_name' => Yii::t('login','Middle Name'),
            'phone' => Yii::t('login','Phone'),
            'agree'     => Yii::t('login','Agreement'),
            'rooms' => Yii::t('login','Your Rooms'),
        ];
    }

}
