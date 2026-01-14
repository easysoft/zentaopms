#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getVersionGroup();
timeout=0
cid=15787

- 执行convertTest模块的getVersionGroupTest方法  @0
- 执行convertTest模块的getVersionGroupTest方法  @2
- 执行$result[1001]) ? count($result[1001]) : 0 @2
- 执行$result[1002]) ? count($result[1002]) : 0 @1
- 执行convertTest模块的getVersionGroupTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

// 准备测试环境:获取临时目录路径
global $app;
$tmpRoot = $app->getTmpRoot() . 'jirafile/';
if(!is_dir($tmpRoot)) mkdir($tmpRoot, 0777, true);

$xmlFile = $tmpRoot . 'nodeassociation.xml';

// 测试步骤1:文件不存在的情况
if(file_exists($xmlFile)) unlink($xmlFile);
r($convertTest->getVersionGroupTest()) && p() && e('0');

// 测试步骤2:创建有效的XML文件(包含Version关联),验证返回的数组有2个版本分组
$validXML = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<entity-engine-xml>
  <NodeAssociation id="1" sourceNodeId="10001" sourceNodeEntity="Issue" sinkNodeId="1001" sinkNodeEntity="Version" associationType="IssueFixVersion"/>
  <NodeAssociation id="2" sourceNodeId="10002" sourceNodeEntity="Issue" sinkNodeId="1001" sinkNodeEntity="Version" associationType="IssueFixVersion"/>
  <NodeAssociation id="3" sourceNodeId="10003" sourceNodeEntity="Issue" sinkNodeId="1002" sinkNodeEntity="Version" associationType="IssueAffectsVersion"/>
</entity-engine-xml>
XML;
file_put_contents($xmlFile, $validXML);
r(count($convertTest->getVersionGroupTest())) && p() && e('2');

// 测试步骤3:验证版本ID 1001有2个关联
$result = $convertTest->getVersionGroupTest();
r(isset($result[1001]) ? count($result[1001]) : 0) && p() && e('2');

// 测试步骤4:验证版本ID 1002有1个关联
r(isset($result[1002]) ? count($result[1002]) : 0) && p() && e('1');

// 测试步骤5:创建不包含Version关联的XML文件
$noVersionXML = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<entity-engine-xml>
  <NodeAssociation id="1" sourceNodeId="10001" sourceNodeEntity="Issue" sinkNodeId="2001" sinkNodeEntity="Component" associationType="IssueComponent"/>
</entity-engine-xml>
XML;
file_put_contents($xmlFile, $noVersionXML);
r($convertTest->getVersionGroupTest()) && p() && e('0');

// 清理测试文件
if(file_exists($xmlFile)) unlink($xmlFile);