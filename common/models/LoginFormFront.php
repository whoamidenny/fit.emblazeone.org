<?php
namespace common\models;

/**
 * Login form front
 */
class LoginFormFront extends LoginForm
{
	public $email;

	private $_user;

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			// username and password are both required
			[['email', 'password'], 'required'],
			[['email'], 'email'],
			// rememberMe must be a boolean value
			['rememberMe', 'boolean'],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
		];
	}

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
