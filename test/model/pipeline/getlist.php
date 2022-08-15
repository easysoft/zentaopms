#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/pipeline.class.php';
su('admin');

/**

title=测试 pipelineModel->getList();
cid=1
pid=1

获取第一页第一条数据的name >> gitlab服务器
统计所有type为gitlab的数量 >> 1
统计所有type为sonarqube的数据量 >> 1

*/

global $tester;
$pipeline = new pipelineTest();
$tester->app->loadClass('pager', $static = true);

$pager = new pager(0, 10, 1);

$gitlabList    = $pipeline->getListTest('gitlab', 'id_desc', $pager);
$sonarqubeList = $pipeline->getListTest('sonarqube ', 'id_desc', $pager);

r($gitlabList)           && p('1:name') && e('gitlab服务器'); //获取第一页第一条数据的name
r(count($gitlabList))    && p()         && e('1');            //统计所有type为gitlab的数量
r(count($sonarqubeList)) && p()         && e('1');            //统计所有type为sonarqube的数据量