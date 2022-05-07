#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getApiLibs();
cid=1
pid=1

查询全部接口库 >> api
查询额外的产品库 >> product
查询接口库 >> 接口库
查询全部接口库统计 >> 10
查询额外的产品库统计 >> 11
查询接口库统计 >> 10

*/
global $tester;
$doc = $tester->loadModel('doc');

$docLibIDList = array('0', '17', '901');

r($doc->getApiLibs($docLibIDList[0]))        && p('901:type') && e('api');    //查询全部接口库
r($doc->getApiLibs($docLibIDList[1]))        && p('17:type')  && e('product');//查询额外的产品库
r($doc->getApiLibs($docLibIDList[2]))        && p('902:name') && e('接口库'); //查询接口库
r(count($doc->getApiLibs($docLibIDList[0]))) && p()           && e('10');     //查询全部接口库统计
r(count($doc->getApiLibs($docLibIDList[1]))) && p()           && e('11');     //查询额外的产品库统计
r(count($doc->getApiLibs($docLibIDList[2]))) && p()           && e('10');     //查询接口库统计