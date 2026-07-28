#!/usr/bin/env php
<?php

/**

title=测试 commonModel::hasChinese();
timeout=0
cid=0

- 测试步骤1：纯中文字符串 >> 返回 true
- 测试步骤2：纯英文字符串 >> 返回 false
- 测试步骤3：中英文混合字符串 >> 返回 true
- 测试步骤4：数字和符号字符串 >> 返回 false
- 测试步骤5：空字符串 >> 返回 false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

r($commonTest->hasChineseTest('禅道')) && p() && e('1'); // 测试步骤1：纯中文字符串
r($commonTest->hasChineseTest('zentao')) && p() && e('0'); // 测试步骤2：纯英文字符串
r($commonTest->hasChineseTest('repo仓库123')) && p() && e('1'); // 测试步骤3：中英文混合字符串
r($commonTest->hasChineseTest('123-_=+!')) && p() && e('0'); // 测试步骤4：数字和符号字符串
r($commonTest->hasChineseTest('')) && p() && e('0'); // 测试步骤5：空字符串
