#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('case')->loadYaml('case_getcasesfrombug')->gen(50);

/**

title=测试 bugTao::getCasesFromBug;
timeout=0
cid=15416


*/

global $tester;
$tester->loadModel('bug');

$bugIdList = array(1, 2, 3, 4, 10001);

r(implode(',', $tester->bug->getCasesFromBug($bugIdList[0]))) && p()  && e('这个是测试用例1,这个是测试用例11,这个是测试用例21,这个是测试用例31,这个是测试用例41'); // 获取ID等于1的bug
r(implode(',', $tester->bug->getCasesFromBug($bugIdList[1]))) && p()  && e('这个是测试用例2,这个是测试用例12,这个是测试用例22,这个是测试用例32,这个是测试用例42'); // 获取ID等于2的bug
r(implode(',', $tester->bug->getCasesFromBug($bugIdList[2]))) && p()  && e('这个是测试用例3,这个是测试用例13,这个是测试用例23,这个是测试用例33,这个是测试用例43'); // 获取ID等于3的bug
r(implode(',', $tester->bug->getCasesFromBug($bugIdList[3]))) && p()  && e('这个是测试用例4,这个是测试用例14,这个是测试用例24,这个是测试用例34,这个是测试用例44'); // 获取ID等于4的bug
r(implode(',', $tester->bug->getCasesFromBug($bugIdList[4]))) && p()  && e('0'); // 获取ID不存在的bug
