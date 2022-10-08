#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getLimitLibs();
cid=1
pid=1

其他类型库查询 >> 0
接口库查询 >> 接口库
产品库查询 >> 多平台产品100
项目库查询 >> 项目主库
接口库查询统计 >> 10
产品库查询统计 >> 100
项目库查询统计 >> 90
执行库查询统计 >> 630
执行库单条查询统计 >> 1

*/
global $tester;
$doc = $tester->loadModel('doc');

$types = array('all', 'api', 'product', 'project', 'execution');
$limit = '1';

r($doc->getLimitLibs($types[0]))                && p()      && e('0');             //其他类型库查询
r($doc->getLimitLibs($types[1]))                && p('901') && e('接口库');        //接口库查询
r($doc->getLimitLibs($types[2]))                && p('100') && e('多平台产品100'); //产品库查询
r($doc->getLimitLibs($types[3]))                && p('110') && e('项目主库');      //项目库查询
r($doc->getLimitLibs($types[4]))                && p('110') && e('');              //执行库查询
r(count($doc->getLimitLibs($types[1])))         && p()      && e('10');            //接口库查询统计
r(count($doc->getLimitLibs($types[2])))         && p()      && e('100');           //产品库查询统计
r(count($doc->getLimitLibs($types[3])))         && p()      && e('90');            //项目库查询统计
r(count($doc->getLimitLibs($types[4])))         && p()      && e('630');           //执行库查询统计
r(count($doc->getLimitLibs($types[4], $limit))) && p()      && e('1');             //执行库单条查询统计