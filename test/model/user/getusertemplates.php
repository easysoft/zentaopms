#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel::getUserTemplates();
cid=1
pid=1



*/
$user = new userTest();

//r() && p('') && e(''); //