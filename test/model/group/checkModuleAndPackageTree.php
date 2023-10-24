#!/usr/bin/env php
<?php

include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');


/**

title=测试groupModel->getModuleAndPackageTree();
cid=1
pid=1


*/
$group = new groupTest();
var_dump($group->getModuleAndPackageTreeTest());
r($group->getModuleAndPackageTreeTest()) && p() && e("获取数据"); 

