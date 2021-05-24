<?php

namespace backend\models;

use common\models\Identity;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
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
            [['username', 'password'], 'required'],
            [['agree'], 'required', 'requiredValue' => 1, 'message' => Yii::t('login','Unfortunately, without consent to the processing of personal data, we cannot collect and process information.')],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['agree', 'boolean'],
            // password is validated by validatePassword()
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

            $usrWithoutStatus=Identity::findByUsernameWithoutStatus($this->username);

            if(!$usrWithoutStatus){
                $this->addError($attribute, Yii::t('login','Invalid username or password'));
            } elseif (!$user && $usrWithoutStatus && $usrWithoutStatus->status==0) {
                $this->addError($attribute, Yii::t('login','The account is blocked, please contact the administrator'));
            }elseif ($user && !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('login','Invalid password. Left').' '.(Identity::DEACTIVATE_WRONG_PASS-$usrWithoutStatus->wrong_pass_entered-1).' '.Yii::t('login','login attempts'));
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
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Identity::findByUsername($this->username);
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
