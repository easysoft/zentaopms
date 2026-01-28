#!/usr/bin/env php
<?php

/**

title=测试 convertModel::splitFile();
timeout=0
cid=15796

- 执行convertTest模块的splitFileTest方法  @success
- 执行convertTest模块的splitFileTest方法  @no_files
- 执行convertTest模块的splitFileTest方法  @no_source_file
- 执行convertTest模块的splitFileTest方法  @success
- 执行$hasValidXMLHeader && $hasValidXMLFooter @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

// 准备测试环境
global $app;
$jiraPath = $app->getTmpRoot() . 'jirafile/';
if(!is_dir($jiraPath)) mkdir($jiraPath, 0755, true);

// 清理之前的测试文件
$testFiles = array('action.xml', 'project.xml', 'status.xml', 'resolution.xml', 'user.xml', 'issue.xml', 'changegroup.xml', 'changeitem.xml', 'issuelink.xml', 'issuelinktype.xml', 'fileattachment.xml', 'version.xml', 'issuetype.xml', 'nodeassociation.xml');
foreach($testFiles as $file)
{
    if(file_exists($jiraPath . $file)) @unlink($jiraPath . $file);
}

// 创建测试用的entities.xml文件
$testXMLContent = '<?xml version="1.0" encoding="UTF-8"?>
<entity-engine-xml>
<Action id="1" name="Create Issue" description="Creates a new issue" />
<Action id="2" name="Close Issue" description="Closes an issue" />
<Project id="10000" pkey="TEST" name="Test Project" description="Test project description" />
<Project id="10001" pkey="DEMO" name="Demo Project" description="Demo project description" />
<Status id="1" name="Open" description="Open status" />
<Status id="2" name="Closed" description="Closed status" />
<Resolution id="1" name="Fixed" description="Fixed resolution" />
<User id="admin" account="admin" email="admin@test.com" />
<Issue id="100" summary="Test issue 1" description="Test issue description" issuetype="1" project="10000" />
<Issue id="101" summary="Test issue 2" description="Another test issue" issuetype="2" project="10001" />
<ChangeGroup id="1" issue="100" author="admin" created="2023-01-01" />
<ChangeItem id="1" group="1" field="status" oldstring="Open" newstring="Closed" />
<IssueLink id="1" source="100" destination="101" linktype="1" />
<IssueLinkType id="1" linkname="relates to" />
<FileAttachment id="1" issue="100" filename="test.txt" />
<Version id="1" project="10000" name="v1.0" />
<IssueType id="1" name="Bug" description="Bug issue type" />
<IssueType id="2" name="Task" description="Task issue type" />
<NodeAssociation id="1" sourceNodeId="100" sourceNodeEntity="Issue" sinkNodeId="1" sinkNodeEntity="Version" associationType="IssueFixVersion" />
</entity-engine-xml>';

file_put_contents($jiraPath . 'entities.xml', $testXMLContent);

// 测试步骤1：测试有效的entities.xml文件分割
r($convertTest->splitFileTest()) && p() && e('success');

// 测试步骤2：测试空的entities.xml文件处理
foreach($testFiles as $file)
{
    if(file_exists($jiraPath . $file)) @unlink($jiraPath . $file);
}
file_put_contents($jiraPath . 'entities.xml', '<?xml version="1.0" encoding="UTF-8"?><entity-engine-xml></entity-engine-xml>');
r($convertTest->splitFileTest()) && p() && e('no_files');

// 测试步骤3：测试不存在entities.xml文件的处理
if(file_exists($jiraPath . 'entities.xml')) @unlink($jiraPath . 'entities.xml');
r($convertTest->splitFileTest()) && p() && e('no_source_file');

// 测试步骤4：测试包含特殊字符的XML内容处理
foreach($testFiles as $file)
{
    if(file_exists($jiraPath . $file)) @unlink($jiraPath . $file);
}
$xmlWithSpecialChars = '<?xml version="1.0" encoding="UTF-8"?>
<entity-engine-xml>
<Action id="1" name="Test Action" description="Action with special chars &amp; &lt; &gt;" />
<Project id="1" name="测试项目" description="Project with Chinese chars" />
</entity-engine-xml>';
file_put_contents($jiraPath . 'entities.xml', $xmlWithSpecialChars);
r($convertTest->splitFileTest()) && p() && e('success');

// 测试步骤5：检查分割后的文件完整性
$actionFile = $jiraPath . 'action.xml';
$hasValidXMLHeader = false;
$hasValidXMLFooter = false;
if(file_exists($actionFile))
{
    $content = file_get_contents($actionFile);
    $hasValidXMLHeader = strpos($content, "<?xml version='1.0' encoding='UTF-8'?>") !== false;
    $hasValidXMLFooter = strpos($content, '</entity-engine-xml>') !== false;
}
r($hasValidXMLHeader && $hasValidXMLFooter) && p() && e('1');