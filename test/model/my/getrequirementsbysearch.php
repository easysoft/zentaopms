#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/my.class.php';
su('admin');

/**

title=测试 myModel->getRequirementsBySearch();
cid=1
pid=1

获取requirement状态的项目 >> 用户需求397,draft
获取requirement状态的项目 >> 用户需求1,active

*/

$my       = new myTest();
$typeList = array('contribute', 'other');
$orderBy  = array('id_desc', 'id_asc');

$requirement1 = $my->getRequirementsBySearchTest(0, $typeList[0], $orderBy[0]);
$requirement2 = $my->getRequirementsBySearchTest(0, $typeList[1], $orderBy[1]);
r($requirement1) && p('397:title,status') && e('用户需求397,draft');//获取requirement状态的项目
r($requirement2) && p('1:title,status')   && e('用户需求1,active'); //获取requirement状态的项目