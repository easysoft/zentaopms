#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=获取项目列表;
cid=1
pid=1

获取条目的project和type字段 >> 0,project

*/
global $token;
$projects = $rest->get('/projects', array("Token" => $token));

$project = array(reset($projects->body->projects));
r($project) && p('project,type', ',') && e('0,project'); // 获取条目的project和type字段