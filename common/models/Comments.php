<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%comments}}".
 *
 * @property int $id
 * @property string $content
 * @property int|null $author
 * @property int|null $ads
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ads $ads0
 * @property User $author0
 */
class Comments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%comments}}';
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
            [['content'], 'required'],
            [['author', 'ads'], 'integer'],
            [['content'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'string', 'max' => 20],
            [['ads'], 'exist', 'skipOnError' => true, 'targetClass' => Ads::class, 'targetAttribute' => ['ads' => 'id']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'author' => 'Author',
            'ads' => 'Ads',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Ads0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAds0()
    {
        return $this->hasOne(Ads::class, ['id' => 'ads']);
    }

    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor0()
    {
        return $this->hasOne(User::class, ['id' => 'author']);
    }
}
