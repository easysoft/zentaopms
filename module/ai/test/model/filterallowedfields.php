#!/usr/bin/env php
<?php

/**

title=测试 aiModel::filterAllowedFields();
timeout=0
cid=1

- 步骤1：白名单为空时返回全部字段数量 @3
- 步骤2：只保留白名单内字段数量 @2
- 步骤3：保留字段 title 的 label @标题
- 步骤4：白名单无匹配时回退全部字段数量 @3
- 步骤5：字段顺序保持第 1 项键名 @title

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$fields = array
(
    'title' => array('label' => '标题'),
    'spec'  => array('label' => '描述'),
    'pri'   => array('label' => '优先级'),
);

$allFields      = $aiTest->filterAllowedFieldsTest($fields, array());
$filteredFields = $aiTest->filterAllowedFieldsTest($fields, array('title', 'spec'));
$fallbackFields = $aiTest->filterAllowedFieldsTest($fields, array('ghost'));

r(count($allFields)) && p() && e('3');
r(count($filteredFields)) && p() && e('2');
r($filteredFields) && p('title:label') && e('标题');
r(count($fallbackFields)) && p() && e('3');
r(array_keys($filteredFields)) && p('0') && e('title');
