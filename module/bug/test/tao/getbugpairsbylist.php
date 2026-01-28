#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('bug')->loadYaml('casebug')->gen(10);

/**

title=测试 bugTao::getBugPairsByList;
timeout=0
cid=15415


*/

$bugIdList  = array('1,2,3', '1,4', '2,7', '2,6,9', '1000001');

global $tester;
$tester->loadModel('bug');

r(implode(',', $tester->bug->getBugPairsByList($bugIdList[0]))) && p()  && e('测试单转Bug1,SonarQube_Bug2,测试单转Bug3');   // 获取 ID 等于 1 2 3 的bug
r(implode(',', $tester->bug->getBugPairsByList($bugIdList[1]))) && p()  && e('测试单转Bug1,SonarQube_Bug4');                // 获取 ID 等于 1 4 的bug
r(implode(',', $tester->bug->getBugPairsByList($bugIdList[2]))) && p()  && e('SonarQube_Bug2,测试单转Bug7');                // 获取 ID 等于 2 7 的bug
r(implode(',', $tester->bug->getBugPairsByList($bugIdList[3]))) && p()  && e('SonarQube_Bug2,SonarQube_Bug6,测试单转Bug9'); // 获取 ID 等于 2 6 9的bug
r(implode(',', $tester->bug->getBugPairsByList($bugIdList[4]))) && p()  && e('0');                                          // 获取 ID 不存在的bug
