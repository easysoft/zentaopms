#!/usr/bin/env php
<?php

/**

title=测试 aiModel::buildContextDescription();
timeout=0
cid=1

- 步骤1：页面级对象包含顶层标签 @1
- 步骤2：页面级对象合并 begin/end 为日期行 @1
- 步骤3：关联对象包含编号前缀 @1
- 步骤4：estimate 字段追加 h @1
- 步骤5：空上下文返回空字符串 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$context = array
(
    'project' => array('id' => 2, 'name' => '项目A', 'desc' => '项目描述', 'begin' => '2026-06-01', 'end' => '2026-06-30'),
    'story'   => array('id' => 8, 'title' => '需求A', 'spec' => '需求描述', 'estimate' => '3', 'status' => 'active'),
);

$desc = $aiTest->buildContextDescriptionTest($context);

r(strpos($desc, '项目：') !== false) && p() && e('1');
r(strpos($desc, '日期：2026-06-01 ~ 2026-06-30') !== false) && p() && e('1');
r(strpos($desc, '需求：#8') !== false) && p() && e('1');
r(strpos($desc, '预计工时：3h') !== false) && p() && e('1');
r($aiTest->buildContextDescriptionTest(array()) === '') && p() && e('1');
