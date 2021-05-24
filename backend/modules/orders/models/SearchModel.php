<?php

namespace backend\modules\orders\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class SearchModel extends Model
{
    public $from,
        $to,
        $phone,
        $email,
        $name,
        $per_page=20,
        $order;

    public static $pageSizeList=[
        '10'=>'10',
        '20'=>'20',
        '50'=>'50',
        '100'=>'100',
        '-1'=>'все'
    ];

    public function rules()
    {
        return [
            [['order', 'phone','email', 'name', 'from', 'to', 'search','per_page'], 'safe']
        ];
    }

    public function search($modelName, $params)
    {
        $query = $modelName::find();

        $query->orderBy('id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'id'
        ]);

//        $query = $this->getOrder($query);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if($this->phone){
            $this->phone=str_replace(['-','(',')',' '],'', $this->phone);
            $len=strlen($this->phone);
            $phone="";
            for ($i=1;$i<=$len;$i++){
                if($i==3) $phone='-'.$phone;
                if($i==5) $phone='-'.$phone;
                if($i==8) $phone=') '.$phone;
                if($i==11) $phone=' ('.$phone;
                $phone=substr($this->phone,-1*$i, 1).$phone;
            }
            $this->phone=$phone;
            $query=$query->andWhere(['like','phone',$this->phone]);
        }
        if($this->name) $query=$query->andWhere(['like','name',$this->name]);
        if($this->email) $query=$query->andWhere(['like','email',$this->email]);
        if($this->from) $query=$query->andWhere(['>=','created_at',$this->from]);
        if($this->to) $query=$query->andWhere(['<=','created_at',$this->to]);

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'from' => 'Дата от',
            'to' => 'Дата до',
            'phone'=>'Телефон',
            'name'=>'Имя',
            'email'=>'Почта',
            'order' => \Yii::t('admin', 'Sort By:'),
        ];
    }
}