#!/usr/bin/env php
<?php

/**

title=buildDateCell
timeout=0
cid=1

- buildDateCell 传入 nodate
 - 属性date @2023-11-12
 - 属性dateString @2023-11-12
 - 属性calcTime @2023-11-12
- buildDateCell 传入 day
 - 属性date @2023-12-21
 - 属性dateString @2023-12-21
 - 属性calcTime @2023-11-12
- buildDateCell 传入 week
 - 属性date @2023年第52周
 - 属性dateString @2023-52
 - 属性calcTime @2023-11-12
- buildDateCell 传入 month
 - 属性date @2023年12月
 - 属性dateString @2023-12
 - 属性calcTime @2023-11-12
- buildDateCell 传入 year
 - 属性date @2023年
 - 属性dateString @2023
 - 属性calcTime @2023-11-12

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$record1 = array('year' => 2023, 'month' => 12, 'week' => 52, 'day' => 21, 'calcTime' => '2023-11-12');
r($metric->buildDateCell($record1, 'nodate')) && p('date,dateString,calcTime') && e('2023-11-12,2023-11-12,2023-11-12'); // buildDateCell 传入 nodate
r($metric->buildDateCell($record1, 'day'))    && p('date,dateString,calcTime') && e('2023-12-21,2023-12-21,2023-11-12'); // buildDateCell 传入 day
r($metric->buildDateCell($record1, 'week'))   && p('date,dateString,calcTime') && e('2023年第52周,2023-52,2023-11-12');  // buildDateCell 传入 week
r($metric->buildDateCell($record1, 'month'))  && p('date,dateString,calcTime') && e('2023年12月,2023-12,2023-11-12');    // buildDateCell 传入 month
r($metric->buildDateCell($record1, 'year'))   && p('date,dateString,calcTime') && e('2023年,2023,2023-11-12');           // buildDateCell 传入 year