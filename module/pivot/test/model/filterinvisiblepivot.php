#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::filterInvisiblePivot();
timeout=0
cid=0

- 步骤1：管理员过滤结果包含全部可见透视表 @4
- 步骤2：普通用户可见全部未删除透视表 @911;912;913;915
- 步骤3：白名单用户同样可见全部未删除透视表 @911;912;913;915
- 步骤4：空数组输入返回空数组 @0
- 步骤5：全部不可见透视表被过滤为空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$dao = $tester->dao;

$pivotIDList = array(911, 912, 913, 914, 915);
$dao->delete()->from(TABLE_PIVOT)->where('id')->in($pivotIDList)->exec();

$pivotList = array(
    array('id' => 911, 'name' => '开放透视表',   'version' => '1', 'createdBy' => 'admin', 'acl' => 'open',    'whitelist' => '',      'stage' => 'published', 'deleted' => '0'),
    array('id' => 912, 'name' => '用户私有表',   'version' => '1', 'createdBy' => 'user1', 'acl' => 'private', 'whitelist' => '',      'stage' => 'published', 'deleted' => '0'),
    array('id' => 913, 'name' => '白名单透视表', 'version' => '1', 'createdBy' => 'admin', 'acl' => 'private', 'whitelist' => 'user2', 'stage' => 'published', 'deleted' => '0'),
    array('id' => 914, 'name' => '已删除透视表', 'version' => '1', 'createdBy' => 'admin', 'acl' => 'open',    'whitelist' => '',      'stage' => 'published', 'deleted' => '1'),
    array('id' => 915, 'name' => '受限透视表',   'version' => '1', 'createdBy' => 'admin', 'acl' => 'private', 'whitelist' => 'admin', 'stage' => 'published', 'deleted' => '0')
);

foreach($pivotList as $pivot) $dao->insert(TABLE_PIVOT)->data($pivot)->exec();

$allPivots       = array_values($dao->select('*')->from(TABLE_PIVOT)->where('id')->in($pivotIDList)->orderBy('id')->fetchAll());
$deletedPivots   = array_values($dao->select('*')->from(TABLE_PIVOT)->where('id')->in('914')->orderBy('id')->fetchAll());

$pivotTest = new pivotModelTest();

su('admin');
r(count($pivotTest->filterInvisiblePivotTest($allPivots))) && p() && e('4');

su('user1');
r($pivotTest->filterInvisiblePivotTest($allPivots)) && p('0:id;1:id;2:id;3:id') && e('911;912;913;915');

su('user2');
r($pivotTest->filterInvisiblePivotTest($allPivots)) && p('0:id;1:id;2:id;3:id') && e('911;912;913;915');

su('admin');
r(count($pivotTest->filterInvisiblePivotTest(array()))) && p() && e('0');

su('user1');
r(count($pivotTest->filterInvisiblePivotTest($deletedPivots))) && p() && e('0');
