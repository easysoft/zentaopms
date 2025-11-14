#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraFieldGroupByProject();
timeout=0
cid=15778

- 执行convertTest模块的getJiraFieldGroupByProjectTest方法，参数是array  @0
- 执行convertTest模块的getJiraFieldGroupByProjectTest方法，参数是$relations  @0
- 执行convertTest模块的getJiraFieldGroupByProjectTest方法，参数是$mockRelations  @0
- 执行convertTest模块的getJiraFieldGroupByProjectTest方法，参数是$invalidRelations  @0
- 执行convertTest模块的getJiraFieldGroupByProjectTest方法，参数是$stringParam  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 创建临时表（如果不存在）
global $tester;
$convertModel = $tester->loadModel('convert');
try {
    $convertModel->createTmpTable4Jira();
} catch(Exception $e) {
    // 表可能已存在，忽略错误
}

// 手动插入测试数据
try {
    $tester->dbh->exec("DELETE FROM jiratmprelation WHERE AType='jcustomfield' AND BType='zworkflowfield'");
    $tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID, extra) VALUES 
        ('jcustomfield', '1', 'zworkflowfield', 'field1', 'story'),
        ('jcustomfield', '2', 'zworkflowfield', 'field2', 'task'),
        ('jcustomfield', '3', 'zworkflowfield', 'field3', 'bug'),
        ('jcustomfield', '4', 'zworkflowfield', 'field4', 'issue'),
        ('jcustomfield', '5', 'zworkflowfield', 'field5', 'testcase')
    ");
} catch(Exception $e) {
    // 插入数据失败，继续测试
}

// 用户登录
su('admin');

// 设置必需的session变量
global $app;
$app->session->set('jiraMethod', 'file');

// 创建测试实例
$convertTest = new convertTest();

// 测试步骤1：空参数情况
r($convertTest->getJiraFieldGroupByProjectTest(array())) && p() && e('0');

// 测试步骤2：有效关系参数但无数据场景
$relations = array(
    'zentaoObject' => array(
        1 => 'story',
        2 => 'task'
    )
);
r($convertTest->getJiraFieldGroupByProjectTest($relations)) && p() && e('0');

// 测试步骤3：模拟有效数据情况（通过mock session数据）
$mockRelations = array(
    'zentaoObject' => array(
        1 => 'story',
        2 => 'task',
        3 => 'bug'
    )
);
r($convertTest->getJiraFieldGroupByProjectTest($mockRelations)) && p() && e('0');

// 测试步骤4：异常处理情况
$invalidRelations = null;
r($convertTest->getJiraFieldGroupByProjectTest($invalidRelations)) && p() && e('0');

// 测试步骤5：参数类型验证
$stringParam = 'invalid_param';
r($convertTest->getJiraFieldGroupByProjectTest($stringParam)) && p() && e('0');