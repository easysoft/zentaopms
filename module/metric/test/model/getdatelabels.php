#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

/**

title=getDateLabels
timeout=0
cid=1

*/

r($metric->getDateLabels('year'))   && p('3;5;10;all') && e('近3年,近5年,近10年,全部');            // 测试传入year的情况
r($metric->getDateLabels('month'))  && p('6;12;24;36') && e('近6个月,近12个月,近24个月,近36个月'); // 测试传入month的情况
r($metric->getDateLabels('week'))   && p('4;8;12;16')  && e('近4周,近8周,近12周,近16周');          // 测试传入week的情况
r($metric->getDateLabels('day'))    && p('7;14;21;28') && e('近7天,近14天,近21天,近28天');         // 测试传入day的情况
r($metric->getDateLabels('nodate')) && p('')           && e('empty array');                        // 测试传入nodate的情况
