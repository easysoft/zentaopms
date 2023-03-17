#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->create();
cid=1
pid=1

执行版本新增发布 >> 11,新增执行版本发布,13
项目版本新增发布 >> 12,新增项目版本发布,3
创建里程碑发布 >> 13,新增里程碑发布,13
创建将版本中完成的研发需求和已解决的Bug关联的发布 >> 14,新增关联发布,13
创建将版本中完成的研发需求和已解决的Bug不关联的发布 >> 15,新增不关联发布,13
名称为空测试 >> 『发布名称』不能为空。
名称重复测试 >> 『发布名称』已经有『新增执行版本发布』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。

*/

$productID = 3;
$branch    = 'all';
$projectID = '0';

$release = new releaseTest();

$executionRelease    = array('name' => '新增执行版本发布', 'marker' => '0', 'build' => '13', 'desc' => '', 'sync' => true);
$projectRelease      = array('name' => '新增项目版本发布', 'marker' => '0', 'build' => '3',  'desc' => '', 'sync' => true);
$markerRelease       = array('name' => '新增里程碑发布',   'marker' => '1', 'build' => '13', 'desc' => '', 'sync' => true);
$syncRelease         = array('name' => '新增关联发布',     'marker' => '0', 'build' => '13', 'desc' => '', 'sync' => true);
$noSyncnormalRelease = array('name' => '新增不关联发布',   'marker' => '0', 'build' => '13', 'desc' => '', 'sync' => false);
$noNameRelease       = array('name' => '',                 'marker' => '0', 'build' => '13', 'desc' => '', 'sync' => true);

r($release->createTest($projectID, $branch, $projectID, $executionRelease))    && p('id,name,build') && e('11,新增执行版本发布,13'); //执行版本新增发布
r($release->createTest($projectID, $branch, $projectID, $projectRelease))      && p('id,name,build') && e('12,新增项目版本发布,3'); //项目版本新增发布
r($release->createTest($projectID, $branch, $projectID, $markerRelease))       && p('id,name,build') && e('13,新增里程碑发布,13'); //创建里程碑发布
r($release->createTest($projectID, $branch, $projectID, $syncRelease))         && p('id,name,build') && e('14,新增关联发布,13'); //创建将版本中完成的研发需求和已解决的Bug关联的发布
r($release->createTest($projectID, $branch, $projectID, $noSyncnormalRelease)) && p('id,name,build') && e('15,新增不关联发布,13'); //创建将版本中完成的研发需求和已解决的Bug不关联的发布
r($release->createTest($projectID, $branch, $projectID, $noNameRelease))       && p('name:0') && e('『发布名称』不能为空。'); //名称为空测试
r($release->createTest($projectID, $branch, $projectID, $executionRelease))    && p('name:0') && e('『发布名称』已经有『新增执行版本发布』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); //名称重复测试
