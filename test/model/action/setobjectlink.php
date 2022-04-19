#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->setObjectLink();
cid=1
pid=1

测试获取action 1 的objectLink >> 1,model/action/product-view-1.
测试获取action 2 的objectLink >> 2,model/action/story-view-2.
测试获取action 3 的objectLink >> 3,model/action/productplan-view-3.
测试获取action 4 的objectLink >> 4,model/action/release-view-4.
测试获取action 5 的objectLink >> 5,model/action/project-index-5.
测试获取action 6 的objectLink >> 6,model/action/task-view-6.
测试获取action 7 的objectLink >> 7,model/action/build-view-7.
测试获取action 8 的objectLink >> 8,model/action/bug-view-8.
测试获取action 9 的objectLink >> 9,
测试获取action 10 的objectLink >> 10,model/action/testcase-view-10.
测试获取action 11 的objectLink >> 11,model/action/testtask-view-11.
测试获取action 12 的objectLink >> 12,javascript:void(0)
测试获取action 13 的objectLink >> 13,model/action/doc-view-13.
测试获取action 14 的objectLink >> 14,model/action/doc-objectLibs-product-14-14.
测试获取action 15 的objectLink >> 15,model/action/todo-view-15.
测试获取action 16 的objectLink >> 16,
测试获取action 17 的objectLink >> 17,
测试获取action 18 的objectLink >> 18,model/action/testsuite-view-18.
测试获取action 19 的objectLink >> 19,model/action/caselib-view-19.
测试获取action 20 的objectLink >> 20,model/action/testreport-view-20.
测试获取action 21 的objectLink >> 21,model/action/entry-browse.
测试获取action 22 的objectLink >> 22,model/action/webhook-browse.
测试获取action 23 的objectLink >> 23,
测试获取action 24 的objectLink >> 24,model/action/product-view-24.
测试获取action 25 的objectLink >> 25,model/action/story-view-25.
测试获取action 26 的objectLink >> 26,model/action/productplan-view-26.
测试获取action 27 的objectLink >> 27,model/action/release-view-27.
测试获取action 28 的objectLink >> 28,model/action/project-index-28.
测试获取action 29 的objectLink >> 29,model/action/task-view-29.
测试获取action 30 的objectLink >> 30,model/action/build-view-30.
测试获取action 31 的objectLink >> 31,model/action/bug-view-31.
测试获取action 32 的objectLink >> 32,
测试获取action 33 的objectLink >> 33,model/action/testcase-view-33.
测试获取action 34 的objectLink >> 34,model/action/testtask-view-34.
测试获取action 35 的objectLink >> 35,javascript:void(0)
测试获取action 36 的objectLink >> 36,model/action/doc-view-36.
测试获取action 37 的objectLink >> 37,model/action/doc-objectLibs-product-37-37.
测试获取action 38 的objectLink >> 38,model/action/todo-view-38.
测试获取action 39 的objectLink >> 39,

*/

$actionIDList = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39);

$action = new actionTest();

r($action->setObjectLinkTest($actionIDList[0]))  && p('id,objectLink') && e("1,model/action/product-view-1.");                // 测试获取action 1 的objectLink
r($action->setObjectLinkTest($actionIDList[1]))  && p('id,objectLink') && e("2,model/action/story-view-2.");                  // 测试获取action 2 的objectLink
r($action->setObjectLinkTest($actionIDList[2]))  && p('id,objectLink') && e("3,model/action/productplan-view-3.");            // 测试获取action 3 的objectLink
r($action->setObjectLinkTest($actionIDList[3]))  && p('id,objectLink') && e("4,model/action/release-view-4.");                // 测试获取action 4 的objectLink
r($action->setObjectLinkTest($actionIDList[4]))  && p('id,objectLink') && e("5,model/action/project-index-5.");               // 测试获取action 5 的objectLink
r($action->setObjectLinkTest($actionIDList[5]))  && p('id,objectLink') && e("6,model/action/task-view-6.");                   // 测试获取action 6 的objectLink
r($action->setObjectLinkTest($actionIDList[6]))  && p('id,objectLink') && e("7,model/action/build-view-7.");                  // 测试获取action 7 的objectLink
r($action->setObjectLinkTest($actionIDList[7]))  && p('id,objectLink') && e("8,model/action/bug-view-8.");                    // 测试获取action 8 的objectLink
r($action->setObjectLinkTest($actionIDList[8]))  && p('id,objectLink') && e("9,");                                        // 测试获取action 9 的objectLink
r($action->setObjectLinkTest($actionIDList[9]))  && p('id,objectLink') && e("10,model/action/testcase-view-10.");             // 测试获取action 10 的objectLink
r($action->setObjectLinkTest($actionIDList[10])) && p('id,objectLink') && e("11,model/action/testtask-view-11.");             // 测试获取action 11 的objectLink
r($action->setObjectLinkTest($actionIDList[11])) && p('id,objectLink') && e("12,javascript:void(0)");                     // 测试获取action 12 的objectLink
r($action->setObjectLinkTest($actionIDList[12])) && p('id,objectLink') && e("13,model/action/doc-view-13.");                  // 测试获取action 13 的objectLink
r($action->setObjectLinkTest($actionIDList[13])) && p('id,objectLink') && e("14,model/action/doc-objectLibs-product-14-14."); // 测试获取action 14 的objectLink
r($action->setObjectLinkTest($actionIDList[14])) && p('id,objectLink') && e("15,model/action/todo-view-15.");                 // 测试获取action 15 的objectLink
r($action->setObjectLinkTest($actionIDList[15])) && p('id,objectLink') && e("16,");                                       // 测试获取action 16 的objectLink
r($action->setObjectLinkTest($actionIDList[16])) && p('id,objectLink') && e("17,");                                       // 测试获取action 17 的objectLink
r($action->setObjectLinkTest($actionIDList[17])) && p('id,objectLink') && e("18,model/action/testsuite-view-18.");            // 测试获取action 18 的objectLink
r($action->setObjectLinkTest($actionIDList[18])) && p('id,objectLink') && e("19,model/action/caselib-view-19.");              // 测试获取action 19 的objectLink
r($action->setObjectLinkTest($actionIDList[19])) && p('id,objectLink') && e("20,model/action/testreport-view-20.");           // 测试获取action 20 的objectLink
r($action->setObjectLinkTest($actionIDList[20])) && p('id,objectLink') && e("21,model/action/entry-browse.");                 // 测试获取action 21 的objectLink
r($action->setObjectLinkTest($actionIDList[21])) && p('id,objectLink') && e("22,model/action/webhook-browse.");               // 测试获取action 22 的objectLink
r($action->setObjectLinkTest($actionIDList[22])) && p('id,objectLink') && e("23,");                                       // 测试获取action 23 的objectLink
r($action->setObjectLinkTest($actionIDList[23])) && p('id,objectLink') && e("24,model/action/product-view-24.");              // 测试获取action 24 的objectLink
r($action->setObjectLinkTest($actionIDList[24])) && p('id,objectLink') && e("25,model/action/story-view-25.");                // 测试获取action 25 的objectLink
r($action->setObjectLinkTest($actionIDList[25])) && p('id,objectLink') && e("26,model/action/productplan-view-26.");          // 测试获取action 26 的objectLink
r($action->setObjectLinkTest($actionIDList[26])) && p('id,objectLink') && e("27,model/action/release-view-27.");              // 测试获取action 27 的objectLink
r($action->setObjectLinkTest($actionIDList[27])) && p('id,objectLink') && e("28,model/action/project-index-28.");             // 测试获取action 28 的objectLink
r($action->setObjectLinkTest($actionIDList[28])) && p('id,objectLink') && e("29,model/action/task-view-29.");                 // 测试获取action 29 的objectLink
r($action->setObjectLinkTest($actionIDList[29])) && p('id,objectLink') && e("30,model/action/build-view-30.");                // 测试获取action 30 的objectLink
r($action->setObjectLinkTest($actionIDList[30])) && p('id,objectLink') && e("31,model/action/bug-view-31.");                  // 测试获取action 31 的objectLink
r($action->setObjectLinkTest($actionIDList[31])) && p('id,objectLink') && e("32,");                                       // 测试获取action 32 的objectLink
r($action->setObjectLinkTest($actionIDList[32])) && p('id,objectLink') && e("33,model/action/testcase-view-33.");             // 测试获取action 33 的objectLink
r($action->setObjectLinkTest($actionIDList[33])) && p('id,objectLink') && e("34,model/action/testtask-view-34.");             // 测试获取action 34 的objectLink
r($action->setObjectLinkTest($actionIDList[34])) && p('id,objectLink') && e("35,javascript:void(0)");                     // 测试获取action 35 的objectLink
r($action->setObjectLinkTest($actionIDList[35])) && p('id,objectLink') && e("36,model/action/doc-view-36.");                  // 测试获取action 36 的objectLink
r($action->setObjectLinkTest($actionIDList[36])) && p('id,objectLink') && e("37,model/action/doc-objectLibs-product-37-37."); // 测试获取action 37 的objectLink
r($action->setObjectLinkTest($actionIDList[37])) && p('id,objectLink') && e("38,model/action/todo-view-38.");                 // 测试获取action 38 的objectLink
r($action->setObjectLinkTest($actionIDList[38])) && p('id,objectLink') && e("39,");                                       // 测试获取action 39 的objectLink