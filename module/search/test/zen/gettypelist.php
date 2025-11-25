#!/usr/bin/env php
<?php

/**

title=测试 searchZen::getTypeList();
timeout=0
cid=18348

- 执行$result1['all'] @1
- 执行$result2 @1
- 执行$result3属性all @全部
- 执行$result4) > 0 @1
- 执行$result5)[0] @all
- 执行$result6属性all @全部
- 执行$result7) && isset($result7['all'] @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('searchindex')->loadYaml('searchindex', false, 2)->gen(50);

su('admin');

$searchTest = new searchZenTest();

$result1 = $searchTest->getTypeListTest();
$result2 = $searchTest->getTypeListTest();
$result3 = $searchTest->getTypeListTest();
$result4 = $searchTest->getTypeListTest();
$result5 = $searchTest->getTypeListTest();
$result6 = $searchTest->getTypeListTest();
$result7 = $searchTest->getTypeListTest();

r(isset($result1['all'])) && p() && e('1');
r(is_array($result2)) && p() && e('1');
r($result3) && p('all') && e('全部');
r(count($result4) > 0) && p() && e('1');
r(array_keys($result5)[0]) && p() && e('all');
r($result6) && p('all') && e('全部');
r(is_array($result7) && isset($result7['all'])) && p() && e('1');