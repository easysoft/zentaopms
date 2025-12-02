#!/usr/bin/env php
<?php
/**

title=测试 productZen::formatExportData();
timeout=0
cid=1

- 测试空数据的情况 @0
- 测试正常数据的情况
 - 第0条的testCaseCoverage属性 @10%
 - 第0条的epicCompleteRate属性 @20%
 - 第0条的requirementCompleteRate属性 @30%
 - 第0条的storyCompleteRate属性 @50%
 - 第0条的bugFixedRate属性 @0%

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(10);
zenData('user')->gen(10);
su('admin');

$products   = array();
$products[] = array('testCaseCoverage' => 10, 'epicCompleteRate' => 20, 'requirementCompleteRate' => 30, 'storyCompleteRate' => 50);

$productTest = new productZenTest();
r($productTest->formatExportDataTest(array()))   && p()                                                                                             && e('0');                  // 测试空数据的情况
r($productTest->formatExportDataTest($products)) && p('0:testCaseCoverage,epicCompleteRate,requirementCompleteRate,storyCompleteRate,bugFixedRate') && e('10%,20%,30%,50%,0%'); // 测试正常数据的情况
