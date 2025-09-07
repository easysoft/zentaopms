#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printPreAndNext();
timeout=0
cid=0

- 步骤1：空参数测试，在onlybody模式下应返回false属性result @alse
- 步骤2：传入包含pre对象的数据测试属性output @~<nav class=\'container\'>~
- 步骤3：传入包含next对象的数据测试属性output @~<nav class=\'container\'>~
- 步骤4：传入包含pre和next对象的完整数据测试属性output @~<nav class=\'container\'>~
- 步骤5：使用自定义链接模板测试属性output @~<nav class=\'container\'>~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 4. 模拟onlybody环境
$_GET['onlybody'] = 'yes';

// 5. 强制要求：必须包含至少5个测试步骤
r($commonTest->printPreAndNextTest('', '')) && p('result') && e(false); // 步骤1：空参数测试，在onlybody模式下应返回false

// 切换到正常模式测试
unset($_GET['onlybody']);

// 准备测试数据
$preAndNextWithPre = new stdClass();
$preAndNextWithPre->pre = new stdClass();
$preAndNextWithPre->pre->id = 1;
$preAndNextWithPre->pre->title = '前一项测试';

$preAndNextWithNext = new stdClass();
$preAndNextWithNext->next = new stdClass();
$preAndNextWithNext->next->id = 2;
$preAndNextWithNext->next->title = '下一项测试';

$preAndNextFull = new stdClass();
$preAndNextFull->pre = new stdClass();
$preAndNextFull->pre->id = 1;
$preAndNextFull->pre->title = '前一项';
$preAndNextFull->next = new stdClass();
$preAndNextFull->next->id = 3;
$preAndNextFull->next->title = '下一项';

r($commonTest->printPreAndNextTest($preAndNextWithPre, '')) && p('output') && e('~<nav class=\'container\'>~'); // 步骤2：传入包含pre对象的数据测试
r($commonTest->printPreAndNextTest($preAndNextWithNext, '')) && p('output') && e('~<nav class=\'container\'>~'); // 步骤3：传入包含next对象的数据测试
r($commonTest->printPreAndNextTest($preAndNextFull, '')) && p('output') && e('~<nav class=\'container\'>~'); // 步骤4：传入包含pre和next对象的完整数据测试
r($commonTest->printPreAndNextTest($preAndNextFull, '/test/link/%d')) && p('output') && e('~<nav class=\'container\'>~'); // 步骤5：使用自定义链接模板测试