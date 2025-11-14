#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->getGroupsFromSettings().
timeout=0
cid=17388

- 测试一个分组。 @product
- 测试两个个分组。 @product,project

- 测试存在非分组。 @product
- 测试没有分组。 @0
- 测试三个分组但存在重复。 @product,project

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivot    = new pivotTest();
$settings = array
(
    array('group1' => 'product'),
    array('group1' => 'product', 'group2' => 'project'),
    array('group1' => 'product', 'columns' => 'id'),
    array('columnTotal' => 'noShow'),
    array('group1' => 'product', 'group2' => 'project', 'group3' => 'product'),
);

r(implode(',', $pivot->getGroupsFromSettingsTest($settings[0]))) && p() && e('product');         // 测试一个分组。
r(implode(',', $pivot->getGroupsFromSettingsTest($settings[1]))) && p() && e('product,project'); // 测试两个个分组。
r(implode(',', $pivot->getGroupsFromSettingsTest($settings[2]))) && p() && e('product');         // 测试存在非分组。
r(count($pivot->getGroupsFromSettingsTest($settings[3]))) && p() && e('0');                    // 测试没有分组。
r(implode(',', $pivot->getGroupsFromSettingsTest($settings[4]))) && p() && e('product,project'); // 测试三个分组但存在重复。