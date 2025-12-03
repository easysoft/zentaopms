#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::judgeSuhosinSetting();
timeout=0
cid=15685

- 步骤1：正常小数值输入 @0
- 步骤2：边界值测试 @0
- 步骤3：超过默认限制 @1
- 步骤4：零值边界测试 @0
- 步骤5：大幅超过限制 @1

*/

// 加载commonModel类定义
require_once dirname(__FILE__, 3) . '/model.php';

$res1 = commonModel::judgeSuhosinSetting(100);
$res2 = commonModel::judgeSuhosinSetting(1000);
$res3 = commonModel::judgeSuhosinSetting(100000);
$res4 = commonModel::judgeSuhosinSetting(0);
$res5 = commonModel::judgeSuhosinSetting(50000);

r($res1) && p() && e('0'); // 步骤1：正常小数值输入
r($res2) && p() && e('0'); // 步骤2：边界值测试
r($res3) && p() && e('1'); // 步骤3：超过默认限制
r($res4) && p() && e('0'); // 步骤4：零值边界测试
r($res5) && p() && e('1'); // 步骤5：大幅超过限制