#!/usr/bin/env php
<?php
/**

title=测试 designModel->getCommit();
cid=1

- 测试空数据 @0
- 测试未关联提交记录的设计 @0
- 测试查询每页5项第一页的提交记录 @1;2;3;4;5;
- 测试查询每页5项第二页的提交记录 @6;7;8;9;10;
- 测试查询每页10项第一页的提交记录 @1;2;3;4;5;6;7;8;9;10;
- 测试查询每页10项第二页的提交记录 @11;12;13;14;15;16;17;18;19;20;
- 测试查询每页20项第一页的提交记录 @1;2;3;4;5;6;7;8;9;10;11;12;13;14;15;16;17;18;19;20;
- 测试查询每页20项第二页的提交记录 @1;2;3;4;5;6;7;8;9;10;11;12;13;14;15;16;17;18;19;20;
- 测试设计数据不存在时的提交记录 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/design.class.php';

$designTable = zdTable('design')->config('design');
$designTable->commit->range('[]{2},`1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20`');
$designTable->gen(3);

zdTable('repohistory')->config('repohistory')->gen(20);
zdTable('user')->gen(5);

$idList      = array(0, 1, 3, 4);
$recPerPages = array(5, 10, 20);
$pageIDs     = array(1, 2);

$designTester = new designTest();
r($designTester->getCommitTest($idList[0], $recPerPages[0], $pageIDs[0])) && p() && e('0');                                                   // 测试空数据
r($designTester->getCommitTest($idList[1], $recPerPages[0], $pageIDs[0])) && p() && e('0');                                                   // 测试未关联提交记录的设计
r($designTester->getCommitTest($idList[2], $recPerPages[0], $pageIDs[0])) && p() && e('1;2;3;4;5;');                                          // 测试查询每页5项第一页的提交记录
r($designTester->getCommitTest($idList[2], $recPerPages[0], $pageIDs[1])) && p() && e('6;7;8;9;10;');                                         // 测试查询每页5项第二页的提交记录
r($designTester->getCommitTest($idList[2], $recPerPages[1], $pageIDs[0])) && p() && e('1;2;3;4;5;6;7;8;9;10;');                               // 测试查询每页10项第一页的提交记录
r($designTester->getCommitTest($idList[2], $recPerPages[1], $pageIDs[1])) && p() && e('11;12;13;14;15;16;17;18;19;20;');                      // 测试查询每页10项第二页的提交记录
r($designTester->getCommitTest($idList[2], $recPerPages[2], $pageIDs[0])) && p() && e('1;2;3;4;5;6;7;8;9;10;11;12;13;14;15;16;17;18;19;20;'); // 测试查询每页20项第一页的提交记录
r($designTester->getCommitTest($idList[2], $recPerPages[2], $pageIDs[1])) && p() && e('1;2;3;4;5;6;7;8;9;10;11;12;13;14;15;16;17;18;19;20;'); // 测试查询每页20项第二页的提交记录
r($designTester->getCommitTest($idList[3], $recPerPages[0], $pageIDs[0])) && p() && e('0');                                                   // 测试设计数据不存在时的提交记录
