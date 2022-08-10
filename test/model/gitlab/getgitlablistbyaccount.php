#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::getGitLabListByAccount();
cid=1
pid=1

默认admin用户查询绑定的gitlab服务器 >> 0
使用已绑定一个gitlab服务器的用户查询 >> 1
使用未绑定gitlab服务器的用户查询 >> 0

*/

$gitlab = $tester->loadModel('gitlab');

r(count($gitlab->getGitLabListByAccount()))        && p() && e('0'); //默认admin用户查询绑定的gitlab服务器
r(count($gitlab->getGitLabListByAccount('user3'))) && p() && e('1'); //使用已绑定一个gitlab服务器的用户查询
r(count($gitlab->getGitLabListByAccount('test1'))) && p() && e('0'); //使用未绑定gitlab服务器的用户查询