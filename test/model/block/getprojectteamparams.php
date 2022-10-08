#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getProjectTeamParams();
cid=1
pid=1

测试获取项目团队 >> type:{name:类型,all=>所有,undone=>未完成,wait=>未开始,doing=>进行中,suspended=>已挂起,closed=>已关闭,control:select};orderBy:{name:排序,id_asc=>ID 递增,id_desc=>ID 递减,status_asc=>状态正序,status_desc=>状态倒序,control:select};count:{name:数量,default:20,control:input};

*/

$block = new blockTest();

r($block->getProjectTeamParamsTest()) && p() && e('type:{name:类型,all=>所有,undone=>未完成,wait=>未开始,doing=>进行中,suspended=>已挂起,closed=>已关闭,control:select};orderBy:{name:排序,id_asc=>ID 递增,id_desc=>ID 递减,status_asc=>状态正序,status_desc=>状态倒序,control:select};count:{name:数量,default:20,control:input};'); // 测试获取项目团队
