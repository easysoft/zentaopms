#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflowField();
timeout=0
cid=0

- 执行convertTest模块的createWorkflowFieldTest方法，参数是$relations, array 属性test @data
- 执行$result['zentaoFieldissue']['customfield_10002']) && strpos($result['zentaoFieldissue']['customfield_10002'], 'jirafield') === 0 @rue
- 执行$result属性zentaoFieldissue @testfield1
属性customfield_10001 @testfield1
- 执行$result['zentaoFieldissue'] @rue
- 执行$result['zentaoFieldissue']['customfield_10004']) && strpos($result['zentaoFieldissue']['customfield_10004'], 'jirafield') === 0 @rue

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 创建临时表
global $tester;
$sql = <<<EOT
CREATE TABLE IF NOT EXISTS `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL,
  `AID` char(100) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` char(100) NOT NULL,
  `extra` char(100) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation` (`AType`,`BType`,`AID`,`BID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;

try {
    $tester->dbh->exec($sql);
    $tester->dbh->exec('TRUNCATE TABLE jiratmprelation');
} catch (Exception $e) {
}

// 3. 定义常量和配置
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

global $config;
if(!isset($config->workflowfield)) $config->workflowfield = new stdClass();
if(!isset($config->workflowfield->numberTypes)) $config->workflowfield->numberTypes = array('int', 'float', 'double');

$tester->dbh->exec("INSERT INTO jiratmprelation (AType, AID, BType, BID, extra) VALUES ('jcustomfield', 'customfield_10001', 'zworkflowfield', 'testfield1', 'testmodule')");

if(!isset($config->convert)) $config->convert = new stdClass();
if(!isset($config->convert->jiraFieldControl)) {
    $config->convert->jiraFieldControl = array(
        'com.atlassian.jira.plugin.system.customfieldtypes:textfield' => array(
            'control' => 'input',
            'type' => 'varchar',
            'length' => '255'
        )
    );
}

// 4. 用户登录
su('admin');

// 5. 创建测试实例
$convertTest = new convertTest();
$convertTest->objectTao->workflowfield = new MockWorkflowField();

// 6. 测试步骤
$originalEdition = $config->edition;

// 测试1: 开源版本直接返回原relations
$config->edition = 'open';
$relations = array('test' => 'data');
r($convertTest->createWorkflowFieldTest($relations, array(), array(), array(), array())) && p('test') && e('data');

// 恢复版本设置
$config->edition = 'biz';

// 测试2: 正常创建工作流自定义字段
$relations = array(
    'zentaoObject' => array('issue' => 'testmodule'),
    'zentaoFieldissue' => array('customfield_10002' => 'add_field')
);
$fields = array(
    'customfield_10002' => (object)array(
        'cfname' => '测试字段',
        'customfieldtypekey' => 'com.atlassian.jira.plugin.system.customfieldtypes:textfield'
    )
);
$result = $convertTest->createWorkflowFieldTest($relations, $fields, array(), array(), array());
r(isset($result['zentaoFieldissue']['customfield_10002']) && strpos($result['zentaoFieldissue']['customfield_10002'], 'jirafield') === 0) && p() && e(true);

// 测试3: 已存在字段关系的情况
$relations = array(
    'zentaoObject' => array('issue' => 'testmodule'),
    'zentaoFieldissue' => array('customfield_10001' => 'add_field')
);
$result = $convertTest->createWorkflowFieldTest($relations, $fields, array(), array(), array());
r($result) && p('zentaoFieldissue,customfield_10001') && e('testfield1');

// 测试4: 空字段列表的情况
$relations = array(
    'zentaoObject' => array('issue' => 'testmodule'),
    'zentaoFieldissue' => array()
);
$result = $convertTest->createWorkflowFieldTest($relations, array(), array(), array(), array());
r(empty($result['zentaoFieldissue'])) && p() && e(true);

// 测试5: 无效控件类型时使用默认类型
$relations = array(
    'zentaoObject' => array('issue' => 'testmodule'),
    'zentaoFieldissue' => array('customfield_10004' => 'add_field')
);
$fields = array(
    'customfield_10004' => (object)array(
        'cfname' => '无效控件字段',
        'customfieldtypekey' => 'unknown.customfield.type'
    )
);
$result = $convertTest->createWorkflowFieldTest($relations, $fields, array(), array(), array());
r(isset($result['zentaoFieldissue']['customfield_10004']) && strpos($result['zentaoFieldissue']['customfield_10004'], 'jirafield') === 0) && p() && e(true);

$config->edition = $originalEdition;