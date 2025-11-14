#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('case')->loadYaml('case')->gen(15);
zenData('testrun')->loadYaml('testrun')->gen(15);

/**

title=测试 testtaskModel->getDataOfTestTaskPerRunResult();
cid=19170
pid=1

*/

global $tester;
$testtask = $tester->loadModel('testtask');

$result1 = $testtask->getDataOfTestTaskPerRunResult(0);
$result2 = $testtask->getDataOfTestTaskPerRunResult(1);
$result3 = $testtask->getDataOfTestTaskPerRunResult(2);

foreach($result2 as $key => $value)
{
    if($key === '') $result2[] = $value;
}

foreach($result3 as $key => $value)
{
    if($key === '') $result3[] = $value;
}

r($result1) && p() && e(0);                              // 测试单 0 中的用例数为 0。
r($result2) && p('0:name,value')       && e('未执行,1'); // 获取测试单 1 中的未执行的用例数。
r($result2) && p('pass:name,value')    && e('通过,2');   // 获取测试单 1 中的执行结果为通过的用例数。
r($result2) && p('fail:name,value')    && e('失败,2');   // 获取测试单 1 中的执行结果为失败的用例数。
r($result2) && p('blocked:name,value') && e('阻塞,3');   // 获取测试单 1 中的执行结果为阻塞的用例数。
r($result3) && p('0:name,value')       && e('未执行,1'); // 获取测试单 2 中的未执行的用例数。
r($result3) && p('pass:name,value')    && e('通过,2');   // 获取测试单 2 中的执行结果为通过的用例数。
r($result3) && p('fail:name,value')    && e('失败,1');   // 获取测试单 2 中的执行结果为失败的用例数。
