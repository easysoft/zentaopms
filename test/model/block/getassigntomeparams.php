#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getAssignToMeParams();
cid=1
pid=1

获取当前登陆用户待办的默认参数 >> 待办数,20,input
获取当前登陆用户任务的默认参数 >> 任务数,20,input
获取当前登陆用户Bug的默认参数 >> Bug数,20,input
获取当前登陆用户需求的默认参数 >> 需求数,20,input

*/

$block = new blockTest();
$data  = $block->getAssignToMeParamsTest();

r($data) && p('todoCount:name,default,control')  && e('待办数,20,input'); // 获取当前登陆用户待办的默认参数
r($data) && p('taskCount:name,default,control')  && e('任务数,20,input'); // 获取当前登陆用户任务的默认参数
r($data) && p('bugCount:name,default,control')   && e('Bug数,20,input');  // 获取当前登陆用户Bug的默认参数
r($data) && p('storyCount:name,default,control') && e('需求数,20,input'); // 获取当前登陆用户需求的默认参数