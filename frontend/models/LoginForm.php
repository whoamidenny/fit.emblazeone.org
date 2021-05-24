<?php

namespace frontend\models;

use backend\modules\clients\models\Client;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property Client|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;
    public $agree = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            ['email','email'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            $usrWithoutStatus=Client::findByEmailWithoutStatus($this->email);

            if(!$usrWithoutStatus){
                $this->addError($attribute, Yii::t('login','Invalid email or password'));
            }elseif (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('login','Invalid username or password'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 31536000);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return Client|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Client::findByEmail($this->email);
        }

        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'username'=>Yii::t('login', 'Username'),
            'password'=>Yii::t('login', 'Password'),
            'rememberMe'=>Yii::t('login', 'Remember me')
        ];
    }
}
