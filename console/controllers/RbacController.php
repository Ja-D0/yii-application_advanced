<?php

namespace console\controllers;

use common\models\Category;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $createAds = $auth->createPermission('createAds');
        $createAds->description = 'Create a ads';
        $auth->add($createAds);

        $updateAds = $auth->createPermission('updateAds');
        $updateAds->description = 'Update ads';
        $auth->add($updateAds);

        $indexAds = $auth->createPermission('indexAds');
        $indexAds->description = 'Index ads';
        $auth->add($indexAds);

        $viewAds = $auth->createPermission('viewAds');
        $viewAds->description = 'View ads';
        $auth->add($viewAds);

        $deleteAds = $auth->createPermission('deleteAds');
        $deleteAds->description = 'Delete ads';
        $auth->add($deleteAds);

        //admin
        $createCategory = $auth->createPermission('createCategory');
        $createCategory->description = 'Create a category';
        $auth->add($createCategory);

        $updateCategory = $auth->createPermission('updateCategory');
        $updateCategory->description = 'Update category';
        $auth->add($updateCategory);

        $indexCategory = $auth->createPermission('indexCategory');
        $indexCategory->description = 'Index category';
        $auth->add($indexCategory);

        $viewCategory = $auth->createPermission('viewCategory');
        $viewCategory->description = 'View category';
        $auth->add($viewCategory);

        $deleteCategory = $auth->createPermission('deleteCategory');
        $deleteCategory->description = 'Delete category';
        $auth->add($deleteCategory);


        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update user';
        $auth->add($updateUser);

        $indexUser = $auth->createPermission('indexUser');
        $indexUser->description = 'Index user';
        $auth->add($indexUser);

        $viewUser = $auth->createPermission('viewUser');
        $viewUser->description = 'View user';
        $auth->add($viewUser);

        $deleteUser= $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete user';
        $auth->add($deleteUser);



        // добавляем роль "user" и даём роли разрешения
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $createAds);
        $auth->addChild($user, $updateAds);
        $auth->addChild($user, $indexAds);
        $auth->addChild($user, $deleteAds);
        $auth->addChild($user, $viewAds);



        // добавляем роль "admin" и даём роли разрешения
        // а также все разрешения роли "user"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $indexUser);
        $auth->addChild($admin, $viewUser);
        $auth->addChild($admin, $deleteUser);
        $auth->addChild($admin, $updateUser);

        $auth->addChild($admin, $indexCategory);
        $auth->addChild($admin, $viewCategory);
        $auth->addChild($admin, $createCategory);
        $auth->addChild($admin, $updateCategory);
        $auth->addChild($admin, $deleteCategory);

        $auth->addChild($admin, $user);


    }
}