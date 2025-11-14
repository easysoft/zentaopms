#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('case')->loadYaml('case')->gen(20);
zenData('testrun')->loadYaml('testrun')->gen(20);
zenData('module')->loadYaml('module')->gen(4);

/**

title=测试 testtaskModel->getDataOfTestTaskPerModule();
cid=19168
pid=1

*/

global $tester;
$testtask = $tester->loadModel('testtask');

$result1 = $testtask->getDataOfTestTaskPerModule(0);
$result2 = $testtask->getDataOfTestTaskPerModule(1);
$result3 = $testtask->getDataOfTestTaskPerModule(2);

r($result1) && p() && e(0);                                    // 测试单 0 中的用例数为 0。
r($result2) && p('0:name,value') && e('/,1');                  // 获取测试单 1 中的无模块的用例数。
r($result2) && p('1:name,value') && e('/模块1,2');             // 获取测试单 1 中的模块 1 的用例数。
r($result2) && p('2:name,value') && e('/模块2,2');             // 获取测试单 1 中的模块 2 的用例数。
r($result2) && p('3:name,value') && e('/模块1/模块3,3');       // 获取测试单 1 中的模块 3 的用例数。
r($result2) && p('4:name,value') && e('/模块1/模块3/模块4,4'); // 获取测试单 1 中的模块 4 的用例数。
r($result3) && p('0:name,value') && e('/,3');                  // 获取测试单 2 中的无模块的用例数。
r($result3) && p('1:name,value') && e('/模块1,1');             // 获取测试单 2 中的模块 1 的用例数。
