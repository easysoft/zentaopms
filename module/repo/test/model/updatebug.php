#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->updateBug();
timeout=0
cid=0

- 更新bug标题 @New Bug Title
- 更新为数字标题 @Bug 12345
- 更新为中文标题 @中文标题测试
- 更新不存在bugID @No Such Bug
- 更新为特殊字符 @special chars

*/

su('admin');

zendata('bug')->loadYaml('bug_getcommitsbyobject', false, 2)->gen(3);

$repoTest = new repoModelTest();

r($repoTest->updateBugTest(1, 'New Bug Title')) && p() && e('New Bug Title');  // 更新bug标题
r($repoTest->updateBugTest(1, 'Bug 12345')) && p() && e('Bug 12345');       // 更新为数字标题
r($repoTest->updateBugTest(2, '中文标题测试')) && p() && e('中文标题测试');   // 更新为中文标题
r($repoTest->updateBugTest(999, 'No Such Bug')) && p() && e('No Such Bug');   // 更新不存在bugID
r($repoTest->updateBugTest(3, 'special chars')) && p() && e('special chars'); // 更新为特殊字符