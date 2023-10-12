#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

su('admin');

zdTable('project')->config('execution')->gen(9);

/**

title=测试 actionModel->read();
cid=1
pid=1

获取id为1,2,3的stage >> 1,stage,1;2,stage,1;3,stage,1
获取id为4,5,6的stage >> 4,stage,1;5,stage,1;6,stage,1
获取id为7,8,9的stage >> 7,stage,1;8,stage,1;9,stage,1

*/

$action = new actionTest();

$idList = array(array(1, 2, 3), array(4, 5, 6), array(7, 8, 9));

r($action->getDeletedStagedByListTest($idList[0]))  && p('1:id,type,deleted;2:id,type,deleted;3:id,type,deleted') && e('1,stage,1;2,stage,1;3,stage,1');  //获取id为1,2,3的stage
r($action->getDeletedStagedByListTest($idList[1]))  && p('4:id,type,deleted;5:id,type,deleted;6:id,type,deleted') && e('4,stage,1;5,stage,1;6,stage,1');  //获取id为4,5,6的stage
r($action->getDeletedStagedByListTest($idList[2]))  && p('7:id,type,deleted;8:id,type,deleted;9:id,type,deleted') && e('7,stage,1;8,stage,1;9,stage,1');  //获取id为7,8,9的stage
