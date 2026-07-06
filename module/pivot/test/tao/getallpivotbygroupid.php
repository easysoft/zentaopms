#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getAllPivotByGroupID();
timeout=0
cid=0

- 步骤1：分组 41 仅返回已发布未删除透视表 @2
- 步骤2：分组 41 按 id 倒序返回 @832
- 步骤3：分组 41 合并 pivotspec 名称 @规格透视表2
- 步骤4：无规格数据的透视表保留原始名称 @无规格透视表
- 步骤5：不存在的分组返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

global $tester;
$dao = $tester->dao;

$pivotIDList = array(831, 832, 833, 834, 835);
$dao->delete()->from(TABLE_PIVOT)->where('id')->in($pivotIDList)->exec();
$dao->delete()->from(TABLE_PIVOTSPEC)->where('pivot')->in($pivotIDList)->exec();

$pivotList = array(
    array('id' => 831, 'name' => '原始透视表1', 'version' => '1.0', 'group' => '41',    'stage' => 'published', 'deleted' => '0'),
    array('id' => 832, 'name' => '原始透视表2', 'version' => '2.0', 'group' => '41,42', 'stage' => 'published', 'deleted' => '0'),
    array('id' => 833, 'name' => '草稿透视表',   'version' => '1.0', 'group' => '41',    'stage' => 'draft',     'deleted' => '0'),
    array('id' => 834, 'name' => '删除透视表',   'version' => '1.0', 'group' => '41',    'stage' => 'published', 'deleted' => '1'),
    array('id' => 835, 'name' => '无规格透视表', 'version' => '1.0', 'group' => '42',    'stage' => 'published', 'deleted' => '0')
);

$pivotSpecList = array(
    array('pivot' => 831, 'version' => '1.0', 'name' => '规格透视表1'),
    array('pivot' => 832, 'version' => '2.0', 'name' => '规格透视表2')
);

foreach($pivotList as $pivot)     $dao->insert(TABLE_PIVOT)->data($pivot)->exec();
foreach($pivotSpecList as $pivot) $dao->insert(TABLE_PIVOTSPEC)->data($pivot)->exec();

su('admin');

$pivotTest      = new pivotTaoTest();
$group41Pivots  = $pivotTest->getAllPivotByGroupIDTest(41);
$group42Pivots  = $pivotTest->getAllPivotByGroupIDTest(42);
$group99Pivots  = $pivotTest->getAllPivotByGroupIDTest(99);

r(count($group41Pivots)) && p() && e('2');
r($group41Pivots[0]->id) && p() && e('832');
r($group41Pivots[0]->name) && p() && e('规格透视表2');
r($group42Pivots[0]->name) && p() && e('无规格透视表');
r(count($group99Pivots)) && p() && e('0');
