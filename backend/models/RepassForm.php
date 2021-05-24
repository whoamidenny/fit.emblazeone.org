<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class RepassForm extends Model
{
    public $username;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
        ];
    }



    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function repass()
    {
        if ($this->validate()) {
            $user=Identity::findByUsernameWithoutStatus($this->username);
            if(!$user) $user=Identity::findByEmail($this->username);

            if($user && $user->email){

                $generateKey=md5('akkf^839'.time());

                $user->password_reset_token=$generateKey;
                $user->save();

                Yii::$app->mailer->compose()
                    ->setFrom(['noreply@osmd.kh.ua'=>Config::getParameter('title',false)])
                    ->setTo($user->email)
                    ->setSubject('Восстановление пароля')
                    ->setHtmlBody('
                           <h1>Восстановление пароля</h1>
                           <p>Данный email отправлен вам потому что вы пытаетесь восстановить доступ в личный кабинет ОСМД Молодежный Городок</p>
                           <p>Для того чтоб сбросить старый пароль перейдите по <a href="'.Url::to(['auth/repass','key'=>$generateKey],true).'">ссылке</a></p>
                    ')
                    ->send();
                    Yii::$app->session->setFlash('success','Проверьте свой почтовый ящик, мы отправили вам инструкции по восстановлению пароля.');
                    return true;
            }elseif($user){
                Yii::$app->session->setFlash('error','В вашем аккаунте не указан email, обратитесь к администрации для восстановления пароля.');
                return false;
            }else{
                Yii::$app->session->setFlash('error','Данный аккаунт не найден в системе.');
                return false;
            }

//            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    public static function resetPassword($key){
        $user=Identity::findOne(['password_reset_token'=>$key]);
        if($user){
            $password=$user->generatePassword();
            $user->save();

            Yii::$app->mailer->compose()
                ->setFrom(['noreply@osmd.kh.ua'=>Config::getParameter('title',false)])
                ->setTo($user->email)
                ->setSubject('Восстановление пароля')
                ->setHtmlBody('
                           <h1>Восстановление пароля</h1>
                           <p>Данный email отправлен вам потому что вы пытаетесь восстановить доступ в личный кабинет ОСМД Молодежный Городок</p>
                           <p>Ваш логин: '.$user->username.'</p>
                           <p>Ваш новый пароль: '.$password.'</p>
                           <p>Не забудьте после входа в личный кабинет сменить пароль на более безопасный.</p>
                    ')
                ->send();
            Yii::$app->session->setFlash('success','Проверьте свой почтовый ящик, мы отправили вам новый пароль.');
        }else{
            Yii::$app->session->setFlash('error','Данный ключ не найден в системе');
        }
    }


    public function attributeLabels()
    {
        return [
            'username'=>'Email или Имя пользователя',
        ];
    }
}
