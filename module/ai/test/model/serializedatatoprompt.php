#!/usr/bin/env php
<?php

/**

title=测试 aiModel::serializeDataToPrompt();
timeout=0
cid=0

- 执行aiTest模块的serializeDataToPromptTest方法，参数是'task', array  @{"任务":{"任务名称":"测试任务"}}
- 执行aiTest模块的serializeDataToPromptTest方法，参数是'task', '', array  @
- 执行aiTest模块的serializeDataToPromptTest方法，参数是'bug', array  @~~
- 执行aiTest模块的serializeDataToPromptTest方法，参数是'story', 'story.title', array  @~~
- 执行aiTest模块的serializeDataToPromptTest方法，参数是'project', array  @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');

$aiTest = new aiTest();

r($aiTest->serializeDataToPromptTest('task', array(array('task', 'name')), array('task' => array('name' => '测试任务')))) && p() && e('{"任务":{"任务名称":"测试任务"}}');
r($aiTest->serializeDataToPromptTest('task', '', array())) && p() && e('');
r($aiTest->serializeDataToPromptTest('bug', array(array('bug', 'title')), (object)array('bug' => array('title' => '测试Bug')))) && p() && e('~~');
r($aiTest->serializeDataToPromptTest('story', 'story.title', array('story' => array('title' => '用户故事')))) && p() && e('~~');
r($aiTest->serializeDataToPromptTest('project', array(), array())) && p() && e('');