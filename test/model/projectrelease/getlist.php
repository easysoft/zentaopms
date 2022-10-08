#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/projectrelease.class.php';
su('admin');

/**

title=测试 projectreleaseModel->getList();
cid=1
pid=1

查询projectID正常存在,type为all的发布 >> 产品1发布9,normal
查询projectID正常存在,type为normal的发布 >> 产品1发布9,normal
查询projectID正常存在,type为terminate的发布 >> 0
查询projectID正常存在,type为''的发布 >> 0
查询projectID正常存在的发布 >> 产品1发布9,normal
查询projectID不存在,type为all的发布 >> 0
查询projectID为空,type为all的发布 >> 8

*/

$projectID = array(11, 1000, '');
$type      = array('all', 'normal', 'terminate', '');

$projectrelease = new projectreleaseTest();

r($projectrelease->getListTest($projectID[0], $type[0])) && p('0:name,status') && e('产品1发布9,normal');    //查询projectID正常存在,type为all的发布
r($projectrelease->getListTest($projectID[0], $type[1])) && p('0:name,status') && e('产品1发布9,normal');    //查询projectID正常存在,type为normal的发布
r($projectrelease->getListTest($projectID[0], $type[2])) && p()                && e('0');                    //查询projectID正常存在,type为terminate的发布
r($projectrelease->getListTest($projectID[0], $type[3])) && p()                && e('0');                    //查询projectID正常存在,type为''的发布
r($projectrelease->getListTest($projectID[0]))           && p('0:name,status') && e('产品1发布9,normal');    //查询projectID正常存在的发布
r($projectrelease->getListTest($projectID[1], $type[0])) && p()                && e('0');                    //查询projectID不存在,type为all的发布
r($projectrelease->getListTest($projectID[2], $type[0])) && p('0:id')          && e('8');                    //查询projectID为空,type为all的发布
