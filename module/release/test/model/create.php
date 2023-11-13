#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->create();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

$build = zdTable('build')->config('build');
$build->project->range('1{2},0{3}');
$build->execution->range('0,101,0{3}');
$build->gen(5);

zdTable('story')->config('story')->gen(5);
zdTable('bug')->gen(0);
zdTable('release')->gen(0);
zdTable('user')->gen(5);
su('admin');

$syncList = array(true, false);

$executionRelease    = array('product' => 1, 'branch' => 0, 'project' => 101, 'name' => '新增执行版本发布', 'marker' => 0, 'build' => '1', 'desc' => '');
$projectRelease      = array('product' => 1, 'branch' => 0, 'project' => 11,  'name' => '新增项目版本发布', 'marker' => 0, 'build' => '2', 'desc' => '');
$markerRelease       = array('product' => 1, 'branch' => 0, 'project' => 0,   'name' => '新增里程碑发布',   'marker' => 1, 'build' => '',  'desc' => '');
$syncRelease         = array('product' => 1, 'branch' => 0, 'project' => 0,   'name' => '新增关联发布',     'marker' => 0, 'build' => '3',  'desc' => '');
$noSyncnormalRelease = array('product' => 1, 'branch' => 0, 'project' => 0,   'name' => '新增不关联发布',   'marker' => 0, 'build' => '3',  'desc' => '');
$noNameRelease       = array('product' => 1, 'branch' => 0, 'project' => 0,   'name' => '',                 'marker' => 0, 'build' => '',  'desc' => '');

$releaseTester = new releaseTest();
r($releaseTester->createTest($executionRelease,    $syncList[0])) && p('id,name,build') && e('1,新增执行版本发布,1');                                                                                    // 执行版本新增发布
r($releaseTester->createTest($projectRelease,      $syncList[0])) && p('id,name,build') && e('2,新增项目版本发布,2');                                                                                     // 项目版本新增发布
r($releaseTester->createTest($markerRelease,       $syncList[0])) && p('id,name') && e('3,新增里程碑发布');                                                                                      // 创建里程碑发布
r($releaseTester->createTest($syncRelease,         $syncList[0])) && p('id,name,build') && e('4,新增关联发布,3');                                                                                        // 创建将版本中完成的研发需求和已解决的Bug关联的发布
r($releaseTester->createTest($noSyncnormalRelease, $syncList[1])) && p('id,name,build') && e('5,新增不关联发布,3');                                                                                      // 创建将版本中完成的研发需求和已解决的Bug不关联的发布
r($releaseTester->createTest($noNameRelease,       $syncList[0])) && p('name:0')        && e('『发布名称』不能为空。');                                                                                    // 名称为空测试
r($releaseTester->createTest($executionRelease,    $syncList[0])) && p('name:0')        && e('『发布名称』已经有『新增执行版本发布』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 名称重复测试
