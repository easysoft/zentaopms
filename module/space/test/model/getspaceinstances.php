#!/usr/bin/env php
<?php
/**

title=测试 spaceModel->getSpaceInstances();
cid=1

- 获取空间ID=0、状态为空、搜索名称为空、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID=0、状态为空、搜索名称为空、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID=0、状态为空、搜索名称为空、每页记录数为10、页码为1的应用列表 @0
- 获取空间ID=0、状态为空、搜索名称为空、每页记录数为10、页码为2的应用列表 @0
- 获取空间ID=0、状态为空、搜索名称为空、每页记录数为20、页码为1的应用列表 @0
- 获取空间ID=0、状态为空、搜索名称为空、每页记录数为20、页码为2的应用列表 @0
- 获取空间ID=0、状态为空、搜索名称为“应用”、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID=0、状态为all、搜索名称为空、每页记录数为5、页码为1的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为all、搜索名称为空、每页记录数为5、页码为2的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为all、搜索名称为空、每页记录数为10、页码为1的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为all、搜索名称为空、每页记录数为10、页码为2的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为all、搜索名称为空、每页记录数为20、页码为1的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为all、搜索名称为空、每页记录数为20、页码为2的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为running、搜索名称为空、每页记录数为5、页码为1的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为running、搜索名称为空、每页记录数为5、页码为2的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为running、搜索名称为空、每页记录数为10、页码为1的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为running、搜索名称为空、每页记录数为10、页码为2的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为running、搜索名称为空、每页记录数为20、页码为1的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为running、搜索名称为空、每页记录数为20、页码为2的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为5、页码为1的应用列表
 - 第2条的space属性 @2
 - 第2条的name属性 @应用2
 - 第2条的appID属性 @2
 - 第2条的version属性 @1
 - 第2条的status属性 @stopped
- 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为5、页码为2的应用列表
 - 第2条的space属性 @2
 - 第2条的name属性 @应用2
 - 第2条的appID属性 @2
 - 第2条的version属性 @1
 - 第2条的status属性 @stopped
- 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为10、页码为1的应用列表
 - 第2条的space属性 @2
 - 第2条的name属性 @应用2
 - 第2条的appID属性 @2
 - 第2条的version属性 @1
 - 第2条的status属性 @stopped
- 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为10、页码为2的应用列表
 - 第2条的space属性 @2
 - 第2条的name属性 @应用2
 - 第2条的appID属性 @2
 - 第2条的version属性 @1
 - 第2条的status属性 @stopped
- 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为20、页码为1的应用列表
 - 第2条的space属性 @2
 - 第2条的name属性 @应用2
 - 第2条的appID属性 @2
 - 第2条的version属性 @1
 - 第2条的status属性 @stopped
- 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为20、页码为2的应用列表
 - 第2条的space属性 @2
 - 第2条的name属性 @应用2
 - 第2条的appID属性 @2
 - 第2条的version属性 @1
 - 第2条的status属性 @stopped
- 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为5、页码为1的应用列表
 - 第3条的space属性 @3
 - 第3条的name属性 @应用3
 - 第3条的appID属性 @3
 - 第3条的version属性 @1
 - 第3条的status属性 @abnormal
- 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为5、页码为2的应用列表
 - 第3条的space属性 @3
 - 第3条的name属性 @应用3
 - 第3条的appID属性 @3
 - 第3条的version属性 @1
 - 第3条的status属性 @abnormal
- 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为10、页码为1的应用列表
 - 第3条的space属性 @3
 - 第3条的name属性 @应用3
 - 第3条的appID属性 @3
 - 第3条的version属性 @1
 - 第3条的status属性 @abnormal
- 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为10、页码为2的应用列表
 - 第3条的space属性 @3
 - 第3条的name属性 @应用3
 - 第3条的appID属性 @3
 - 第3条的version属性 @1
 - 第3条的status属性 @abnormal
- 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为20、页码为1的应用列表
 - 第3条的space属性 @3
 - 第3条的name属性 @应用3
 - 第3条的appID属性 @3
 - 第3条的version属性 @1
 - 第3条的status属性 @abnormal
- 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为20、页码为2的应用列表
 - 第3条的space属性 @3
 - 第3条的name属性 @应用3
 - 第3条的appID属性 @3
 - 第3条的version属性 @1
 - 第3条的status属性 @abnormal
- 获取空间ID=0、状态为test、搜索名称为空、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID=0、状态为test、搜索名称为空、每页记录数为5、页码为2的应用列表 @0
- 获取空间ID=0、状态为test、搜索名称为空、每页记录数为10、页码为1的应用列表 @0
- 获取空间ID=0、状态为test、搜索名称为空、每页记录数为10、页码为2的应用列表 @0
- 获取空间ID=0、状态为test、搜索名称为空、每页记录数为20、页码为1的应用列表 @0
- 获取空间ID=0、状态为test、搜索名称为空、每页记录数为20、页码为2的应用列表 @0
- 获取空间ID=0、状态为all、搜索名称为“ 应用”、每页记录数为5、页码为1的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=1、状态为空、搜索名称为“ 应用”、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID=1、状态为all、搜索名称为空、每页记录数为5、页码为1的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=1、状态为running、搜索名称为空、每页记录数为5、页码为1的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID=1、状态为stopped、搜索名称为空、每页记录数为5、页码为1的应用列表
 - 第11条的space属性 @1
 - 第11条的name属性 @应用11
 - 第11条的appID属性 @1
 - 第11条的version属性 @1
 - 第11条的status属性 @stopped
- 获取空间ID=1、状态为abnormal、搜索名称为空、每页记录数为5、页码为1的应用列表
 - 第6条的space属性 @1
 - 第6条的name属性 @应用6
 - 第6条的appID属性 @1
 - 第6条的version属性 @1
 - 第6条的status属性 @abnormal
- 获取空间ID=1、状态为test、搜索名称为空、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID=1、状态为all、搜索名称为“ 应用”、每页记录数为5、页码为1的应用列表
 - 第1条的space属性 @1
 - 第1条的name属性 @应用1
 - 第1条的appID属性 @1
 - 第1条的version属性 @1
 - 第1条的status属性 @running
- 获取空间ID不存在、状态为空、搜索名称为空、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID不存在、状态为all、搜索名称为空、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID不存在、状态为running、搜索名称为空、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID不存在、状态为stopped、搜索名称为空、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID不存在、状态为abnormal、搜索名称为空、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID不存在、状态为test、搜索名称为空、每页记录数为5、页码为1的应用列表 @0
- 获取空间ID不存在、状态为all、搜索名称为“ 应用”、每页记录数为5、页码为1的应用列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/space.class.php';

zdTable('user')->gen(5);
zdTable('space')->config('space')->gen(5);
zdTable('instance')->config('instance')->gen(20);

$spaceIdList = array(0, 1, 6);
$statusList  = array('', 'all', 'running', 'stopped', 'abnormal', 'test');
$searchNames = array('', '应用');
$recPerPages = array(5, 10, 20);
$pageIdList  = array(1, 2);

$spaceTester = new spaceTest();
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[0], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID=0、状态为空、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[0], $searchNames[0], $recPerPages[0], $pageIdList[1])) && p()                                     && e('0');                    // 获取空间ID=0、状态为空、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[0], $searchNames[0], $recPerPages[1], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID=0、状态为空、搜索名称为空、每页记录数为10、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[0], $searchNames[0], $recPerPages[1], $pageIdList[1])) && p()                                     && e('0');                    // 获取空间ID=0、状态为空、搜索名称为空、每页记录数为10、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[0], $searchNames[0], $recPerPages[2], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID=0、状态为空、搜索名称为空、每页记录数为20、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[0], $searchNames[0], $recPerPages[2], $pageIdList[1])) && p()                                     && e('0');                    // 获取空间ID=0、状态为空、搜索名称为空、每页记录数为20、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[0], $searchNames[1], $recPerPages[0], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID=0、状态为空、搜索名称为“应用”、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[1], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为all、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[1], $searchNames[0], $recPerPages[0], $pageIdList[1])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为all、搜索名称为空、每页记录数为5、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[1], $searchNames[0], $recPerPages[1], $pageIdList[0])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为all、搜索名称为空、每页记录数为10、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[1], $searchNames[0], $recPerPages[1], $pageIdList[1])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为all、搜索名称为空、每页记录数为10、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[1], $searchNames[0], $recPerPages[2], $pageIdList[0])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为all、搜索名称为空、每页记录数为20、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[1], $searchNames[0], $recPerPages[2], $pageIdList[1])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为all、搜索名称为空、每页记录数为20、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[2], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为running、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[2], $searchNames[0], $recPerPages[0], $pageIdList[1])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为running、搜索名称为空、每页记录数为5、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[2], $searchNames[0], $recPerPages[1], $pageIdList[0])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为running、搜索名称为空、每页记录数为10、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[2], $searchNames[0], $recPerPages[1], $pageIdList[1])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为running、搜索名称为空、每页记录数为10、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[2], $searchNames[0], $recPerPages[2], $pageIdList[0])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为running、搜索名称为空、每页记录数为20、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[2], $searchNames[0], $recPerPages[2], $pageIdList[1])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为running、搜索名称为空、每页记录数为20、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[3], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p('2:space,name,appID,version,status')  && e('2,应用2,2,1,stopped');  // 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[3], $searchNames[0], $recPerPages[0], $pageIdList[1])) && p('2:space,name,appID,version,status')  && e('2,应用2,2,1,stopped');  // 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为5、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[3], $searchNames[0], $recPerPages[1], $pageIdList[0])) && p('2:space,name,appID,version,status')  && e('2,应用2,2,1,stopped');  // 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为10、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[3], $searchNames[0], $recPerPages[1], $pageIdList[1])) && p('2:space,name,appID,version,status')  && e('2,应用2,2,1,stopped');  // 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为10、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[3], $searchNames[0], $recPerPages[2], $pageIdList[0])) && p('2:space,name,appID,version,status')  && e('2,应用2,2,1,stopped');  // 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为20、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[3], $searchNames[0], $recPerPages[2], $pageIdList[1])) && p('2:space,name,appID,version,status')  && e('2,应用2,2,1,stopped');  // 获取空间ID=0、状态为stopped、搜索名称为空、每页记录数为20、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[4], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p('3:space,name,appID,version,status')  && e('3,应用3,3,1,abnormal'); // 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[4], $searchNames[0], $recPerPages[0], $pageIdList[1])) && p('3:space,name,appID,version,status')  && e('3,应用3,3,1,abnormal'); // 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为5、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[4], $searchNames[0], $recPerPages[1], $pageIdList[0])) && p('3:space,name,appID,version,status')  && e('3,应用3,3,1,abnormal'); // 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为10、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[4], $searchNames[0], $recPerPages[1], $pageIdList[1])) && p('3:space,name,appID,version,status')  && e('3,应用3,3,1,abnormal'); // 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为10、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[4], $searchNames[0], $recPerPages[2], $pageIdList[0])) && p('3:space,name,appID,version,status')  && e('3,应用3,3,1,abnormal'); // 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为20、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[4], $searchNames[0], $recPerPages[2], $pageIdList[1])) && p('3:space,name,appID,version,status')  && e('3,应用3,3,1,abnormal'); // 获取空间ID=0、状态为abnormal、搜索名称为空、每页记录数为20、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[5], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID=0、状态为test、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[5], $searchNames[0], $recPerPages[0], $pageIdList[1])) && p()                                     && e('0');                    // 获取空间ID=0、状态为test、搜索名称为空、每页记录数为5、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[5], $searchNames[0], $recPerPages[1], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID=0、状态为test、搜索名称为空、每页记录数为10、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[5], $searchNames[0], $recPerPages[1], $pageIdList[1])) && p()                                     && e('0');                    // 获取空间ID=0、状态为test、搜索名称为空、每页记录数为10、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[5], $searchNames[0], $recPerPages[2], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID=0、状态为test、搜索名称为空、每页记录数为20、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[5], $searchNames[0], $recPerPages[2], $pageIdList[1])) && p()                                     && e('0');                    // 获取空间ID=0、状态为test、搜索名称为空、每页记录数为20、页码为2的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[0], $statusList[1], $searchNames[1], $recPerPages[0], $pageIdList[0])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=0、状态为all、搜索名称为“ 应用”、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[1], $statusList[0], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID=1、状态为空、搜索名称为“ 应用”、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[1], $statusList[1], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=1、状态为all、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[1], $statusList[2], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=1、状态为running、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[1], $statusList[3], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p('11:space,name,appID,version,status') && e('1,应用11,1,1,stopped'); // 获取空间ID=1、状态为stopped、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[1], $statusList[4], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p('6:space,name,appID,version,status')  && e('1,应用6,1,1,abnormal'); // 获取空间ID=1、状态为abnormal、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[1], $statusList[5], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID=1、状态为test、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[1], $statusList[1], $searchNames[1], $recPerPages[0], $pageIdList[0])) && p('1:space,name,appID,version,status')  && e('1,应用1,1,1,running');  // 获取空间ID=1、状态为all、搜索名称为“ 应用”、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[2], $statusList[0], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID不存在、状态为空、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[2], $statusList[1], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID不存在、状态为all、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[2], $statusList[2], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID不存在、状态为running、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[2], $statusList[3], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID不存在、状态为stopped、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[2], $statusList[4], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID不存在、状态为abnormal、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[2], $statusList[5], $searchNames[0], $recPerPages[0], $pageIdList[0])) && p()                                     && e('0');                    // 获取空间ID不存在、状态为test、搜索名称为空、每页记录数为5、页码为1的应用列表
r($spaceTester->getSpaceInstancesTest($spaceIdList[2], $statusList[1], $searchNames[1], $recPerPages[0], $pageIdList[0])) && p()                                    && e('0');                     // 获取空间ID不存在、状态为all、搜索名称为“ 应用”、每页记录数为5、页码为1的应用列表
