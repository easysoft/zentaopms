#!/usr/bin/env php
<?php

/**

title=测试 programZen::buildProgramForEdit();
cid=0

- 测试步骤1：编辑正常项目集，提供完整数据 >> 返回包含正确ID和名称的项目集对象
- 测试步骤2：编辑项目集，设置长期项目 >> 结束日期设为2059-12-31
- 测试步骤3：编辑项目集，开始日期为空 >> begin字段为空字符串
- 测试步骤4：编辑项目集，设置实际开始日期，状态从wait变为doing >> status变为doing
- 测试步骤5：编辑项目集，设置开放权限控制 >> 白名单为空字符串
- 测试步骤6：编辑不存在的项目集ID >> 返回编辑对象，但无原始数据
- 测试步骤7：编辑项目集，不设置预算单位 >> 保持原有预算单位

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

// 2. zendata数据准备
zenData('project')->gen(0); // 清空数据
$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目集1-项目集10');
$projectTable->type->range('program');
$projectTable->status->range('wait{3},doing{4},closed{3}');
$projectTable->begin->range('2024-01-01{5},2024-06-01{5}');
$projectTable->end->range('2024-12-31{5},2025-12-31{5}');
$projectTable->budgetUnit->range('CNY{5},USD{5}');
$projectTable->acl->range('open{5},private{5}');
$projectTable->parent->range('0');
$projectTable->path->range(',1,{1},2,{1},3,{1},4,{1},5,{1},6,{1},7,{1},8,{1},9,{1},10,');
$projectTable->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$programTest = new programTest();

// 5. 测试步骤

// 步骤1：编辑正常项目集，提供完整数据
$postData1 = array(
    'name' => '更新的项目集名称',
    'begin' => '2024-03-01',
    'end' => '2024-09-30',
    'budget' => '100000',
    'budgetUnit' => 'CNY',
    'acl' => 'private'
);
r($programTest->buildProgramForEditTest(1, $postData1)) && p('id,name,lastEditedBy') && e('1,更新的项目集名称,admin');

// 步骤2：编辑项目集，设置长期项目
$postData2 = array(
    'name' => '长期项目集',
    'begin' => '2024-01-01',
    'longTime' => true
);
r($programTest->buildProgramForEditTest(2, $postData2)) && p('name,end') && e('长期项目集,2059-12-31');

// 步骤3：编辑项目集，开始日期为空（0000-00-00格式）
$postData3 = array(
    'name' => '无开始日期项目集',
    'begin' => '0000-00-00',
    'end' => '2024-12-31'
);
r($programTest->buildProgramForEditTest(3, $postData3)) && p('name,begin') && e('无开始日期项目集,');

// 步骤4：编辑项目集，设置实际开始日期，状态从wait变为doing
$postData4 = array(
    'name' => '启动中的项目集',
    'realBegan' => '2024-03-15',
    'begin' => '2024-03-01',
    'end' => '2024-12-31'
);
r($programTest->buildProgramForEditTest(1, $postData4)) && p('name,status') && e('启动中的项目集,doing');

// 步骤5：编辑项目集，设置开放权限控制
$postData5 = array(
    'name' => '开放权限项目集',
    'acl' => 'open',
    'whitelist' => array('user1', 'user2')
);
r($programTest->buildProgramForEditTest(5, $postData5)) && p('name,acl,whitelist') && e('开放权限项目集,open,');

// 步骤6：编辑不存在的项目集ID
$postData6 = array(
    'name' => '不存在的项目集',
    'begin' => '2024-04-01',
    'end' => '2024-10-31'
);
r($programTest->buildProgramForEditTest(999, $postData6)) && p('id,name') && e('999,不存在的项目集');

// 步骤7：编辑项目集，不设置预算单位（保持原有值）
$postData7 = array(
    'name' => '保持预算单位项目集',
    'budget' => '50000'
);
r($programTest->buildProgramForEditTest(7, $postData7)) && p('name,budgetUnit') && e('保持预算单位项目集,USD');