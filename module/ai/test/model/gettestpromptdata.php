#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getTestPromptData();
timeout=0
cid=0

- 获取禅道智能体中需求的示例数据。 @1
- 获取禅道智能体中项目的示例数据。 @1
- 获取禅道智能体中bug的示例数据。 @1
- 获取禅道智能体中发布的示例数据。 @1
- 验证异常数据。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');
$aiTest = new aiTest();

$storyPrompt = new stdClass();
$storyPrompt->id = 1;
$storyPrompt->module = 'story';
$storyPrompt->source = ',story.title,story.spec,';

$projectPrompt = new stdClass();
$projectPrompt->id = 2;
$projectPrompt->module = 'project';
$projectPrompt->source = ',project.name,project.type,project.desc,project.begin,project.end,';

$bugPrompt = new stdClass();
$bugPrompt->id = 3;
$bugPrompt->module = 'bug';
$bugPrompt->source = ',bug.title,bug.steps,bug.severity,';

$releasePrompt = new stdClass();
$releasePrompt->id = 4;
$releasePrompt->module = 'release';
$releasePrompt->source = ',release.product,release.name,release.desc,';

$unknownPrompt = new stdClass();
$unknownPrompt->id = 5;
$unknownPrompt->module = 'product';
$unknownPrompt->source = '';

r($aiTest->getTestPromptDataTest($storyPrompt))   && p() && e('1'); // 获取禅道智能体中需求的示例数据。
r($aiTest->getTestPromptDataTest($projectPrompt)) && p() && e('1'); // 获取禅道智能体中项目的示例数据。
r($aiTest->getTestPromptDataTest($bugPrompt))     && p() && e('1'); // 获取禅道智能体中bug的示例数据。
r($aiTest->getTestPromptDataTest($releasePrompt)) && p() && e('1'); // 获取禅道智能体中发布的示例数据。
r($aiTest->getTestPromptDataTest($unknownPrompt)) && p() && e('0'); // 验证异常数据。
