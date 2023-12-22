#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('lang')->gen(10);

/**

title=测试 commonModel::loadCustomFromDB();
timeout=0
cid=1

- 查看设置后的配置项数量 @5
- 查看设置后的配置项详情
 - 第0条的owner属性 @system
 - 第0条的module属性 @common
 - 第0条的section属性 @global
 - 第0条的key属性 @hourPoint
 - 第0条的value属性 @0

*/

su('admin');
$tester->loadModel('common')->loadCustomFromDB();

r(count($lang->db->custom))   && p('')              && e(3);        // 查看设置后的语言项的数量
r($lang->db->custom->process) && p('section1:key1') && e('value1'); // 查看设置后的语言项的值
r($lang->db->custom->task)    && p('section8:key8') && e('value8'); // 查看设置后的语言项的值
r($lang->db->custom->stage)   && p('section6:key6') && e('value6'); // 查看设置后的语言项的值
