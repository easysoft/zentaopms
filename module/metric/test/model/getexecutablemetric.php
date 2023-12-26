#!/usr/bin/env php
<?php

/**

title=getExecutableMetric
timeout=0
cid=1

- 测试可执行的第1个度量项的代号属性1 @count_of_program
- 测试可执行的第2个度量项的代号属性2 @count_of_doing_program
- 测试可执行的第3个度量项的代号属性3 @count_of_closed_program
- 测试可执行的第4个度量项的代号属性4 @count_of_suspended_program
- 测试可执行的第5个度量项的代号属性5 @count_of_wait_program
- 测试可执行的第6个度量项的代号属性6 @count_of_top_program
- 测试可执行的第7个度量项的代号属性7 @count_of_closed_top_program
- 测试可执行的第8个度量项的代号属性8 @count_of_unclosed_top_program
- 测试可执行的第9个度量项的代号属性9 @count_of_annual_created_top_program
- 测试可执行的第10个度量项的代号属性10 @count_of_annual_closed_top_program
- 测试可执行的第11个度量项的代号属性11 @count_of_line
- 测试可执行的第12个度量项的代号属性12 @count_of_product
- 测试可执行的第13个度量项的代号属性13 @count_of_normal_product

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

r($metric->getExecutableMetric()) && p(1)  && e('count_of_program');                    // 测试可执行的第1个度量项的代号
r($metric->getExecutableMetric()) && p(2)  && e('count_of_doing_program');              // 测试可执行的第2个度量项的代号
r($metric->getExecutableMetric()) && p(3)  && e('count_of_closed_program');             // 测试可执行的第3个度量项的代号
r($metric->getExecutableMetric()) && p(4)  && e('count_of_suspended_program');          // 测试可执行的第4个度量项的代号
r($metric->getExecutableMetric()) && p(5)  && e('count_of_wait_program');               // 测试可执行的第5个度量项的代号
r($metric->getExecutableMetric()) && p(6)  && e('count_of_top_program');                // 测试可执行的第6个度量项的代号
r($metric->getExecutableMetric()) && p(7)  && e('count_of_closed_top_program');         // 测试可执行的第7个度量项的代号
r($metric->getExecutableMetric()) && p(8)  && e('count_of_unclosed_top_program');       // 测试可执行的第8个度量项的代号
r($metric->getExecutableMetric()) && p(9)  && e('count_of_annual_created_top_program'); // 测试可执行的第9个度量项的代号
r($metric->getExecutableMetric()) && p(10) && e('count_of_annual_closed_top_program');  // 测试可执行的第10个度量项的代号
r($metric->getExecutableMetric()) && p(11) && e('count_of_line');                       // 测试可执行的第11个度量项的代号
r($metric->getExecutableMetric()) && p(12) && e('count_of_product');                    // 测试可执行的第12个度量项的代号
r($metric->getExecutableMetric()) && p(13) && e('count_of_normal_product');             // 测试可执行的第13个度量项的代号