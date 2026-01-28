#!/usr/bin/env php
<?php

/**

title=测试 commonModel::isTutorialMode();
timeout=0
cid=15684

- 执行commonTest模块的isTutorialModeTest方法  @0
- 执行commonTest模块的isTutorialModeTest方法  @0
- 执行commonTest模块的isTutorialModeTest方法  @1
- 执行commonTest模块的isTutorialModeTest方法  @0
- 执行commonTest模块的isTutorialModeTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

// 备份原始session状态
$originalTutorialMode = isset($_SESSION['tutorialMode']) ? $_SESSION['tutorialMode'] : null;

// 测试步骤1：未设置tutorialMode（默认状态）
unset($_SESSION['tutorialMode']);
r($commonTest->isTutorialModeTest()) && p() && e('0');

// 测试步骤2：设置tutorialMode为空字符串
$_SESSION['tutorialMode'] = '';
r($commonTest->isTutorialModeTest()) && p() && e('0');

// 测试步骤3：设置tutorialMode为非空字符串
$_SESSION['tutorialMode'] = 'guide';
r($commonTest->isTutorialModeTest()) && p() && e('1');

// 测试步骤4：设置tutorialMode为0
$_SESSION['tutorialMode'] = 0;
r($commonTest->isTutorialModeTest()) && p() && e('0');

// 测试步骤5：设置tutorialMode为true
$_SESSION['tutorialMode'] = true;
r($commonTest->isTutorialModeTest()) && p() && e('1');

// 恢复原始session状态
if($originalTutorialMode !== null)
{
    $_SESSION['tutorialMode'] = $originalTutorialMode;
}
else
{
    unset($_SESSION['tutorialMode']);
}