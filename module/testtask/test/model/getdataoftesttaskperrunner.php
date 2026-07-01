#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('case')->loadYaml('case')->gen(20);
zenData('testrun')->loadYaml('testrun')->gen(20);
zenData('user')->gen(5);

/**

title=测试 testtaskModel->getDataOfTestTaskPerRunner();
timeout=0
cid=19152

- 测试单 0 中的用例数为 0。 @0
- 获取测试单 1 中的未执行的用例数。
 - 第0条的name属性 @未执行
 - 第0条的value属性 @1
- 获取测试单 1 中的用户 1 执行的用例数。
 - 第user1条的name属性 @用户1
 - 第user1条的value属性 @2
- 获取测试单 1 中的用户 2 执行的用例数。
 - 第user2条的name属性 @用户2
 - 第user2条的value属性 @2
- 获取测试单 1 中的用户 3 执行的用例数。
 - 第user3条的name属性 @用户3
 - 第user3条的value属性 @3
- 获取测试单 1 中的用户 4 执行的用例数。
 - 第user4条的name属性 @用户4
 - 第user4条的value属性 @4
- 获取测试单 2 中的未执行的用例数。
 - 第0条的name属性 @未执行
 - 第0条的value属性 @3
- 获取测试单 2 中的用户 1 执行的用例数。
 - 第user1条的name属性 @用户1
 - 第user1条的value属性

*/

global $tester;
$testtask = $tester->loadModel('testtask');

$result1 = $testtask->getDataOfTestTaskPerRunner(0, '1=1', 0);
$result2 = $testtask->getDataOfTestTaskPerRunner(1, '1=1', 0);
$result3 = $testtask->getDataOfTestTaskPerRunner(2, '1=1', 0);

foreach($result2 as $key => $value)
{
    if($key === '') $result2[] = $value;
}

foreach($result3 as $key => $value)
{
    if($key === '') $result3[] = $value;
}

r($result1) && p() && e(0);                            // 测试单 0 中的用例数为 0。
r($result2) && p('0:name,value')     && e('未执行,1'); // 获取测试单 1 中的未执行的用例数。
r($result2) && p('user1:name,value') && e('用户1,2');  // 获取测试单 1 中的用户 1 执行的用例数。
r($result2) && p('user2:name,value') && e('用户2,2');  // 获取测试单 1 中的用户 2 执行的用例数。
r($result2) && p('user3:name,value') && e('用户3,3');  // 获取测试单 1 中的用户 3 执行的用例数。
r($result2) && p('user4:name,value') && e('用户4,4');  // 获取测试单 1 中的用户 4 执行的用例数。
r($result3) && p('0:name,value')     && e('未执行,3'); // 获取测试单 2 中的未执行的用例数。
r($result3) && p('user1:name,value') && e('用户1,1');  // 获取测试单 2 中的用户 1 执行的用例数。
