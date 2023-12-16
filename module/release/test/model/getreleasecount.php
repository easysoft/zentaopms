#!/usr/bin/env php
<?php

/**

title=taskModel->getReleaseCount();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->config('product')->gen(10);
zdTable('release')->config('release')->gen(100);

global $tester;
$releaseModel = $tester->loadModel('release');
$releaseModel->app->user->admin = true;
$releaseModel->app->user->view  = new stdclass();
$releaseModel->app->user->view->products = '1,2,3';

r($releaseModel->getReleaseCount())            && p() && e('40'); // 获取不传入参数的发布数量
r($releaseModel->getReleaseCount(''))          && p() && e('40'); // 获取传入参数为空的发布数量
r($releaseModel->getReleaseCount('all'))       && p() && e('40'); // 获取系统内所有未删除的发布数量
r($releaseModel->getReleaseCount('milestone')) && p() && e('25'); // 获取系统内所有未删除的里程碑发布数量
r($releaseModel->getReleaseCount('test'))      && p() && e('40'); // 获取传入一个不存在的参数的发布数量
