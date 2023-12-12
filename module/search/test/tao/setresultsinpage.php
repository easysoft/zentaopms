#!/usr/bin/env php
<?php

/**

title=测试 searchModel->setResultsInPageTest();
timeout=0
cid=1

- 测试结果集按照每页5条记录时第一页的记录数 @5
- 测试结果集按照每页5条记录时第二页的记录数 @5
- 测试结果集按照每页5条记录时第三页的记录数 @5
- 测试结果集按照每页10条记录时第一页的记录数 @10
- 测试结果集按照每页10条记录时第二页的记录数 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';

su('admin');

zdTable('searchindex')->gen(15);

$recPerPages = array(5, 10);
$pageIDs     = array(1, 2, 3);

$search = new searchTest();
r(count($search->setResultsInPageTest($recPerPages[0], $pageIDs[0]))) && p() && e(5);  //测试结果集按照每页5条记录时第一页的记录数
r(count($search->setResultsInPageTest($recPerPages[0], $pageIDs[1]))) && p() && e(5);  //测试结果集按照每页5条记录时第二页的记录数
r(count($search->setResultsInPageTest($recPerPages[0], $pageIDs[2]))) && p() && e(5);  //测试结果集按照每页5条记录时第三页的记录数
r(count($search->setResultsInPageTest($recPerPages[1], $pageIDs[0]))) && p() && e(10); //测试结果集按照每页10条记录时第一页的记录数
r(count($search->setResultsInPageTest($recPerPages[1], $pageIDs[1]))) && p() && e(5);  //测试结果集按照每页10条记录时第二页的记录数