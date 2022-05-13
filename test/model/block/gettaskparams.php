#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getTaskParams();
cid=1
pid=1

获取task的参数 >> {"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"assignedTo":"指派给我","openedBy":"由我创建","finishedBy":"由我完成","closedBy":"由我关闭","canceledBy":"由我取消"},"control":"select"},"orderBy":{"name":"排序","default":"id_desc","options":{"id_asc":"ID 递增","id_desc":"ID 递减","pri_asc":"优先级递增","pri_desc":"优先级递减","estimate_asc":"预计时间递增","estimate_desc":"预计时间递减","status_asc":"状态正序","status_desc":"状态倒序","deadline_asc":"截止日期递增","deadline_desc":"截止日期递减"},"control":"select"}}

*/

$block = new blockTest();

r($block->getTaskParamsTest()) && p() && e('{"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"assignedTo":"指派给我","openedBy":"由我创建","finishedBy":"由我完成","closedBy":"由我关闭","canceledBy":"由我取消"},"control":"select"},"orderBy":{"name":"排序","default":"id_desc","options":{"id_asc":"ID 递增","id_desc":"ID 递减","pri_asc":"优先级递增","pri_desc":"优先级递减","estimate_asc":"预计时间递增","estimate_desc":"预计时间递减","status_asc":"状态正序","status_desc":"状态倒序","deadline_asc":"截止日期递增","deadline_desc":"截止日期递减"},"control":"select"}}'); // 获取task的参数