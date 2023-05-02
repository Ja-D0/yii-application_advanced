<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\QueryInterface;

/**
 * This is the model class for table "{{%ads}}".
 *
 * @property int $id
 * @property string $title
 * @property int|null $author
 * @property int|null $category
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $description
 *
 * @property Category $category_name
 * @property Comments $comments
 */
class Ads extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ads}}';
    }

    public function behaviors()
    {
        return [

            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function($event){
                    date_default_timezone_set('Europe/Moscow');
                    return date("Y-m-d H:i:s");
                },
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['category'], 'integer'],
            [['title', 'author','created_at', 'updated_at'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 256],
            [['category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category' => 'id']],
            [['category_name', 'comments'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название объявления',
            'author' => 'Автор',
            'category' => 'Категория',
            'status' => 'Статус',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'description' => 'Описание',
            'search_string' => 'Поиск по ключевому слову'
        ];
    }

    /**
     * Gets query for [[Category_name]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory_name()
    {
        return $this->hasOne(Category::class, ['id' => 'category']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments(){
        return $this->hasMany(Comments::class, ['ads' => 'id']);
    }
}
