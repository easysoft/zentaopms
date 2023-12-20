#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 upgradeTao->getNoMergedSprints();
cid=1

- 检查查询到的冲刺是否是未归并的
 - 第101条的project属性 @0
 - 第101条的type属性 @sprint
- 测试已归并的冲刺是否没有被查询出来第111条的project属性 @Error: Cannot get index 111.
第111条的type属性 @Error: Cannot get index 111.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('execution')->gen(30);
zdTable('user')->gen(5);
su('admin');

global $tester;
$upgrade = $tester->loadModel('upgrade');
r($upgrade->getNoMergedSprints()) && p('101:project,type') && e('0,sprint');                     //检查查询到的冲刺是否是未归并的
r($upgrade->getNoMergedSprints()) && p('111:project,type') && e('Error: Cannot get index 111.'); //测试已归并的冲刺是否没有被查询出来
