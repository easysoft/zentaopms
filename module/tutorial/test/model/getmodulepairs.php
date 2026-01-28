#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 tutorialModel::getModulePairs();
timeout=0
cid=19445

- 测试admin用户获取模块键值对属性1 @Test module
- 测试user1用户获取模块键值对属性1 @Test module
- 测试guest用户获取模块键值对属性1 @Test module
- 测试键值1对应的模块名称属性1 @Test module
- 测试返回数据结构完整性属性1 @Test module
- 测试多次调用的一致性属性1 @Test module

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

$tutorial = new tutorialModelTest();

su('admin');
r($tutorial->getModulePairsTest()) && p('1') && e('Test module'); // 测试admin用户获取模块键值对

su('user1');
r($tutorial->getModulePairsTest()) && p('1') && e('Test module'); // 测试user1用户获取模块键值对

su('guest');
r($tutorial->getModulePairsTest()) && p('1') && e('Test module'); // 测试guest用户获取模块键值对

su('admin');
r($tutorial->getModulePairsTest()) && p('1') && e('Test module'); // 测试键值1对应的模块名称

$firstResult = $tutorial->getModulePairsTest();
$secondResult = $tutorial->getModulePairsTest();
r($firstResult) && p('1') && e('Test module'); // 测试返回数据结构完整性

r($secondResult) && p('1') && e('Test module'); // 测试多次调用的一致性