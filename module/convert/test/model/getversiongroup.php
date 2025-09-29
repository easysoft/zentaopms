#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getVersionGroup();
timeout=0
cid=0

- 步骤1：无文件时返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

global $app;

$convertTest = new convertTest();

// 清理临时文件目录，准备测试环境
$tmpRoot = $app->getTmpRoot() . 'jirafile/';
if(is_dir($tmpRoot)) {
    $filePath = $tmpRoot . 'nodeassociation.xml';
    if(file_exists($filePath)) @unlink($filePath);
    @rmdir($tmpRoot);
}

// 测试步骤（必须至少5个，每个写在一行）
r($convertTest->getVersionGroupTest()) && p() && e('0'); // 步骤1：无文件时返回空数组
if(!is_dir($tmpRoot)) mkdir($tmpRoot, 0755, true); $xmlContent = '<?xml version="1.0" encoding="UTF-8"?><entity-engine-xml><NodeAssociation id="1" sourceNodeId="100" sinkNodeId="10" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueFixVersion"/><NodeAssociation id="2" sourceNodeId="101" sinkNodeId="10" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueFixVersion"/></entity-engine-xml>'; file_put_contents($tmpRoot . 'nodeassociation.xml', $xmlContent); r(count($convertTest->getVersionGroupTest())) && p() && e('1'); // 步骤2：有效XML文件返回版本组
file_put_contents($tmpRoot . 'nodeassociation.xml', '<?xml version="1.0" encoding="UTF-8"?><entity-engine-xml></entity-engine-xml>'); r(count($convertTest->getVersionGroupTest())) && p() && e('0'); // 步骤3：空XML文件返回空数组
$xmlContent2 = '<?xml version="1.0" encoding="UTF-8"?><entity-engine-xml><NodeAssociation id="1" sourceNodeId="100" sinkNodeId="10" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueFixVersion"/><NodeAssociation id="2" sourceNodeId="101" sinkNodeId="20" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueAffectsVersion"/></entity-engine-xml>'; file_put_contents($tmpRoot . 'nodeassociation.xml', $xmlContent2); r(count($convertTest->getVersionGroupTest())) && p() && e('2'); // 步骤4：多个版本组的XML文件
$xmlContent3 = '<?xml version="1.0" encoding="UTF-8"?><entity-engine-xml><NodeAssociation id="1" sourceNodeId="100" sinkNodeId="30" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueFixVersion"/><NodeAssociation id="2" sourceNodeId="101" sinkNodeId="40" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueFixVersion"/><NodeAssociation id="3" sourceNodeId="102" sinkNodeId="50" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueAffectsVersion"/></entity-engine-xml>'; file_put_contents($tmpRoot . 'nodeassociation.xml', $xmlContent3); r(count($convertTest->getVersionGroupTest())) && p() && e('3'); // 步骤5：不同版本ID的情况