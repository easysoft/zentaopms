#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('lang')->gen(10);

/**

title=测试 commonModel::loadCustomFromDB();
timeout=0
cid=15687

- 查看设置后的语言项的数量 @3
- 查看设置后的语言项的值第section1条的key1属性 @value1
- 查看设置后的语言项的值第section8条的key8属性 @value8
- 查看设置后的语言项的值第section6条的key6属性 @value6
- 升级的时候，返回0 @0
- 未安装禅道的时候，返回0 @0

*/

su('admin');
global $lang;
$tester->loadModel('common')->loadCustomFromDB();

r(count($lang->db->custom))      && p('')              && e(3);        // 查看设置后的语言项的数量
r($lang->db->custom['process'])  && p('section1:key1') && e('value1'); // 查看设置后的语言项的值
r($lang->db->custom['task'])     && p('section8:key8') && e('value8'); // 查看设置后的语言项的值
r($lang->db->custom['stage'])    && p('section6:key6') && e('value6'); // 查看设置后的语言项的值

$tester->app->upgrading = true;
r($tester->loadModel('common')->loadCustomFromDB()) && p() && e(0);  // 升级的时候，返回0

$tester->app->upgrading   = false;
$tester->config->db->name = '';
r($tester->loadModel('common')->loadCustomFromDB()) && p() && e(0);  // 未安装禅道的时候，返回0
