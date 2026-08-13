#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::filterInvisiblePivot();
timeout=0
cid=0

- 执行pivotTest模块的filterInvisiblePivotTest方法，参数是$allPivots  @4
- 执行pivotTest模块的filterInvisiblePivotTest方法，参数是$allPivots 
 - 第0条的id属性 @911
 - 第1条的id属性 @912
- 执行pivotTest模块的filterInvisiblePivotTest方法，参数是$allPivots 
 - 第0条的id属性 @911
 - 第1条的id属性 @913
- 执行pivotTest模块的filterInvisiblePivotTest方法，参数是array  @0
- 执行pivotTest模块的filterInvisiblePivotTest方法，参数是$deletedPivots  @0

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
r($pivotTest->filterInvisiblePivotTest($allPivots)) && p('0:id;1:id') && e('911;912');

su('user2');
r($pivotTest->filterInvisiblePivotTest($allPivots)) && p('0:id;1:id') && e('911;913');

su('admin');
r(count($pivotTest->filterInvisiblePivotTest(array()))) && p() && e('0');

su('user1');
r(count($pivotTest->filterInvisiblePivotTest($deletedPivots))) && p() && e('0');