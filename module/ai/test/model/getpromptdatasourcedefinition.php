#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getPromptDataSourceDefinition();
timeout=0
cid=0

- 获取story模块的数据源定义分组数量 @1
- story模块的公共名称 @需求
- story模块的title字段名称 @标题
- project模块的begin字段名称 @开始
- release模块的product字段名称 @所属产品

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest      = new aiModelTest();
$storyResult = $aiTest->getPromptDataSourceDefinitionTest('story');
$projectResult = $aiTest->getPromptDataSourceDefinitionTest('project');
$releaseResult = $aiTest->getPromptDataSourceDefinitionTest('release');

r(count($storyResult))                      && p() && e('1');
r($storyResult['story']['common'])         && p() && e('需求');
r($storyResult['story']['title'])          && p() && e('标题');
r($projectResult['project']['begin'])      && p() && e('开始');
r($releaseResult['release']['product'])    && p() && e('所属产品');
