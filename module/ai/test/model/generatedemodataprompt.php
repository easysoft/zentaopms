#!/usr/bin/env php
<?php

/**

title=测试 aiModel::generateDemoDataPrompt();
timeout=0
cid=0

- 执行aiTest模块的generateDemoDataPromptTest方法，参数是'story', 'story.title'  @{"需求":{"需求标题":"开发一个在线学习平台"}}
- 执行aiTest模块的generateDemoDataPromptTest方法，参数是'story', 'story.spec'  @{"需求":{"需求描述":"我们需要开发一个在线学习平台，能够提供课程管理、学生管理、教师管理等功能。"}}
- 执行aiTest模块的generateDemoDataPromptTest方法，参数是'story', 'story.verify'  @{"需求":{"验收标准":"1. 所有功能均能够正常运行，没有明显的错误和异常。2. 界面美观、易用性好。3. 平台能够满足用户需求，具有较高的用户满意度。4. 代码质量好，结构清晰、易于维护。"}}
- 执行aiTest模块的generateDemoDataPromptTest方法，参数是'story', 'story.category'  @{"需求":{"需求类型":"feature"}}
- 执行aiTest模块的generateDemoDataPromptTest方法，参数是'execution', 'execution.name'  @{"执行":{"执行名称":"在线学习平台软件开发"}}

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');

$aiTest = new aiTest();

r($aiTest->generateDemoDataPromptTest('story', 'story.title')) && p() && e('{"需求":{"需求标题":"开发一个在线学习平台"}}');
r($aiTest->generateDemoDataPromptTest('story', 'story.spec')) && p() && e('{"需求":{"需求描述":"我们需要开发一个在线学习平台，能够提供课程管理、学生管理、教师管理等功能。"}}');
r($aiTest->generateDemoDataPromptTest('story', 'story.verify')) && p() && e('{"需求":{"验收标准":"1. 所有功能均能够正常运行，没有明显的错误和异常。2. 界面美观、易用性好。3. 平台能够满足用户需求，具有较高的用户满意度。4. 代码质量好，结构清晰、易于维护。"}}');
r($aiTest->generateDemoDataPromptTest('story', 'story.category')) && p() && e('{"需求":{"需求类型":"feature"}}');
r($aiTest->generateDemoDataPromptTest('execution', 'execution.name')) && p() && e('{"执行":{"执行名称":"在线学习平台软件开发"}}');