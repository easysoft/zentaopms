#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getStoryParams();
cid=1
pid=1

获取需求的参数 >> {"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"assignedTo":"指派给我","openedBy":"由我创建","reviewedBy":"由我评审","closedBy":"由我关闭"},"control":"select"},"orderBy":{"name":"排序","default":"id_desc","options":{"id_asc":"ID 递增","id_desc":"ID 递减","pri_asc":"优先级递增","pri_desc":"优先级递减","status_asc":"状态正序","status_desc":"状态倒序","stage_asc":"阶段正序","stage_desc":"阶段倒序"},"control":"select"}}

*/

$block = new blockTest();

r($block->getStoryParamsTest()) && p() && e('{"count":{"name":"数量","default":20,"control":"input"},"type":{"name":"类型","options":{"assignedTo":"指派给我","openedBy":"由我创建","reviewedBy":"由我评审","closedBy":"由我关闭"},"control":"select"},"orderBy":{"name":"排序","default":"id_desc","options":{"id_asc":"ID 递增","id_desc":"ID 递减","pri_asc":"优先级递增","pri_desc":"优先级递减","status_asc":"状态正序","status_desc":"状态倒序","stage_asc":"阶段正序","stage_desc":"阶段倒序"},"control":"select"}}'); // 获取需求的参数