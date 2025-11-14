#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('user')->gen(2);
zenData('project')->gen(40);
zenData('product')->gen(20, true, false);
zenData('story')->gen(30);

su('admin');

/**

title=bugModel->getBugQuery();
cid=15357
pid=1

*/

$bugQuery = array();
$bugQuery[] = "`product` = 'all'";
$bugQuery[] = "`project` = 'all'";
$bugQuery[] = " `resolvedDate` > '2022-01-01'";
$bugQuery[] = " `closedDate` <= '2022-01-01'";
$bugQuery[] = " `story` LIKE '%2%'";
$bugQuery[] = " `story` NOT LIKE '%2%'";
$bugQuery[] = " `product` = '1' and `project` = '1' and `story` > 'abc'";

$bug=new bugTest();


global $app;
$app->user->view->products = '1,2,3,4,5,6,7,8,9,10';

r($bug->getBugQueryTest($bugQuery[0])) && p()  && e("1 AND `product` IN ('1','2','3','4','5','6','7','8','9','10')"); // 查询 `product` = 'all' 的bugQuery
r($bug->getBugQueryTest($bugQuery[1])) && p()  && e("1 AND `project` in (11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40)"); // 查询 `project` = 'all' 的bugQuery
r($bug->getBugQueryTest($bugQuery[2])) && p()  && e(" `resolvedDate` != '0000-00-00 00:00:00' AND `resolvedDate` > '2022-01-01'");                                     // 查询 `resolvedDate` = '2022-01-01' 的bugQuery
r($bug->getBugQueryTest($bugQuery[3])) && p()  && e(" `closedDate` != '0000-00-00 00:00:00' AND `closedDate` <= '2022-01-01'");                                        // 查询 `closedDate` = '2022-01-01' 的bugQuery
r($bug->getBugQueryTest($bugQuery[4])) && p()  && e("`story`  IN (2,12,20,21,22,23,24,25,26,27,28,29) AND `story` != 0");                                              // 查询 `story` LIKE '%abc%' 的bugQuery
r($bug->getBugQueryTest($bugQuery[5])) && p()  && e("`story` NOT  IN (2,12,20,21,22,23,24,25,26,27,28,29) AND `story` != 0");                                          // 查询 `story` NOT LIKE '%abc%' 的bugQuery
r($bug->getBugQueryTest($bugQuery[6])) && p()  && e("`product` = '1' and `project` = '1' and `story` > 'abc' AND `story` != 0");                                       // 查询 `product` = '1' and `project` = '1' and `story` > 'abc' 的bugQuery
