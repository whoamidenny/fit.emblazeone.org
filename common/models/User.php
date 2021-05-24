<?php
namespace common\models;

class User extends \yii\web\User
{
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if($permissionName=='*') return true;
        if(!$this->identity) return false;
        if($permissionName==$this->identity->role) return true;
        return parent::can($permissionName, $params, $allowCaching);
    }
}
