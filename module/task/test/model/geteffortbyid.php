#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');
zenData('effort')->gen(11);

/**

title=taskModel->getEffortByID();
timeout=0
cid=18806

- 查询日志为空的情况 @0
- 查询不是最后一次添加的日志
 - 属性work @这是工作内容1
 - 属性isLast @0
- 查询最后添加的日志
 - 属性work @这是工作内容11
 - 属性isLast @1
- 查询不存在的日志 @0

*/

$estimateIdList = array('0', '1', '11', '50');

$task = new taskModelTest();
r($task->getEffortByIDTest($estimateIdList[0])) && p()              && e('0');                // 查询日志为空的情况
r($task->getEffortByIDTest($estimateIdList[1])) && p('work,isLast') && e('这是工作内容1,0');  // 查询不是最后一次添加的日志
r($task->getEffortByIDTest($estimateIdList[2])) && p('work,isLast') && e('这是工作内容11,1'); // 查询最后添加的日志
r($task->getEffortByIDTest($estimateIdList[3])) && p()              && e('0');                // 查询不存在的日志