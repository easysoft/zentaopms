#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflowField();
timeout=0
cid=15851

- 执行convertTest模块的createWorkflowFieldTest方法，参数是$relations1, array 第zentaoObject条的Story属性 @story
- 执行convertTest模块的createWorkflowFieldTest方法，参数是$relations2, array 第zentaoObject条的Story属性 @story
- 执行$result3['zentaoFieldStory']['customfield_10001']) && strpos($result3['zentaoFieldStory']['customfield_10001'], 'jirafield') === 0 ? 1 : 0 @1
- 执行convertTest模块的createWorkflowFieldTest方法，参数是$relations4, $fields4, array 第zentaoFieldStory条的customfield_10002属性 @existingfield001
- 执行$result5['zentaoFieldBug']['customfield_10003']) && strpos($result5['zentaoFieldBug']['customfield_10003'], 'jirafield') === 0 ? 1 : 0 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 定义常量
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', 'jiratmprelation');

global $tester, $config;

// 创建临时表
$sql = "CREATE TABLE IF NOT EXISTS `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL DEFAULT '',
  `AID` char(100) NOT NULL DEFAULT '',
  `BType` char(30) NOT NULL DEFAULT '',
  `BID` char(100) NOT NULL DEFAULT '',
  `extra` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

try {
    $tester->dbh->exec($sql);
    $tester->dbh->exec('TRUNCATE TABLE jiratmprelation');
} catch (Exception $e) {
    // 忽略表创建错误
}

zenData('workflowfield')->gen(0);
zenData('workflow')->gen(0);

// 设置必要的配置
if(!isset($config->workflowfield)) $config->workflowfield = new stdclass();
if(!isset($config->workflowfield->numberTypes)) $config->workflowfield->numberTypes = array('int', 'decimal', 'float');

su('admin');

$convertTest = new convertTest();

// 测试步骤1: 开源版直接返回relations
$originalEdition = $config->edition;
$config->edition = 'open';
$relations1 = array('zentaoObject' => array('Story' => 'story'), 'zentaoFieldStory' => array());
r($convertTest->createWorkflowFieldTest($relations1, array(), array(), array(), array())) && p('zentaoObject:Story') && e('story');

// 测试步骤2: 企业版无自定义字段的情况
$config->edition = 'biz';
$relations2 = array('zentaoObject' => array('Story' => 'story'));
r($convertTest->createWorkflowFieldTest($relations2, array(), array(), array(), array())) && p('zentaoObject:Story') && e('story');

// 测试步骤3: 正常创建工作流字段
$relations3 = array(
    'zentaoObject' => array('Story' => 'story'),
    'zentaoFieldStory' => array('customfield_10001' => 'add_field')
);
$fields3 = array(
    'customfield_10001' => (object)array(
        'cfname' => 'Test Custom Field',
        'customfieldtypekey' => 'com.atlassian.jira.plugin.system.customfieldtypes:textfield'
    )
);
$result3 = $convertTest->createWorkflowFieldTest($relations3, $fields3, array(), array(), array());
r(isset($result3['zentaoFieldStory']['customfield_10001']) && strpos($result3['zentaoFieldStory']['customfield_10001'], 'jirafield') === 0 ? 1 : 0) && p() && e('1');

// 测试步骤4: 字段已存在时重用
$tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID, extra) VALUES ('jcustomfield', 'customfield_10002', 'zworkflowfield', 'existingfield001', 'story')");
$relations4 = array(
    'zentaoObject' => array('Story' => 'story'),
    'zentaoFieldStory' => array('customfield_10002' => 'add_field')
);
$fields4 = array(
    'customfield_10002' => (object)array(
        'cfname' => 'Existing Field',
        'customfieldtypekey' => 'com.atlassian.jira.plugin.system.customfieldtypes:textfield'
    )
);
r($convertTest->createWorkflowFieldTest($relations4, $fields4, array(), array(), array())) && p('zentaoFieldStory:customfield_10002') && e('existingfield001');

// 测试步骤5: 包含选项的自定义字段
$relations5 = array(
    'zentaoObject' => array('Bug' => 'bug'),
    'zentaoFieldBug' => array('customfield_10003' => 'add_field')
);
$fields5 = array(
    'customfield_10003' => (object)array(
        'cfname' => 'Select Field',
        'customfieldtypekey' => 'com.atlassian.jira.plugin.system.customfieldtypes:select'
    )
);
$fieldOptions5 = array(
    '1001' => (object)array('customfield' => 'customfield_10003', 'customvalue' => 'Option 1'),
    '1002' => (object)array('customfield' => 'customfield_10003', 'customvalue' => 'Option 2')
);
$result5 = $convertTest->createWorkflowFieldTest($relations5, $fields5, $fieldOptions5, array(), array());
r(isset($result5['zentaoFieldBug']['customfield_10003']) && strpos($result5['zentaoFieldBug']['customfield_10003'], 'jirafield') === 0 ? 1 : 0) && p() && e('1');

$config->edition = $originalEdition;