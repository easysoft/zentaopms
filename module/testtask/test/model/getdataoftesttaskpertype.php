#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('case')->loadYaml('case')->gen(36);
zenData('testrun')->loadYaml('testrun')->gen(36);

/**

title=测试 testtaskModel->getDataOfTestTaskPerType();
timeout=0
cid=19152

- 测试单 0 中的用例数为 0。 @0
- 获取测试单 1 中类型为单元测试的用例数。
 - 第unit条的name属性 @单元测试
 - 第unit条的value属性 @1
- 获取测试单 1 中类型为接口测试的用例数。
 - 第interface条的name属性 @接口测试
 - 第interface条的value属性 @2
- 获取测试单 1 中类型为功能测试的用例数。
 - 第feature条的name属性 @功能测试
 - 第feature条的value属性 @2
- 获取测试单 1 中类型为安装部署的用例数。
 - 第install条的name属性 @安装部署
 - 第install条的value属性 @4
- 获取测试单 1 中类型为配置相关的用例数。
 - 第config条的name属性 @配置相关
 - 第config条的value属性 @4
- 获取测试单 1 中类型为性能测试的用例数。
 - 第performance条的name属性 @性能测试
 - 第performance条的value属性 @1
- 获取测试单 1 中类型为安全相关的用例数。
 - 第security条的name属性 @安全相关
 - 第security条的value属性 @6
- 获取测试单 1 中类型为其他的用例数。
 - 第other条的name属性 @其他
 - 第other条的value属性 @6
- 获取测试单 2 中类型为性能测试的用例数。
 - 第performance条的name属性 @性能测试
 - 第performance条的value属性

*/

global $tester;
$tester->loadModel('testtask');

$result0 = $tester->testtask->getDataOfTestTaskPerType(0, '1=1', 0);
$result1 = $tester->testtask->getDataOfTestTaskPerType(1, '1=1', 0);
$result2 = $tester->testtask->getDataOfTestTaskPerType(2, '1=1', 0);

r($result0) && p()                         && e('0');          // 测试单 0 中的用例数为 0。
r($result1) && p('unit:name,value')        && e('单元测试,1'); // 获取测试单 1 中类型为单元测试的用例数。
r($result1) && p('interface:name,value')   && e('接口测试,2'); // 获取测试单 1 中类型为接口测试的用例数。
r($result1) && p('feature:name,value')     && e('功能测试,2'); // 获取测试单 1 中类型为功能测试的用例数。
r($result1) && p('install:name,value')     && e('安装部署,4'); // 获取测试单 1 中类型为安装部署的用例数。
r($result1) && p('config:name,value')      && e('配置相关,4'); // 获取测试单 1 中类型为配置相关的用例数。
r($result1) && p('performance:name,value') && e('性能测试,1'); // 获取测试单 1 中类型为性能测试的用例数。
r($result1) && p('security:name,value')    && e('安全相关,6'); // 获取测试单 1 中类型为安全相关的用例数。
r($result1) && p('other:name,value')       && e('其他,6');     // 获取测试单 1 中类型为其他的用例数。
r($result2) && p('performance:name,value') && e('性能测试,4'); // 获取测试单 2 中类型为性能测试的用例数。
