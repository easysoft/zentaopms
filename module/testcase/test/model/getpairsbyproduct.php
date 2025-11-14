#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->gen('200');

su('admin');

/**

title=测试 testcaseModel->getPairsByProduct();
timeout=0
cid=18993

- 获取产品1的case @4:这个是测试用例4,3:这个是测试用例3,2:这个是测试用例2,1:这个是测试用例1

- 获取产品2的case @8:这个是测试用例8,7:这个是测试用例7,6:这个是测试用例6,5:这个是测试用例5

- 获取产品3的case @12:这个是测试用例12,11:这个是测试用例11,10:这个是测试用例10,9:这个是测试用例9

- 获取产品4的case @16:这个是测试用例16,15:这个是测试用例15,14:这个是测试用例14,13:这个是测试用例13

- 获取产品5的case @20:这个是测试用例20,19:这个是测试用例19,18:这个是测试用例18,17:这个是测试用例17

- 获取产品41分支 1 的case @0
- 获取产品41分支 0 1 的case @164:这个是测试用例164,163:这个是测试用例163,162:这个是测试用例162,161:这个是测试用例161

- 获取产品41分支 1 搜索标题包含16的case @164:这个是测试用例164,163:这个是测试用例163,162:这个是测试用例162,161:这个是测试用例161

- 获取产品41分支 1 搜索标题包含20的case @0
- 获取产品41分支 1 搜索标题包含16 限制条目数为1的case @164:这个是测试用例164

*/
$productIDList = array(1, 2, 3, 4, 5, 41);
$branch        = array(1, array(0, 1));

$testcase = new testcaseTest();
r($testcase->getPairsByProductTest($productIDList[0]))                      && p() && e('4:这个是测试用例4,3:这个是测试用例3,2:这个是测试用例2,1:这个是测试用例1');                 // 获取产品1的case
r($testcase->getPairsByProductTest($productIDList[1]))                      && p() && e('8:这个是测试用例8,7:这个是测试用例7,6:这个是测试用例6,5:这个是测试用例5');                 // 获取产品2的case
r($testcase->getPairsByProductTest($productIDList[2]))                      && p() && e('12:这个是测试用例12,11:这个是测试用例11,10:这个是测试用例10,9:这个是测试用例9');           // 获取产品3的case
r($testcase->getPairsByProductTest($productIDList[3]))                      && p() && e('16:这个是测试用例16,15:这个是测试用例15,14:这个是测试用例14,13:这个是测试用例13');         // 获取产品4的case
r($testcase->getPairsByProductTest($productIDList[4]))                      && p() && e('20:这个是测试用例20,19:这个是测试用例19,18:这个是测试用例18,17:这个是测试用例17');         // 获取产品5的case
r($testcase->getPairsByProductTest($productIDList[5], $branch[0]))          && p() && e('0');                                                                                       // 获取产品41分支 1 的case
r($testcase->getPairsByProductTest($productIDList[5], $branch[1]))          && p() && e('164:这个是测试用例164,163:这个是测试用例163,162:这个是测试用例162,161:这个是测试用例161'); // 获取产品41分支 0 1 的case
r($testcase->getPairsByProductTest($productIDList[5], $branch[1], '16'))    && p() && e('164:这个是测试用例164,163:这个是测试用例163,162:这个是测试用例162,161:这个是测试用例161'); // 获取产品41分支 1 搜索标题包含16的case
r($testcase->getPairsByProductTest($productIDList[5], $branch[1], '20'))    && p() && e('0');                                                                                       // 获取产品41分支 1 搜索标题包含20的case
r($testcase->getPairsByProductTest($productIDList[5], $branch[1], '16', 1)) && p() && e('164:这个是测试用例164');                                                                   // 获取产品41分支 1 搜索标题包含16 限制条目数为1的case