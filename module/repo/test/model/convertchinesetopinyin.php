#!/usr/bin/env php
<?php

/**

title=测试 commonModel::convertChineseToPinyin();
timeout=0
cid=0

- 测试步骤1：纯中文转换为拼音 >> 返回连字符拼音
- 测试步骤2：纯英文保持不变 >> 返回原字符串
- 测试步骤3：中文+数字转换 >> 返回拼音并保留数字
- 测试步骤4：中文+特殊字符转换 >> 去除特殊字符
- 测试步骤5：中英混合转换 >> 中文转拼音英文保留

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

r($commonTest->convertChineseToPinyinTest('禅道')) && p() && e('chan-dao'); // 测试步骤1：纯中文转换为拼音
r($commonTest->convertChineseToPinyinTest('zentao')) && p() && e('zentao'); // 测试步骤2：纯英文保持不变
r($commonTest->convertChineseToPinyinTest('禅道123')) && p() && e('chan-dao-123'); // 测试步骤3：中文+数字转换
r($commonTest->convertChineseToPinyinTest('禅道@#')) && p() && e('chan-dao'); // 测试步骤4：中文+特殊字符转换
r($commonTest->convertChineseToPinyinTest('repo仓库')) && p() && e('repo-cang-ku'); // 测试步骤5：中英混合转换
