#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 adminModel::fetchAPI();
cid=1
pid=1

正确的API地址 >> 1
错误的API地址 >> 0

*/

global $tester;
$tester->loadModel('admin');

$realURL    = 'https://www.zentao.net/publicclass.json';
$invalidURL = 'https://test.zentao.net/publicclass.json';

$result1 = (int)!empty($tester->admin->fetchAPI($realURL));
$result2 = (int)$tester->admin->fetchAPI($invalidURL);

r($result1) && p() && e('1'); // 正确的API地址
r($result2) && p() && e('0'); // 错误的API地址
