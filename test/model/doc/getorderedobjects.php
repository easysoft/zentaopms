#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getOrderedObjects();
cid=1
pid=1

all类型查询 >> 0
api类型查询 >> 0
product类型查询 >> 已关闭的多分支产品70
project类型查询 >> 项目87
execution类型查询 >> 项目60 / 阶段600
product类型查询统计 >> 120
project类型查询统计 >> 110
execution类型查询统计 >> 630

*/
global $tester;
$doc = $tester->loadModel('doc');

$types = array('all', 'api', 'product', 'project', 'execution');

r($doc->getOrderedObjects($types[0]))        && p()      && e('0');                    //all类型查询
r($doc->getOrderedObjects($types[1]))        && p()      && e('0');                    //api类型查询
r($doc->getOrderedObjects($types[2]))        && p('70')  && e('已关闭的多分支产品70'); //product类型查询
r($doc->getOrderedObjects($types[3]))        && p('97')  && e('项目87');               //project类型查询
r($doc->getOrderedObjects($types[4]))        && p('700') && e('项目60 / 阶段600');     //execution类型查询
r(count($doc->getOrderedObjects($types[2]))) && p('70')  && e('120');                  //product类型查询统计
r(count($doc->getOrderedObjects($types[3]))) && p('97')  && e('110');                  //project类型查询统计
r(count($doc->getOrderedObjects($types[4]))) && p('700') && e('630');                  //execution类型查询统计