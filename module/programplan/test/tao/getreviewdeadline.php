#!/usr/bin/env php
<?php

/**

title=测试 programplanTao::getReviewDeadline();
timeout=0
cid=17771

- 执行programplanTest模块的getReviewDeadlineTest方法，参数是''  @0
- 执行programplanTest模块的getReviewDeadlineTest方法，参数是'2023-12-29'  @2023-12-22
- 执行programplanTest模块的getReviewDeadlineTest方法，参数是'2023-12-29', 6  @2023-12-21
- 执行programplanTest模块的getReviewDeadlineTest方法，参数是'2023-12-29', 0  @2023-12-29
- 执行programplanTest模块的getReviewDeadlineTest方法，参数是'2023-12-29', 10  @2023-12-15
- 执行programplanTest模块的getReviewDeadlineTest方法，参数是'2024-01-02', 5  @2023-12-26
- 执行programplanTest模块的getReviewDeadlineTest方法，参数是'2024-01-02', 1  @2024-01-01

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

su('admin');

$programplanTest = new programplanTest();

r($programplanTest->getReviewDeadlineTest('')) && p() && e('0');
r($programplanTest->getReviewDeadlineTest('2023-12-29')) && p() && e('2023-12-22');
r($programplanTest->getReviewDeadlineTest('2023-12-29', 6)) && p() && e('2023-12-21');
r($programplanTest->getReviewDeadlineTest('2023-12-29', 0)) && p() && e('2023-12-29');
r($programplanTest->getReviewDeadlineTest('2023-12-29', 10)) && p() && e('2023-12-15');
r($programplanTest->getReviewDeadlineTest('2024-01-02', 5)) && p() && e('2023-12-26');
r($programplanTest->getReviewDeadlineTest('2024-01-02', 1)) && p() && e('2024-01-01');