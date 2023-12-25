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
 - 第1条的name属性 @imortRepo1
 - 第1条的SCM属性 @Gitlab
- 批量创建二个版本库
 - 第3条的name属性 @imortRepo3
 - 第3条的SCM属性 @Gitlab
- 批量创建已存在版本库第name条的0属性 @『名称』已经有『imortRepo2』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 批量创建已存在仓库第serviceProject条的0属性 @『仓库』已经有『3』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 批量创建二个版本库,关联多项目
 - 第5条的name属性 @imortRepo5
 - 第5条的projects属性 @5,6,7

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(0);

$_SERVER['REQUEST_URI'] = 'http://unittest.com';

$serviceHosts = array(1);
$repo1 = array('serviceProject' => 1, 'product' => 1, 'name' => 'imortRepo1', 'projects' => 1);
$repo2 = array('serviceProject' => 2, 'product' => 2, 'name' => 'imortRepo2', 'projects' => 2);
$repo3 = array('serviceProject' => 3, 'product' => 3, 'name' => 'imortRepo3', 'projects' => 3);
$repo4 = array('serviceProject' => 4, 'product' => 4, 'name' => 'imortRepo4', 'projects' => 4);
$repo5 = array('serviceProject' => 5, 'product' => 5, 'name' => 'imortRepo5', 'projects' => '5,6,7');

$repo = new repoTest();
r($repo->batchCreateTest(array($repo1), $serviceHosts[0]))         && p('1:name,SCM')           && e('imortRepo1,Gitlab');                                                                               //批量创建一个版本库
r($repo->batchCreateTest(array($repo2, $repo3), $serviceHosts[0])) && p('3:name,SCM')           && e('imortRepo3,Gitlab');                                                                               //批量创建二个版本库
r($repo->batchCreateTest(array($repo2), $serviceHosts[0]))         && p('name:0')               && e('『名称』已经有『imortRepo2』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); //批量创建已存在版本库
r($repo->batchCreateTest(array($repo3), $serviceHosts[0]))         && p('serviceProject:0')     && e('『仓库』已经有『3』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');       //批量创建已存在仓库
r($repo->batchCreateTest(array($repo4, $repo5), $serviceHosts[0])) && p('5:name:projects', ':') && e('imortRepo5:5,6,7');                                                                                //批量创建二个版本库,关联多项目