#!/usr/bin/env php
<?php

/**

title=测试 searchTao::setResultsInPage();
timeout=0
cid=18345

- 测试结果集按照每页5条记录时第1页的记录数 @5
- 测试结果集按照每页5条记录时第2页的记录数 @5
- 测试结果集按照每页5条记录时第3页的记录数 @5
- 测试结果集按照每页10条记录时第1页的记录数 @10
- 测试结果集按照每页10条记录时第2页的记录数 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

zenData('searchindex')->gen(15);

$search = new searchTaoTest();
r(count($search->setResultsInPageTest(5, 1))) && p() && e(5);  //测试结果集按照每页5条记录时第1页的记录数
r(count($search->setResultsInPageTest(5, 2))) && p() && e(5);  //测试结果集按照每页5条记录时第2页的记录数
r(count($search->setResultsInPageTest(5, 3))) && p() && e(5);  //测试结果集按照每页5条记录时第3页的记录数
r(count($search->setResultsInPageTest(10, 1))) && p() && e(10); //测试结果集按照每页10条记录时第1页的记录数
r(count($search->setResultsInPageTest(10, 2))) && p() && e(5);  //测试结果集按照每页10条记录时第2页的记录数