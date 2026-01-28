#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('bug')->gen(30);
$action = zenData('action')->loadYaml('action');
$action->date->range("20250401 000000-20250601 000000:1D")->type('timestamp')->format('YY-MM-DD hh:mm:ss');
$action->gen(100);
zenData('history')->loadYaml('history')->gen(100);
zenData('user')->gen(1);

su('admin');

/**

title=bugModel->getActivatedBugs();
timeout=0
cid=15353

- 测试获取产品 1 2 3 类型 空 开始日期 上月 结束日期 下月 的bug @1,2,5,6,9

- 测试获取产品 1 2 3 类型 空 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 1 2 3 类型 resolved 开始日期 上月 结束日期 下月 的bug @1,5,6,9

- 测试获取产品 1 2 3 类型 resolved 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 1 2 3 类型 opened 开始日期 上月 结束日期 下月 的bug @2,5,6,9

- 测试获取产品 1 2 3 类型 opened 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 4 5 6 类型 空 开始日期 上月 结束日期 下月 的bug @10,13,14,17,18

- 测试获取产品 4 5 6 类型 空 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 4 5 6 类型 resolved 开始日期 上月 结束日期 下月 的bug @10,13,14,17,18

- 测试获取产品 4 5 6 类型 resolved 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 4 5 6 类型 opened 开始日期 上月 结束日期 下月 的bug @10,13,14,17,18

- 测试获取产品 4 5 6 类型 opened 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 7 8 9 类型 空 开始日期 上月 结束日期 下月 的bug @21,22,25,26

- 测试获取产品 7 8 9 类型 空 开始日期 上周 结束日期 下周 的bug @25,26

- 测试获取产品 7 8 9 类型 resolved 开始日期 上月 结束日期 下月 的bug @21,22,25,26

- 测试获取产品 7 8 9 类型 resolved 开始日期 上周 结束日期 下周 的bug @25,26

- 测试获取产品 7 8 9 类型 opened 开始日期 上月 结束日期 下月 的bug @21,22,25,26

- 测试获取产品 7 8 9 类型 opened 开始日期 上周 结束日期 下周 的bug @25,26

- 测试获取产品 空 类型 空 开始日期 上月 结束日期 下月 的bug @0
- 测试获取产品 空 类型 空 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 空 类型 resolved 开始日期 上月 结束日期 下月 的bug @0
- 测试获取产品 空 类型 resolved 开始日期 上周 结束日期 下周 的bug @0
- 测试获取产品 空 类型 opened 开始日期 上月 结束日期 下月 的bug @0
- 测试获取产品 空 类型 opened 开始日期 上周 结束日期 下周 的bug @0

*/

$productIdList = array('1,2,3', '4,5,6', '7,8,9', '0');
$beginList     = array('lastmonth', 'lastweek');
$endList       = array('nextmonth', 'nextweek');
$buildIdList   = array('1,0,trunk', '1,trunk', '0,trunk');

$bug = new bugModelTest();

r($bug->getActivatedBugsTest($productIdList[0], $beginList[0], $endList[0], $buildIdList[0])) && p() && e('1,2,5,6,9'); // 测试获取产品 1 2 3 类型 空 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[0], $beginList[1], $endList[1], $buildIdList[0])) && p() && e('0');         // 测试获取产品 1 2 3 类型 空 开始日期 上周 结束日期 下周 的bug
r($bug->getActivatedBugsTest($productIdList[0], $beginList[0], $endList[0], $buildIdList[1])) && p() && e('1,5,6,9');   // 测试获取产品 1 2 3 类型 resolved 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[0], $beginList[1], $endList[1], $buildIdList[1])) && p() && e('0');         // 测试获取产品 1 2 3 类型 resolved 开始日期 上周 结束日期 下周 的bug
r($bug->getActivatedBugsTest($productIdList[0], $beginList[0], $endList[0], $buildIdList[2])) && p() && e('2,5,6,9');   // 测试获取产品 1 2 3 类型 opened 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[0], $beginList[1], $endList[1], $buildIdList[2])) && p() && e('0');         // 测试获取产品 1 2 3 类型 opened 开始日期 上周 结束日期 下周 的bug

r($bug->getActivatedBugsTest($productIdList[1], $beginList[0], $endList[0], $buildIdList[0])) && p() && e('10,13,14,17,18'); // 测试获取产品 4 5 6 类型 空 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[1], $beginList[1], $endList[1], $buildIdList[0])) && p() && e('0');              // 测试获取产品 4 5 6 类型 空 开始日期 上周 结束日期 下周 的bug
r($bug->getActivatedBugsTest($productIdList[1], $beginList[0], $endList[0], $buildIdList[1])) && p() && e('10,13,14,17,18'); // 测试获取产品 4 5 6 类型 resolved 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[1], $beginList[1], $endList[1], $buildIdList[1])) && p() && e('0');              // 测试获取产品 4 5 6 类型 resolved 开始日期 上周 结束日期 下周 的bug
r($bug->getActivatedBugsTest($productIdList[1], $beginList[0], $endList[0], $buildIdList[2])) && p() && e('10,13,14,17,18'); // 测试获取产品 4 5 6 类型 opened 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[1], $beginList[1], $endList[1], $buildIdList[2])) && p() && e('0');              // 测试获取产品 4 5 6 类型 opened 开始日期 上周 结束日期 下周 的bug

r($bug->getActivatedBugsTest($productIdList[2], $beginList[0], $endList[0], $buildIdList[0])) && p() && e('21,22,25,26'); // 测试获取产品 7 8 9 类型 空 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[2], $beginList[1], $endList[1], $buildIdList[0])) && p() && e('25,26');       // 测试获取产品 7 8 9 类型 空 开始日期 上周 结束日期 下周 的bug
r($bug->getActivatedBugsTest($productIdList[2], $beginList[0], $endList[0], $buildIdList[1])) && p() && e('21,22,25,26'); // 测试获取产品 7 8 9 类型 resolved 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[2], $beginList[1], $endList[1], $buildIdList[1])) && p() && e('25,26');       // 测试获取产品 7 8 9 类型 resolved 开始日期 上周 结束日期 下周 的bug
r($bug->getActivatedBugsTest($productIdList[2], $beginList[0], $endList[0], $buildIdList[2])) && p() && e('21,22,25,26'); // 测试获取产品 7 8 9 类型 opened 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[2], $beginList[1], $endList[1], $buildIdList[2])) && p() && e('25,26');       // 测试获取产品 7 8 9 类型 opened 开始日期 上周 结束日期 下周 的bug

r($bug->getActivatedBugsTest($productIdList[3], $beginList[0], $endList[0], $buildIdList[0])) && p() && e('0'); // 测试获取产品 空 类型 空 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[3], $beginList[1], $endList[1], $buildIdList[0])) && p() && e('0'); // 测试获取产品 空 类型 空 开始日期 上周 结束日期 下周 的bug
r($bug->getActivatedBugsTest($productIdList[3], $beginList[0], $endList[0], $buildIdList[1])) && p() && e('0'); // 测试获取产品 空 类型 resolved 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[3], $beginList[1], $endList[1], $buildIdList[1])) && p() && e('0'); // 测试获取产品 空 类型 resolved 开始日期 上周 结束日期 下周 的bug
r($bug->getActivatedBugsTest($productIdList[3], $beginList[0], $endList[0], $buildIdList[2])) && p() && e('0'); // 测试获取产品 空 类型 opened 开始日期 上月 结束日期 下月 的bug
r($bug->getActivatedBugsTest($productIdList[3], $beginList[1], $endList[1], $buildIdList[2])) && p() && e('0'); // 测试获取产品 空 类型 opened 开始日期 上周 结束日期 下周 的bug
