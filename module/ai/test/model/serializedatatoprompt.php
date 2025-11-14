#!/usr/bin/env php
<?php

/**

title=测试 aiModel::serializeDataToPrompt();
timeout=0
cid=15063

- 执行aiTest模块的serializeDataToPromptTest方法，参数是'task', '', array  @0
- 执行aiTest模块的serializeDataToPromptTest方法，参数是'bug', array  @0
- 执行aiTest模块的serializeDataToPromptTest方法，参数是'project', array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');

$aiTest = new aiTest();

r($aiTest->serializeDataToPromptTest('task', '', array())) && p() && e('0');
r($aiTest->serializeDataToPromptTest('bug', array(array('bug', 'title')), array())) && p() && e('0');
r($aiTest->serializeDataToPromptTest('project', array(), array())) && p() && e('0');