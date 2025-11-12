#!/usr/bin/env php
<?php

/**

title=测试 customZen::setFieldListForSet();
timeout=0
cid=0

- 执行customTest模块的setFieldListForSetTest方法，参数是'story', 'priList', array  @1
- 执行customTest模块的setFieldListForSetTest方法，参数是'project', 'unitList', array 属性message @至少选择一种货币
- 执行customTest模块的setFieldListForSetTest方法，参数是'project', 'unitList', array 属性message @默认货币不能为空
- 执行customTest模块的setFieldListForSetTest方法，参数是'bug', 'longlife', array  @1
- 执行customTest模块的setFieldListForSetTest方法，参数是'user', 'contactField', array  @1
- 执行customTest模块的setFieldListForSetTest方法，参数是'user', 'deleted', array  @1
- 执行customTest模块的setFieldListForSetTest方法，参数是'story', 'sourceList', array 属性message @『键』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('config')->gen(0);
zenData('lang')->gen(0);

su('admin');

$customTest = new customZenTest();

r($customTest->setFieldListForSetTest('story', 'priList', array('lang' => 'zh-cn', 'keys' => array('1', '2', '3'), 'values' => array('高', '中', '低'), 'systems' => array('0', '0', '0')))) && p() && e('1');
r($customTest->setFieldListForSetTest('project', 'unitList', array('defaultCurrency' => 'CNY'))) && p('message') && e('至少选择一种货币');
r($customTest->setFieldListForSetTest('project', 'unitList', array('unitList' => array('CNY', 'USD')))) && p('message') && e('默认货币不能为空');
r($customTest->setFieldListForSetTest('bug', 'longlife', array('longlife' => '365'))) && p() && e('1');
r($customTest->setFieldListForSetTest('user', 'contactField', array('contactField' => array('email', 'phone')))) && p() && e('1');
r($customTest->setFieldListForSetTest('user', 'deleted', array('showDeleted' => '1'))) && p() && e('1');
r($customTest->setFieldListForSetTest('story', 'sourceList', array('lang' => 'zh-cn'))) && p('message') && e('『键』不能为空。');