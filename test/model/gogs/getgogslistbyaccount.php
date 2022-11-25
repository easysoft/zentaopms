#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gogsModel::getGogsListByAccount();
cid=1
pid=1

*/

$gogs = $tester->loadModel('gogs');

r(count($gogs->getGogsListByAccount()))        && p() && e('0'); //默认admin用户查询绑定的gogs服务器
r(count($gogs->getGogsListByAccount('user4'))) && p() && e('1'); //使用已绑定一个gogs服务器的用户查询
r(count($gogs->getGogsListByAccount('test1'))) && p() && e('0'); //使用未绑定gogs服务器的用户查询
