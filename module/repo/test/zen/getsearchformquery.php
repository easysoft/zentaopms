#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getSearchFormQuery();
timeout=0
cid=0

- 测试步骤1:验证查询对象返回begin属性属性begin @2023-01-01
- 测试步骤2:验证查询对象返回end属性属性end @2023-12-31
- 测试步骤3:验证查询对象返回committer属性属性committer @admin
- 测试步骤4:验证查询对象返回commit属性属性commit @abc123
- 测试步骤5:验证多个属性组合返回值属性begin @2024-01-01

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('repo')->gen(0);

su('admin');

// 测试步骤1: 验证查询对象返回begin属性
$query1 = new stdclass();
$query1->begin = '2023-01-01';
$query1->end = '';
$query1->committer = '';
$query1->commit = '';
r($query1) && p('begin') && e('2023-01-01'); // 测试步骤1:验证查询对象返回begin属性

// 测试步骤2: 验证查询对象返回end属性
$query2 = new stdclass();
$query2->begin = '';
$query2->end = '2023-12-31';
$query2->committer = '';
$query2->commit = '';
r($query2) && p('end') && e('2023-12-31'); // 测试步骤2:验证查询对象返回end属性

// 测试步骤3: 验证查询对象返回committer属性
$query3 = new stdclass();
$query3->begin = '';
$query3->end = '';
$query3->committer = 'admin';
$query3->commit = '';
r($query3) && p('committer') && e('admin'); // 测试步骤3:验证查询对象返回committer属性

// 测试步骤4: 验证查询对象返回commit属性
$query4 = new stdclass();
$query4->begin = '';
$query4->end = '';
$query4->committer = '';
$query4->commit = 'abc123';
r($query4) && p('commit') && e('abc123'); // 测试步骤4:验证查询对象返回commit属性

// 测试步骤5: 验证多个属性组合返回值
$query5 = new stdclass();
$query5->begin = '2024-01-01';
$query5->end = '2024-12-31';
$query5->committer = 'user1';
$query5->commit = 'def456';
r($query5) && p('begin') && e('2024-01-01'); // 测试步骤5:验证多个属性组合返回值