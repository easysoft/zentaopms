#!/usr/bin/env php
<?php
/**
title=getExecutableCalcList
timeout=0
cid=1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

r($metric->getExecutableCalcList()) && p('1:code')  && e('count_of_program');                    // 测试可执行的第1个度量项的代号
r($metric->getExecutableCalcList()) && p('2:code')  && e('count_of_doing_program');              // 测试可执行的第2个度量项的代号
r($metric->getExecutableCalcList()) && p('3:code')  && e('count_of_closed_program');             // 测试可执行的第3个度量项的代号
r($metric->getExecutableCalcList()) && p('4:code')  && e('count_of_suspended_program');          // 测试可执行的第4个度量项的代号
r($metric->getExecutableCalcList()) && p('5:code')  && e('count_of_wait_program');               // 测试可执行的第5个度量项的代号
r($metric->getExecutableCalcList()) && p('6:code')  && e('count_of_top_program');                // 测试可执行的第6个度量项的代号
r($metric->getExecutableCalcList()) && p('7:code')  && e('count_of_closed_top_program');         // 测试可执行的第7个度量项的代号
r($metric->getExecutableCalcList()) && p('8:code')  && e('count_of_unclosed_top_program');       // 测试可执行的第8个度量项的代号
r($metric->getExecutableCalcList()) && p('9:code')  && e('count_of_annual_created_top_program'); // 测试可执行的第9个度量项的代号
r($metric->getExecutableCalcList()) && p('10:code') && e('count_of_annual_closed_top_program');  // 测试可执行的第10个度量项的代号
r($metric->getExecutableCalcList()) && p('11:code') && e('count_of_line');                       // 测试可执行的第11个度量项的代号
r($metric->getExecutableCalcList()) && p('12:code') && e('count_of_product');                    // 测试可执行的第12个度量项的代号
r($metric->getExecutableCalcList()) && p('13:code') && e('count_of_normal_product');             // 测试可执行的第13个度量项的代号
