#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 upgradeModel->changeTeamFields();
cid=19501

- 获取返回结果。 @1
- 检查 team 表 root 字段是否存在。 @1
- 检查 team 表 type 字段是否存在。 @1
- 检查 team 表 task 字段是否存在。 @0
- 检查 team 表 project 字段是否存在。 @0

**/

global $tester;
$upgradeModel = $tester->loadModel('upgrade');
r((int)$upgradeModel->changeTeamFields()) && p() && e('1'); //获取返回结果。

$desc   = $upgradeModel->dao->query("DESC " . TABLE_TEAM)->fetchAll();
$fields = array_column($desc, 'Field', 'Field');

r((int)isset($fields['root']))    && p() && e('1'); // 检查 team 表 root 字段是否存在。
r((int)isset($fields['type']))    && p() && e('1'); // 检查 team 表 type 字段是否存在。
r((int)isset($fields['task']))    && p() && e('0'); // 检查 team 表 task 字段是否存在。
r((int)isset($fields['project'])) && p() && e('0'); // 检查 team 表 project 字段是否存在。
