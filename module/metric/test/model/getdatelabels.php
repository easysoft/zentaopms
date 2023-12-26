#!/usr/bin/env php
<?php

/**

title=getDateLabels
timeout=0
cid=1

- 测试传入year的情况
 - 属性3 @近3年
 - 属性5 @近5年
 - 属性10 @近10年
 - 属性all @全部
- 测试传入month的情况
 - 属性6 @近6个月
 - 属性12 @近12个月
 - 属性24 @近24个月
 - 属性36 @近36个月
- 测试传入week的情况
 - 属性4 @近4周
 - 属性8 @近8周
 - 属性12 @近12周
 - 属性16 @近16周
- 测试传入day的情况
 - 属性7 @近7天
 - 属性14 @近14天
 - 属性21 @近21天
 - 属性28 @近28天
- 测试传入nodate的情况 @empty array

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

r($metric->getDateLabels('year'))   && p('3;5;10;all') && e('近3年,近5年,近10年,全部');            // 测试传入year的情况
r($metric->getDateLabels('month'))  && p('6;12;24;36') && e('近6个月,近12个月,近24个月,近36个月'); // 测试传入month的情况
r($metric->getDateLabels('week'))   && p('4;8;12;16')  && e('近4周,近8周,近12周,近16周');          // 测试传入week的情况
r($metric->getDateLabels('day'))    && p('7;14;21;28') && e('近7天,近14天,近21天,近28天');         // 测试传入day的情况
r($metric->getDateLabels('nodate')) && p('')           && e('empty array');                        // 测试传入nodate的情况