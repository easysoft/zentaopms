#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->getByID();
timeout=0
cid=1

- 判断项目阶段1的name是否为项目1
 - 属性name @项目1
 - 属性code @project1

- 判断项目阶段2的name是否为项目2
 - 属性name @项目2
 - 属性code @project2

- 判断项目阶段3的type是否为project属性type @project

- 判断项目阶段1的milestone,setMilestone在另外一种情况下是否为0
 - 属性milestone @0
 - 属性setMilestone @0



*/

function initData()
{
    zdTable('project')->config('getbyid')->gen(5);
}

initData();

$planIDList = array('1', '2' ,'3' ,'4', '5');

$programplan = new programplanTest();

r($programplan->getByIDTest($planIDList[0])) && p('name,code') && e('项目1,project1'); // 判断项目阶段1的name是否为项目1
r($programplan->getByIDTest($planIDList[1])) && p('name,code') && e('项目2,project2'); // 判断项目阶段2的name是否为项目2
r($programplan->getByIDTest($planIDList[2])) && p('type') && e('project');             // 判断项目阶段3的type是否为project
r($programplan->getByIDTest($planIDList[0])) && p('milestone,setMilestone') && e('0,0');  // 判断项目阶段1的milestone,setMilestone在另外一种情况下是否为0