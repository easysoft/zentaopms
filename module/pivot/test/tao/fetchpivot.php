#!/usr/bin/env php
<?php

/**

title=测试 pivotTao->fetchPivot();
timeout=0
cid=1

- 测试查询存在的透视表ID=1，不指定版本属性id @1
- 测试查询存在的透视表ID=2，指定版本=2属性id @2
- 测试查询不存在的透视表ID=999 @0
- 测试查询存在的透视表ID=3，指定不存在版本属性id @3
- 测试查询已删除的透视表ID=9 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

zenData('pivot')->gen(10);
zenData('pivotspec')->gen(5);

$pivot = new pivotTest();

r($pivot->fetchPivotTest(1)) && p('id') && e('1');       //测试查询存在的透视表ID=1，不指定版本
r($pivot->fetchPivotTest(2, '2')) && p('id') && e('2');  //测试查询存在的透视表ID=2，指定版本=2
r($pivot->fetchPivotTest(999)) && p('') && e('0');      //测试查询不存在的透视表ID=999
r($pivot->fetchPivotTest(3, '99')) && p('id') && e('3'); //测试查询存在的透视表ID=3，指定不存在版本
r($pivot->fetchPivotTest(9)) && p('') && e('0');        //测试查询已删除的透视表ID=9