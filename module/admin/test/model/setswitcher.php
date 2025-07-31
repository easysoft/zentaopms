#!/usr/bin/env php
<?php

/**

title=测试adminModel->setSwitcher();
timeout=0
cid=0

- 获取switcherMenu的信息。 @0
- 获取switcherMenu的第10个开始的字符串信息。 @='btn-group header-btn'><butto
- 获取switcherMenu的第50个开始的字符串信息。 @tn pull-right btn-link' data-t
- 获取switcherMenu的第80个开始的字符串信息。 @oggle='dropdown'><span class='
- 获取switcherMenu的第100个开始的字符串信息。 @an class='text'>系统设置</span> <s

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester,$lang;
$tester->loadModel('admin');
r(!empty($lang->switcherMenu)) && p() && e(0);  // 获取switcherMenu的信息。
$tester->admin->setSwitcher();
r(mb_substr($lang->switcherMenu, '10',  '30')) && p() && e("='btn-group header-btn'><butto");     // 获取switcherMenu的第10个开始的字符串信息。
r(mb_substr($lang->switcherMenu, '50',  '30')) && p() && e("tn pull-right btn-link' data-t");     // 获取switcherMenu的第50个开始的字符串信息。
r(mb_substr($lang->switcherMenu, '80',  '30')) && p() && e("oggle='dropdown'><span class='");     // 获取switcherMenu的第80个开始的字符串信息。
r(mb_substr($lang->switcherMenu, '100', '30')) && p() && e("an class='text'>系统设置</span> <s"); // 获取switcherMenu的第100个开始的字符串信息。
