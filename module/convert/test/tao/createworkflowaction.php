#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflowAction();
timeout=0
cid=0

- 步骤1：空relations参数处理 @0
- 步骤2：open版本直接返回属性test @value
- 步骤3：无zentaoAction键处理第normalKey条的action1属性 @value1
- 步骤4：非add_action情况第zentaoActionbug条的action1属性 @other_action
- 步骤5：创建新工作流动作 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 定义JIRA临时关系表常量
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

// 2. 创建临时表
global $tester, $config;
$tableName = $config->db->prefix . 'jiratmprelation';
$sql = <<<EOT
CREATE TABLE IF NOT EXISTS `{$tableName}`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL,
  `AID` char(100) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` char(100) NOT NULL,
  `extra` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
EOT;
$tester->dbh->exec($sql);

// 3. zendata数据准备（根据需要配置）
$workflowActionTable = zenData('workflowaction');
$workflowActionTable->gen(0);

$jiraTmpRelationTable = zenData('jiratmprelation');
$jiraTmpRelationTable->AType->range('jflowaction{5}');
$jiraTmpRelationTable->AID->range('action1,action2,action3,action4,action5');
$jiraTmpRelationTable->BType->range('zworkflowaction{5}');
$jiraTmpRelationTable->BID->range('bugaction1,storyaction1,taskaction1,productaction1,testaction1');
$jiraTmpRelationTable->extra->range('bug,story,task,product,test');
$jiraTmpRelationTable->gen(5);

// 4. 用户登录（选择合适角色）
su('admin');

// 5. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 模拟版本为biz版本以启用工作流功能
global $config;
$originalEdition = $config->edition;
$config->edition = 'biz';

// 6. 测试步骤
r($convertTest->createWorkflowActionTest(array(), array())) && p() && e('0'); // 步骤1：空relations参数处理

// 测试open版本直接返回
$config->edition = 'open';
$relations = array('test' => 'value');
r($convertTest->createWorkflowActionTest($relations, array())) && p('test') && e('value'); // 步骤2：open版本直接返回

// 恢复为biz版本
$config->edition = 'biz';

// 测试无zentaoAction键的relations
$relations = array('normalKey' => array('action1' => 'value1'));
r($convertTest->createWorkflowActionTest($relations, array())) && p('normalKey:action1') && e('value1'); // 步骤3：无zentaoAction键处理

// 测试zentaoAction但非add_action的情况
$relations = array(
    'zentaoActionbug' => array('action1' => 'other_action'),
    'zentaoObject' => array('bug' => 'bug')
);
r($convertTest->createWorkflowActionTest($relations, array())) && p('zentaoActionbug:action1') && e('other_action'); // 步骤4：非add_action情况

// 测试创建新工作流动作
$relations = array(
    'zentaoActionbug' => array('newaction1' => 'add_action'),
    'zentaoObject' => array('bug' => 'bug')
);
$jiraActions = array(
    'actions' => array(
        'newaction1' => array(
            'name' => '新建动作',
            'id' => 'newaction1'
        )
    ),
    'steps' => array()
);

$result = $convertTest->createWorkflowActionTest($relations, $jiraActions);
r($result !== false) && p() && e('1'); // 步骤5：创建新工作流动作

// 恢复原始配置
$config->edition = $originalEdition;