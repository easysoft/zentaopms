#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 miscModel->getLatestVersionList();
cid=17210

- 不传入URL。 @0
- 检查请求内容。 @1
- 检查返回数组键值。 @0,1,body,header,errno,info,response

- 检查数组键值 1 数据。 @200
- 检查info数组数据。
 - 属性url @https://www.zentao.net/
 - 属性http_code @200
- 检查 errno 数据。 @0
- 检查 request_header 数据。 @1

*/
global $tester, $config;
$miscModel = $tester->loadModel('misc');

$url = 'https://www.zentao.net/';

$_SERVER['HTTP_REFERER'] = $url;
r($miscModel->getLatestVersionList(null)) && p() && e('0');  //不传入URL。
$response1 = $miscModel->getLatestVersionList($url);
r(strpos($response1, '<title>禅道项目管理软件') !== false) && p() && e('1'); //检查请求内容。

$data = $miscModel->getLatestVersionList($url, null, array(), array(), 'json', 'GET', 30, true);
r(implode(',', array_keys($data))) && p()                && e('0,1,body,header,errno,info,response'); //检查返回数组键值。
r($data['1'])                      && p()                && e('200'); //检查数组键值 1 数据。
r($data['info'])                   && p('url,http_code') && e('https://www.zentao.net/,200'); //检查info数组数据。
r($data['errno'])                  && p()                && e('0'); //检查 errno 数据。
r(strpos($data['info']['request_header'], 'Content-Type: application/json;charset=utf-8') !== false) && p() && e('1'); //检查 request_header 数据。
