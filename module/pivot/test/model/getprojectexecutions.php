#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 pivotModel::getProjectExecutions();
timeout=0
cid=17397

- 步骤1：验证返回数组类型 @1
- 步骤2：验证多执行项目格式化 @项目1/迭代1
- 步骤3：验证单执行项目格式化 @项目2
- 步骤4：验证阶段也按多执行项目格式化 @项目1/阶段1
- 步骤5：验证目标执行都存在于结果中 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
foreach(array(
    (object)array('id' => 9001, 'name' => '项目1', 'type' => 'project', 'project' => 0,    'multiple' => 0, 'deleted' => 0),
    (object)array('id' => 9002, 'name' => '项目2', 'type' => 'project', 'project' => 0,    'multiple' => 0, 'deleted' => 0),
    (object)array('id' => 9101, 'name' => '迭代1', 'type' => 'sprint',  'project' => 9001, 'multiple' => 1, 'deleted' => 0),
    (object)array('id' => 9102, 'name' => '迭代2', 'type' => 'sprint',  'project' => 9002, 'multiple' => 0, 'deleted' => 0),
    (object)array('id' => 9103, 'name' => '阶段1', 'type' => 'stage',   'project' => 9001, 'multiple' => 1, 'deleted' => 0)
) as $project)
{
    $tester->dao->insert(TABLE_PROJECT)->data($project)->exec();
}

$pivot  = new pivotModelTest();
$result = $pivot->getProjectExecutions();

r(is_array($result))                                                           && p()        && e('1');
r($result)                                                                     && p('9101')  && e('项目1/迭代1');
r($result)                                                                     && p('9102')  && e('项目2');
r($result)                                                                     && p('9103')  && e('项目1/阶段1');
r(count(array_intersect_key($result, array(9101 => true, 9102 => true, 9103 => true)))) && p() && e('3');
