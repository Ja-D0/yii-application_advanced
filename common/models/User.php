<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $nickname
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property Ads $ads
 * @property Comments $comments
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS = ['Пользователь', 'Администратор'];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
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
            [['username', 'nickname', 'auth_key', 'password_hash', 'email'], 'required'],
            [['username', 'nickname', 'created_at', 'updated_at'], 'string', 'max' => 20],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'email', 'status', 'verification_token'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
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

    public function setStatus($status)
    {
        $this->status = User::STATUS[$status];
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

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function getMenuItems($user){
        $menuItems = [
            ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
        ];
        if ($user->isGuest){
            $menuItems[] = ['label' => 'Регистрация', 'url' => '/site/signup'];
            return $menuItems;
        }else{
            switch ($user->identity->status){
                case 'Пользователь':{
                    $menuItems[] = ['label' => 'Мои объявления', 'url' => '/ads/index'];
                    $menuItems[] = ['label' => 'Все объявления', 'url' => '/ads/ads'];
                    return $menuItems;
                }
                case 'Администратор':{
                    if (Yii::$app->id == 'app-backend') {
                        $menuItems[] = ['label' => 'Все объявления', 'url' => '/ads/index'];
                        $menuItems[] = ['label' => 'Категории', 'url' => '/category/index'];
                        $menuItems[] = ['label' => 'Пользователи', 'url' => '/user/index'];
                        $menuItems[] = ['label' => 'Комментарии', 'url' => '/comments/index'];

                    } else {
                        $menuItems[] = ['label' => 'Мои объявления', 'url' => '/ads/index'];
                        $menuItems[] = ['label' => 'Все объявления', 'url' => '/ads/ads'];

                    }
                    return $menuItems;
                }
            }

        }

    }
    public function getAds()
    {
        return $this->hasMany(Ads::class, ['author' => 'id']);
    }
    public function getComments(){
        return $this->hasMany(Comments::class, ['author' => 'id']);
    }
}
