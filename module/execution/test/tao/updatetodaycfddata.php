#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

/**

title=测试 executionModel->updateTodayCFDData();
timeout=0
cid=16400

- 更新迭代1今日需求未开始列无卡片累计流图数据
 - 属性execution @1
 - 属性type @story
- 更新迭代2今日需求未开始列无卡片累计流图数据
 - 属性execution @2
 - 属性type @story
- 更新迭代3今日需求未开始列无卡片累计流图数据
 - 属性execution @3
 - 属性type @story
- 更新迭代1今日Bug未开始列无卡片累计流图数据
 - 属性execution @1
 - 属性type @bug
- 更新迭代2今日Bug未开始列无卡片累计流图数据
 - 属性execution @2
 - 属性type @bug
- 更新迭代3今日Bug未开始列无卡片累计流图数据
 - 属性execution @3
 - 属性type @bug
- 更新迭代1今日任务未开始列无卡片累计流图数据
 - 属性execution @1
 - 属性type @task
- 更新迭代2今日任务未开始列无卡片累计流图数据
 - 属性execution @2
 - 属性type @task
- 更新迭代3今日任务未开始列无卡片累计流图数据
 - 属性execution @3
 - 属性type @task
- 更新迭代1今日需求进行中列无卡片累计流图数据
 - 属性execution @1
 - 属性name @进行中
- 更新迭代2今日需求进行中列无卡片累计流图数据
 - 属性execution @2
 - 属性name @进行中
- 更新迭代3今日需求进行中列无卡片累计流图数据
 - 属性execution @3
 - 属性name @进行中
- 更新迭代1今日Bug未进行中无卡片累计流图数据
 - 属性execution @1
 - 属性name @进行中
- 更新迭代2今日Bug未进行中无卡片累计流图数据
 - 属性execution @2
 - 属性name @进行中
- 更新迭代3今日Bug未进行中无卡片累计流图数据
 - 属性execution @3
 - 属性name @进行中
- 更新迭代1今日任务进行中列无卡片累计流图数据
 - 属性execution @1
 - 属性name @进行中
- 更新迭代2今日任务进行中列无卡片累计流图数据
 - 属性execution @2
 - 属性name @进行中
- 更新迭代3今日任务进行中列无卡片累计流图数据
 - 属性execution @3
 - 属性name @进行中
- 更新迭代1今日需求未开始列有卡片累计流图数据
 - 属性execution @1
 - 属性type @story
- 更新迭代2今日需求未开始列有卡片累计流图数据
 - 属性execution @2
 - 属性type @story
- 更新迭代3今日需求未开始列有卡片累计流图数据
 - 属性execution @3
 - 属性type @story
- 更新迭代1今日Bug未开始列有卡片累计流图数据
 - 属性execution @1
 - 属性type @bug
- 更新迭代2今日Bug未开始列有卡片累计流图数据
 - 属性execution @2
 - 属性type @bug
- 更新迭代3今日Bug未开始列有卡片累计流图数据
 - 属性execution @3
 - 属性type @bug
- 更新迭代1今日任务未开始列有卡片累计流图数据
 - 属性execution @1
 - 属性type @task
- 更新迭代2今日任务未开始列有卡片累计流图数据
 - 属性execution @2
 - 属性type @task
- 更新迭代3今日任务未开始列有卡片累计流图数据
 - 属性execution @3
 - 属性type @task
- 更新迭代1今日需求进行中列有卡片累计流图数据
 - 属性execution @1
 - 属性count @3
- 更新迭代2今日需求进行中列有卡片累计流图数据
 - 属性execution @2
 - 属性count @3
- 更新迭代3今日需求进行中列有卡片累计流图数据
 - 属性execution @3
 - 属性count @3
- 更新迭代1今日Bug未进行中有卡片累计流图数据
 - 属性execution @1
 - 属性count @3
- 更新迭代2今日Bug未进行中有卡片累计流图数据
 - 属性execution @2
 - 属性count @3
- 更新迭代3今日Bug未进行中有卡片累计流图数据
 - 属性execution @3
 - 属性count @3
- 更新迭代1今日任务进行中列有卡片累计流图数据
 - 属性execution @1
 - 属性count @3
- 更新迭代2今日任务进行中列有卡片累计流图数据
 - 属性execution @2
 - 属性count @3
- 更新迭代3今日任务进行中列有卡片累计流图数据
 - 属性execution @3
 - 属性count @3

*/

$executionIdList = array(1, 2, 3);
$typeList        = array('story', 'bug', 'task');
$colName         = array('未开始', '进行中');
$cardIdList      = array('', '1,2,3');

$executionTester = new executionTest();
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[0], $colName[0], $cardIdList[0])) && p('execution,type')  && e('1,story');  // 更新迭代1今日需求未开始列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[0], $colName[0], $cardIdList[0])) && p('execution,type')  && e('2,story');  // 更新迭代2今日需求未开始列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[0], $colName[0], $cardIdList[0])) && p('execution,type')  && e('3,story');  // 更新迭代3今日需求未开始列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[1], $colName[0], $cardIdList[0])) && p('execution,type')  && e('1,bug');    // 更新迭代1今日Bug未开始列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[1], $colName[0], $cardIdList[0])) && p('execution,type')  && e('2,bug');    // 更新迭代2今日Bug未开始列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[1], $colName[0], $cardIdList[0])) && p('execution,type')  && e('3,bug');    // 更新迭代3今日Bug未开始列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[2], $colName[0], $cardIdList[0])) && p('execution,type')  && e('1,task');   // 更新迭代1今日任务未开始列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[2], $colName[0], $cardIdList[0])) && p('execution,type')  && e('2,task');   // 更新迭代2今日任务未开始列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[2], $colName[0], $cardIdList[0])) && p('execution,type')  && e('3,task');   // 更新迭代3今日任务未开始列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[0], $colName[1], $cardIdList[0])) && p('execution,name')  && e('1,进行中'); // 更新迭代1今日需求进行中列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[0], $colName[1], $cardIdList[0])) && p('execution,name')  && e('2,进行中'); // 更新迭代2今日需求进行中列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[0], $colName[1], $cardIdList[0])) && p('execution,name')  && e('3,进行中'); // 更新迭代3今日需求进行中列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[1], $colName[1], $cardIdList[0])) && p('execution,name')  && e('1,进行中'); // 更新迭代1今日Bug未进行中无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[1], $colName[1], $cardIdList[0])) && p('execution,name')  && e('2,进行中'); // 更新迭代2今日Bug未进行中无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[1], $colName[1], $cardIdList[0])) && p('execution,name')  && e('3,进行中'); // 更新迭代3今日Bug未进行中无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[2], $colName[1], $cardIdList[0])) && p('execution,name')  && e('1,进行中'); // 更新迭代1今日任务进行中列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[2], $colName[1], $cardIdList[0])) && p('execution,name')  && e('2,进行中'); // 更新迭代2今日任务进行中列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[2], $colName[1], $cardIdList[0])) && p('execution,name')  && e('3,进行中'); // 更新迭代3今日任务进行中列无卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[0], $colName[0], $cardIdList[1])) && p('execution,type')  && e('1,story');  // 更新迭代1今日需求未开始列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[0], $colName[0], $cardIdList[1])) && p('execution,type')  && e('2,story');  // 更新迭代2今日需求未开始列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[0], $colName[0], $cardIdList[1])) && p('execution,type')  && e('3,story');  // 更新迭代3今日需求未开始列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[1], $colName[0], $cardIdList[1])) && p('execution,type')  && e('1,bug');    // 更新迭代1今日Bug未开始列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[1], $colName[0], $cardIdList[1])) && p('execution,type')  && e('2,bug');    // 更新迭代2今日Bug未开始列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[1], $colName[0], $cardIdList[1])) && p('execution,type')  && e('3,bug');    // 更新迭代3今日Bug未开始列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[2], $colName[0], $cardIdList[1])) && p('execution,type')  && e('1,task');   // 更新迭代1今日任务未开始列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[2], $colName[0], $cardIdList[1])) && p('execution,type')  && e('2,task');   // 更新迭代2今日任务未开始列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[2], $colName[0], $cardIdList[1])) && p('execution,type')  && e('3,task');   // 更新迭代3今日任务未开始列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[0], $colName[1], $cardIdList[1])) && p('execution,count') && e('1,3');      // 更新迭代1今日需求进行中列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[0], $colName[1], $cardIdList[1])) && p('execution,count') && e('2,3');      // 更新迭代2今日需求进行中列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[0], $colName[1], $cardIdList[1])) && p('execution,count') && e('3,3');      // 更新迭代3今日需求进行中列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[1], $colName[1], $cardIdList[1])) && p('execution,count') && e('1,3');      // 更新迭代1今日Bug未进行中有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[1], $colName[1], $cardIdList[1])) && p('execution,count') && e('2,3');      // 更新迭代2今日Bug未进行中有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[1], $colName[1], $cardIdList[1])) && p('execution,count') && e('3,3');      // 更新迭代3今日Bug未进行中有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[0], $typeList[2], $colName[1], $cardIdList[1])) && p('execution,count') && e('1,3');      // 更新迭代1今日任务进行中列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[1], $typeList[2], $colName[1], $cardIdList[1])) && p('execution,count') && e('2,3');      // 更新迭代2今日任务进行中列有卡片累计流图数据
r($executionTester->updateTodayCFDDataTest($executionIdList[2], $typeList[2], $colName[1], $cardIdList[1])) && p('execution,count') && e('3,3');      // 更新迭代3今日任务进行中列有卡片累计流图数据
