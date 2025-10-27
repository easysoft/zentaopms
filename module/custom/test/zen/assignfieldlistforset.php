#!/usr/bin/env php
<?php

/**

title=测试 customZen::assignFieldListForSet();
timeout=0
cid=0

- 步骤1：正常情况 @zh-cn
- 步骤2：all语言模式 @all
- 步骤3：不同模块字段 @zh-cn
- 步骤4：英文语言 @en
- 步骤5：下划线转换 @zh-cn

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('lang')->gen(0);

su('admin');

$customTest = new customTest();

r($customTest->assignFieldListForSetTest('story', 'priList', 'zh-cn', 'zh-cn')) && p() && e('zh-cn'); // 步骤1：正常情况
r($customTest->assignFieldListForSetTest('story', 'priList', 'all', 'zh-cn')) && p() && e('all'); // 步骤2：all语言模式
r($customTest->assignFieldListForSetTest('bug', 'severityList', 'zh-cn', 'zh-cn')) && p() && e('zh-cn'); // 步骤3：不同模块字段
r($customTest->assignFieldListForSetTest('user', 'roleList', 'en', 'zh-cn')) && p() && e('en'); // 步骤4：英文语言
r($customTest->assignFieldListForSetTest('task', 'typeList', 'zh_cn', 'zh-cn')) && p() && e('zh-cn'); // 步骤5：下划线转换