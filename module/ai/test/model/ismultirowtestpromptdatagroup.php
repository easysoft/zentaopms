#!/usr/bin/env php
<?php

/**

title=测试 aiModel::isMultiRowTestPromptDataGroup();
timeout=0
cid=0

- stories 是多行分组 @1
- steps 是多行分组 @1
- story 不是多行分组 @0
- task 不是多行分组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

r($aiTest->isMultiRowTestPromptDataGroupTest('stories')) && p() && e('1');
r($aiTest->isMultiRowTestPromptDataGroupTest('steps'))   && p() && e('1');
r($aiTest->isMultiRowTestPromptDataGroupTest('story'))   && p() && e('0');
r($aiTest->isMultiRowTestPromptDataGroupTest('task'))    && p() && e('0');
