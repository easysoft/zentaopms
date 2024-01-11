#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::apiGet();
timeout=0
cid=1

- 用host url 发送一个请求 @success
- 用host ID 发送一个请求 @success
- 用不合规范的host url 发送一个请求 @return null
- 用不存在host ID 发送一个请求 @return null

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$gitlab = new gitlabTest();

$hostID = 1;
$host   = 'https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w';
$host2  = 'abc.com';
$api    = '/user';

r($gitlab->apiGetTest($host, $api))   && p() && e('success'); //用host url 发送一个请求
r($gitlab->apiGetTest($hostID, $api)) && p() && e('success'); //用host ID 发送一个请求
r($gitlab->apiGetTest($host2, $api))  && p() && e('return null'); //用不合规范的host url 发送一个请求
r($gitlab->apiGetTest(0, $api))       && p() && e('return null'); //用不存在host ID 发送一个请求
