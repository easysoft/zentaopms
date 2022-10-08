#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getDocsByBrowseType();
cid=1
pid=1

all类型查询 >> 1,文档标题1
openedbyme类型查询 >> 2,正常产品2
editedbyme类型查询 >> 0
byediteddate类型查询 >> 900,admin
collectedbyme类型查询 >> 0
test类型查询 >> 0
加上moduleId查询 >> 关键词1
all类型查询 >> 900
openedbyme类型查询 >> 900
byediteddate类型查询 >> 900

*/
$browseType = array('all', 'openedbyme', 'editedbyme', 'byediteddate', 'collectedbyme', 'test');
$moduleIds  = array('', '3621', '3622', '3623');

$doc = new docTest();

r($doc->getDocsByBrowseTypeTest($browseType[0], $moduleIds[0])) && p('1:lib,title')             && e('1,文档标题1');//all类型查询
r($doc->getDocsByBrowseTypeTest($browseType[1], $moduleIds[0])) && p('2:lib,objectName')        && e('2,正常产品2');//openedbyme类型查询
r($doc->getDocsByBrowseTypeTest($browseType[2], $moduleIds[0])) && p()                          && e('0');          //editedbyme类型查询
r($doc->getDocsByBrowseTypeTest($browseType[3], $moduleIds[0])) && p('900:lib,users')           && e('900,admin');  //byediteddate类型查询
r($doc->getDocsByBrowseTypeTest($browseType[4], $moduleIds[0])) && p()                          && e('0');          //collectedbyme类型查询
r($doc->getDocsByBrowseTypeTest($browseType[5], $moduleIds[0])) && p()                          && e('0');          //test类型查询
r($doc->getDocsByBrowseTypeTest($browseType[3], $moduleIds))    && p('1:keywords')              && e('关键词1');    //加上moduleId查询
r(count($doc->getDocsByBrowseTypeTest($browseType[0], $moduleIds[0]))) && p('1:lib,title')      && e('900');        //all类型查询
r(count($doc->getDocsByBrowseTypeTest($browseType[1], $moduleIds[0]))) && p('2:lib,objectName') && e('900');        //openedbyme类型查询
r(count($doc->getDocsByBrowseTypeTest($browseType[3], $moduleIds[0]))) && p('900:lib,users')    && e('900');        //byediteddate类型查询