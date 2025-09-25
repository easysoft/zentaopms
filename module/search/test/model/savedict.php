#!/usr/bin/env php
<?php

/**

title=测试 searchModel::saveDict();
timeout=0
cid=0

- 测试步骤1：保存有效字典数据属性result @1
- 测试步骤2：测试边界值属性count @2
- 测试步骤3：测试无效key属性count @0
- 测试步骤4：测试空值和重复key属性count @1
- 测试步骤5：测试空字典数组属性result @1
- 测试步骤6：测试批量保存属性count @3
- 测试步骤7：测试字符串分词保存属性result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$search = new searchTest();

r($search->saveDictTest(array('12345' => '测试'))) && p('result') && e('1'); // 测试步骤1：保存有效字典数据
r($search->saveDictTest(array('00000' => '边界1', '65535' => '边界2'))) && p('count') && e('2'); // 测试步骤2：测试边界值
r($search->saveDictTest(array('abc' => '无效', '1234' => '长度错', '99999' => '超范围'))) && p('count') && e('0'); // 测试步骤3：测试无效key
r($search->saveDictTest(array('12345' => '', '23456' => '有效', '12345' => '重复'))) && p('count') && e('1'); // 测试步骤4：测试空值和重复key
r($search->saveDictTest(array())) && p('result') && e('1'); // 测试步骤5：测试空字典数组
r($search->saveDictTest(array('11111' => '词1', '22222' => '词2', '33333' => '词3'))) && p('count') && e('3'); // 测试步骤6：测试批量保存
r($search->saveDictTest('我是测试文本')) && p('result') && e('1'); // 测试步骤7：测试字符串分词保存