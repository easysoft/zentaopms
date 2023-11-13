#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->batchCreate();
timeout=0
cid=1

- 批量创建一个版本库
 - 第2条的name属性 @imortRepo1
 - 第2条的SCM属性 @Gitlab

- 批量创建二个版本库
 - 第4条的name属性 @imortRepo3
 - 第4条的SCM属性 @Gitlab

- 批量创建已存在版本库 @『名称』已经有『imortRepo2』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

- 批量创建已存在仓库 @『仓库』已经有『1599』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

- 批量创建二个版本库,关联多项目
 - 第6条的name属性 @imortRepo5
 - 第6条的projects属性 @5,6,7

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->gen(1);

$serviceHosts = array(1);
$repo1 = array('serviceProject' => 1552, 'product' => 1, 'name' => 'imortRepo1', 'projects' => 1);
$repo2 = array('serviceProject' => 1574, 'product' => 2, 'name' => 'imortRepo2', 'projects' => 2);
$repo3 = array('serviceProject' => 1599, 'product' => 3, 'name' => 'imortRepo3', 'projects' => 3);
$repo4 = array('serviceProject' => 1617, 'product' => 4, 'name' => 'imortRepo4', 'projects' => 4);
$repo5 = array('serviceProject' => 1618, 'product' => 5, 'name' => 'imortRepo5', 'projects' => '5,6,7');

$repo = new repoTest();
r($repo->batchCreateTest(array($repo1), $serviceHosts[0]))         && p('2:name,SCM')           && e('imortRepo1,Gitlab');                                                                               //批量创建一个版本库
r($repo->batchCreateTest(array($repo2, $repo3), $serviceHosts[0])) && p('4:name,SCM')           && e('imortRepo3,Gitlab');                                                                               //批量创建二个版本库
r($repo->batchCreateTest(array($repo2), $serviceHosts[0]))         && p('name:0')               && e('『名称』已经有『imortRepo2』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); //批量创建已存在版本库
r($repo->batchCreateTest(array($repo3), $serviceHosts[0]))         && p('serviceProject:0')     && e('『仓库』已经有『1599』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');       //批量创建已存在仓库
r($repo->batchCreateTest(array($repo4, $repo5), $serviceHosts[0])) && p('6:name:projects', ':') && e('imortRepo5:5,6,7');                                                                                //批量创建二个版本库,关联多项目