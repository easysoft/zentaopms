#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createProject();
timeout=0
cid=0

- 执行convertTest模块的createProjectTest方法，参数是$data1, $projectRoleActor1 
 - 属性name @测试项目名称
 - 属性code @TEST1
 - 属性status @wait
 - 属性type @project
- 执行convertTest模块的createProjectTest方法，参数是$data2, array 属性name @长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长
- 执行convertTest模块的createProjectTest方法，参数是$data3, array 属性desc @~~
- 执行convertTest模块的createProjectTest方法，参数是$data4, $projectRoleActor4 
 - 属性name @团队项目
 - 属性type @project
 - 属性model @scrum
- 执行convertTest模块的createProjectTest方法，参数是$data5, array 
 - 属性storyType @story

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('project')->loadYaml('project_createproject', false, 2)->gen(10);
zendata('user')->loadYaml('user_createproject', false, 2)->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 步骤1：正常情况 - 基本Jira项目数据
$data1 = new stdclass();
$data1->pname = '测试项目名称';
$data1->pkey = 'TEST1';
$data1->description = '项目描述内容';
$data1->status = 'wait';
$data1->lead = 'jira_admin';
$data1->created = '2024-01-01 10:00:00';
$data1->id = 1;
$projectRoleActor1 = array();
r($convertTest->createProjectTest($data1, $projectRoleActor1)) && p('name,code,status,type') && e('测试项目名称,TEST1,wait,project');

// 步骤2：边界值 - 长项目名称截取测试
$data2 = new stdclass();
$data2->pname = '长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长名称';
$data2->pkey = 'LONG';
$data2->description = '长名称测试';
$data2->status = 'doing';
$data2->lead = 'jira_user1';
$data2->created = '2024-02-15 14:30:00';
$data2->id = 2;
r($convertTest->createProjectTest($data2, array())) && p('name') && e('长长长长长长长长长长长长长长长长长长长长长长长长长长长长长长');

// 步骤3：异常输入 - 空描述处理
$data3 = new stdclass();
$data3->pname = '无描述项目';
$data3->pkey = 'NODESC';
$data3->status = 'done';
$data3->lead = 'jira_lead';
$data3->created = '2024-03-10 09:15:00';
$data3->id = 3;
r($convertTest->createProjectTest($data3, array())) && p('desc') && e('~~');

// 步骤4：业务规则 - 包含团队成员的项目
$data4 = new stdclass();
$data4->pname = '团队项目';
$data4->pkey = 'TEAM';
$data4->description = '包含团队成员的项目';
$data4->status = 'wait';
$data4->lead = 'jira_admin';
$data4->created = '2024-04-01 16:00:00';
$data4->id = 4;
$projectRoleActor4 = array(4 => array('jira_user1', 'jira_user2'));
r($convertTest->createProjectTest($data4, $projectRoleActor4)) && p('name,type,model') && e('团队项目,project,scrum');

// 步骤5：权限验证 - 项目默认设置验证
$data5 = new stdclass();
$data5->pname = '默认设置项目';
$data5->pkey = 'DEFAULT';
$data5->description = '验证默认设置';
$data5->status = 'closed';
$data5->lead = '';
$data5->created = '';
$data5->id = 5;
r($convertTest->createProjectTest($data5, array())) && p('storyType') && e('story,epic,requirement');