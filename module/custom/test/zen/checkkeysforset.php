#!/usr/bin/env php
<?php
/**

title=测试 customZen::checkKeysForSet();
timeout=0
cid=15935

- 执行customTest模块的checkKeysForSetTest方法，参数是'story', 'priList', 'zh-cn', array  @1
- 执行customTest模块的checkKeysForSetTest方法，参数是'story', 'priList', 'zh-cn', array 属性message @1键重复
- 执行customTest模块的checkKeysForSetTest方法，参数是'story', 'sourceList', 'zh-cn', array 属性message @键值应当为大小写英文字母、数字或下划线的组合
- 执行customTest模块的checkKeysForSetTest方法，参数是'story', 'priList', 'zh-cn', array 属性message @值不能为空！
- 执行customTest模块的checkKeysForSetTest方法，参数是'story', 'priList', 'zh-cn', array 属性message @键值应为不大于255的数字
- 执行customTest模块的checkKeysForSetTest方法，参数是'story', 'sourceList', 'zh-cn', array  @1
- 执行customTest模块的checkKeysForSetTest方法，参数是'user', 'roleList', 'zh-cn', array 属性message @键的长度必须小于10个字符！

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('config')->gen(0);

su('admin');

$customTest = new customZenTest();

r($customTest->checkKeysForSetTest('story', 'priList', 'zh-cn', array('1', '2', '3'), array('高', '中', '低'), array('0', '0', '0'))) && p() && e('1');
r($customTest->checkKeysForSetTest('story', 'priList', 'zh-cn', array('1', '2', '1'), array('高', '中', '低'), array('0', '0', '0'))) && p('message') && e('1键重复');
r($customTest->checkKeysForSetTest('story', 'sourceList', 'zh-cn', array('valid_key', 'invalid-key'), array('来源1', '来源2'), array('0', '0'))) && p('message') && e('键值应当为大小写英文字母、数字或下划线的组合');
r($customTest->checkKeysForSetTest('story', 'priList', 'zh-cn', array('1', '2'), array('高', ''), array('0', '0'))) && p('message') && e('值不能为空！');
r($customTest->checkKeysForSetTest('story', 'priList', 'zh-cn', array('1', '256'), array('高', '中'), array('0', '0'))) && p('message') && e('键值应为不大于255的数字');
r($customTest->checkKeysForSetTest('story', 'sourceList', 'zh-cn', array('customer', 'market', 'internal'), array('客户', '市场', '内部'), array('0', '0', '0'))) && p() && e('1');
r($customTest->checkKeysForSetTest('user', 'roleList', 'zh-cn', array('short', 'this_is_too_long_key'), array('角色1', '角色2'), array('0', '0'))) && p('message') && e('键的长度必须小于10个字符！');
