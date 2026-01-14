#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::getRelationByObject();
timeout=0
cid=16658

- 测试步骤1：获取存在的任务关联记录
 - 属性issueID @100
 - 属性gitlabID @1
 - 属性projectID @10
- 测试步骤2：获取存在的bug关联记录
 - 属性issueID @105
 - 属性gitlabID @1
 - 属性projectID @20
- 测试步骤3：获取存在的需求关联记录
 - 属性issueID @110
 - 属性gitlabID @2
 - 属性projectID @30
- 测试步骤4：获取不存在的对象关联记录 @0
- 测试步骤5：测试无效的对象类型参数 @0
- 测试步骤6：测试边界值ID为0的情况 @0
- 测试步骤7：验证返回数据的完整性
 - 属性issueID @100
 - 属性gitlabID @1
 - 属性projectID @10
 - 属性id @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('relation');
$table->id->range('1-15');
$table->product->range('1{15}');
$table->project->range('1{15}');
$table->AType->range('task{5},bug{5},story{5}');
$table->AID->range('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15');
$table->AVersion->range('1{15}');
$table->relation->range('gitlab{15}');
$table->BType->range('issue{15}');
$table->BID->range('100,101,102,103,104,105,106,107,108,109,110,111,112,113,114');
$table->BVersion->range('10{5},20{5},30{5}');
$table->extra->range('1{10},2{5}');
$table->gen(15);

su('admin');

$gitlab = new gitlabModelTest();

r($gitlab->getRelationByObjectTest('task', 1))    && p('issueID,gitlabID,projectID') && e('100,1,10');  // 测试步骤1：获取存在的任务关联记录
r($gitlab->getRelationByObjectTest('bug', 6))     && p('issueID,gitlabID,projectID') && e('105,1,20');  // 测试步骤2：获取存在的bug关联记录
r($gitlab->getRelationByObjectTest('story', 11))  && p('issueID,gitlabID,projectID') && e('110,2,30');  // 测试步骤3：获取存在的需求关联记录
r($gitlab->getRelationByObjectTest('task', 999))  && p() && e('0');                                     // 测试步骤4：获取不存在的对象关联记录
r($gitlab->getRelationByObjectTest('invalid', 1)) && p() && e('0');                                     // 测试步骤5：测试无效的对象类型参数
r($gitlab->getRelationByObjectTest('task', 0))    && p() && e('0');                                     // 测试步骤6：测试边界值ID为0的情况
r($gitlab->getRelationByObjectTest('task', 1))    && p('issueID,gitlabID,projectID,id') && e('100,1,10,1'); // 测试步骤7：验证返回数据的完整性