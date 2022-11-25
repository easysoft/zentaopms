#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->apiGetSingleTag();
cid=1
pid=1

查询正确的tag信息                >> 1230
使用不存在的gitlabID查询tag信息  >> 0
使用不存在的projectID查询tag信息 >> 404 Project Not Found
使用不存在的tag名称查询tag信息   >> 404 Tag Not Found

*/

$gitlab = new gitlabTest();

$tag1 = $gitlab->apiGetSingleTagTest(1, 1569, '1230');
$tag2 = $gitlab->apiGetSingleTagTest(0, 1569, '1230');
$tag3 = $gitlab->apiGetSingleTagTest(1, 0,    '1230');
$tag4 = $gitlab->apiGetSingleTagTest(1, 1569, '0');

r($tag1) && p('name')    && e('1230');                   // 查询正确的tag信息
r($tag2) && p()          && e('0');                      // 使用不存在的gitlabID查询tag信息
r($tag3) && p('message') && e('404 Project Not Found');  // 使用不存在的projectID查询tag信息
r($tag4) && p('message') && e('404 Tag Not Found');      // 使用不存在的tag名称查询tag信息
