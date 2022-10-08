#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/score.class.php';
su('admin');

/**

title=测试 scoreModel->getListByAccount();
cid=1
pid=1

查看account为admin，每页展示15条，第一页的第一条数据 >> admin
查看account为dev10, 每页展示15条，第一页的数据总量 >> 15
查看account为top10, 不传pager(默认分页)的第一条数据 >> top10
查看account为adminn(不存在的用户)的数据 >> 0

*/
global $tester;
$tester->app->loadClass('pager', $static = true);

$score       = new scoreTest();
$accountList = array('admin', 'dev10', 'top10', 'adminn');
$pager       = new pager(0, 15, 1);

r($score->getListByAccountTest($accountList[0], $pager))       && p('0:account')  && e('admin'); // 查看account为admin，每页展示15条，第一页的第一条数据
r($score->getListByAccountTest($accountList[1], $pager, true)) && p('')           && e('15');    // 查看account为dev10, 每页展示15条，第一页的数据总量
r($score->getListByAccountTest($accountList[2], null))         && p('0:account')  && e('top10'); // 查看account为top10, 不传pager(默认分页)的第一条数据
r($score->getListByAccountTest($accountList[3], null))         && p('0:account')  && e('0');     // 查看account为adminn(不存在的用户)的数据