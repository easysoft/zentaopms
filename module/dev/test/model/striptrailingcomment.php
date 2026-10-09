#!/usr/bin/env php
<?php

/**

title=测试 devModel::stripTrailingComment();
timeout=0
cid=0

- 步骤1：去掉末尾分号 @select 1
- 步骤2：去掉末尾行注释 @select 1
- 步骤3：去掉末尾井号注释 @select 1
- 步骤4：去掉末尾块注释 @select 1
- 步骤5：没有注释时保持不变 @select 1 from zt_user
- 步骤6：保留语句中间的注释 @yes

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang, $config;
$lang->dev = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');

su('admin');

zenData('user')->gen(1);

$dev = new devModelTest();

r($dev->stripTrailingCommentTest('select 1;'))                     && p() && e('select 1');                     // 步骤1：去掉末尾分号
r($dev->stripTrailingCommentTest('select 1 -- comment'))           && p() && e('select 1');                     // 步骤2：去掉末尾行注释
r($dev->stripTrailingCommentTest('select 1 # comment'))            && p() && e('select 1');                     // 步骤3：去掉末尾井号注释
r($dev->stripTrailingCommentTest('select 1 /* comment */'))        && p() && e('select 1');                     // 步骤4：去掉末尾块注释
r($dev->stripTrailingCommentTest('select 1 from zt_user'))         && p() && e('select 1 from zt_user');        // 步骤5：没有注释时保持不变
r($dev->stripTrailingCommentTest('select 1 /* keep */ from zt_user') === 'select 1 /* keep */ from zt_user' ? 'yes' : 'fail') && p() && e('yes'); // 步骤6：保留语句中间的注释
