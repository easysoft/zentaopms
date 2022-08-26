#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->checkNovice();
cid=1
pid=1

检查当account是admin的时候的返回值。 >> 0
检查当->app->user->modifyPassword是不为空的时候的返回值。 >> 0
检查当account是guest的时候的返回值。 >> 0

*/

$tutorial = new tutorialTest();

r($tutorial->checkNoviceTest()) && p() && e('0'); //检查当account是admin的时候的返回值。
r($tutorial->checkNoviceTest()) && p() && e('0'); //检查当$this->app->user->modifyPassword是不为空的时候的返回值。
su('guest');
r($tutorial->checkNoviceTest()) && p() && e('0'); //检查当account是guest的时候的返回值。