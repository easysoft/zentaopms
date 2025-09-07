#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getVersionGroup();
timeout=0
cid=0

- 执行convertTest模块的getVersionGroupTest方法  @0
- 执行$result @1
- 执行convertTest模块的getVersionGroupTest方法  @0
- 执行$result2 @2
- 执行$result3 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

global $app;

su('admin');

$convertTest = new convertTest();

// 步骤1：无nodeassociation.xml文件时返回空数组
$tmpRoot = $app->getTmpRoot() . 'jirafile/';
if(is_dir($tmpRoot)) {
    $filePath = $tmpRoot . 'nodeassociation.xml';
    if(file_exists($filePath)) @unlink($filePath);
    @rmdir($tmpRoot);
}
r($convertTest->getVersionGroupTest()) && p() && e('0');

// 步骤2：存在有效nodeassociation.xml文件时返回版本分组数据
if(!is_dir($tmpRoot)) mkdir($tmpRoot, 0755, true);
$xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<entity-engine-xml>
<NodeAssociation id="1" sourceNodeId="100" sinkNodeId="10" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueFixVersion"/>
<NodeAssociation id="2" sourceNodeId="101" sinkNodeId="10" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueFixVersion"/>
</entity-engine-xml>';
file_put_contents($tmpRoot . 'nodeassociation.xml', $xmlContent);
$result = $convertTest->getVersionGroupTest();
r(count($result)) && p() && e('1');

// 步骤3：nodeassociation.xml文件内容为空时返回空数组
file_put_contents($tmpRoot . 'nodeassociation.xml', '<?xml version="1.0" encoding="UTF-8"?><entity-engine-xml></entity-engine-xml>');
r($convertTest->getVersionGroupTest()) && p() && e('0');

// 步骤4：包含多个版本组的XML文件
$xmlContent3 = '<?xml version="1.0" encoding="UTF-8"?>
<entity-engine-xml>
<NodeAssociation id="1" sourceNodeId="100" sinkNodeId="10" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueFixVersion"/>
<NodeAssociation id="2" sourceNodeId="101" sinkNodeId="20" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueAffectsVersion"/>
</entity-engine-xml>';
file_put_contents($tmpRoot . 'nodeassociation.xml', $xmlContent3);
$result2 = $convertTest->getVersionGroupTest();
r(count($result2)) && p() && e('2');

// 步骤5：测试不同版本ID的情况
$xmlContent4 = '<?xml version="1.0" encoding="UTF-8"?>
<entity-engine-xml>
<NodeAssociation id="1" sourceNodeId="100" sinkNodeId="30" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueFixVersion"/>
<NodeAssociation id="2" sourceNodeId="101" sinkNodeId="40" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueFixVersion"/>
<NodeAssociation id="3" sourceNodeId="102" sinkNodeId="50" sourceNodeEntity="Issue" sinkNodeEntity="Version" associationType="IssueAffectsVersion"/>
</entity-engine-xml>';
file_put_contents($tmpRoot . 'nodeassociation.xml', $xmlContent4);
$result3 = $convertTest->getVersionGroupTest();
r(count($result3)) && p() && e('3');