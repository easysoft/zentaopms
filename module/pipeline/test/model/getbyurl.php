#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getByUrl();
timeout=0
cid=17346

- 测试步骤1：正常查询system用户创建的存在URL记录属性id @201
- 测试步骤2：查询空URL字符串 @0
- 测试步骤3：查询不存在的URL @0
- 测试步骤4：查询存在但非system用户创建的URL记录 @0
- 测试步骤5：查询URL格式变化情况(带尾部斜杠)属性id @202

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
$table = zenData('pipeline');
$table->id->range('201-210');
$table->type->range('gitlab{3},jenkins{3},gitea{2},gogs{2}');
$table->name->range('测试GitLab服务器1,测试GitLab服务器2,测试GitLab服务器3,测试Jenkins服务器1,测试Jenkins服务器2,测试Jenkins服务器3,测试Gitea服务器1,测试Gitea服务器2,测试Gogs服务器1,测试Gogs服务器2');
$table->url->range('https://gitlabdev.qc.oop.cc/,https://test.example.com/,https://demo.gitlab.com,https://jenkins.test.com/,https://ci.example.org,https://build.demo.com/,https://gitea.local.com,https://git.example.net/,https://gogs.test.org/,https://source.demo.net');
$table->account->range('root,admin,test,user,developer,manager,guest,system,staff,operator');
$table->password->range('cGFzc3dvcmQxMjM=,YWRtaW4xMjM=,dGVzdDEyMw==,dXNlcjEyMw==,ZGV2MTIz,bWFuYWdlcjEyMw==,Z3Vlc3QxMjM=,c3lzdGVtMTIz,c3RhZmYxMjM=,b3BlcmF0b3IxMjM=');
$table->token->range('token123,admin_token,test_token,user_token,dev_token,mgr_token,guest_token,sys_token,staff_token,op_token');
$table->private->range('key1,key2,key3,key4,key5,key6,key7,key8,key9,key10');
$table->createdBy->range('system{5},admin{3},user{2}');
$table->createdDate->range('`2024-01-01 10:00:00`');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$pipelineTest = new pipelineModelTest();

r($pipelineTest->getByUrlTest('https://gitlabdev.qc.oop.cc/')) && p('id') && e('201'); // 测试步骤1：正常查询system用户创建的存在URL记录
r($pipelineTest->getByUrlTest('')) && p() && e('0'); // 测试步骤2：查询空URL字符串
r($pipelineTest->getByUrlTest('https://nonexistent.example.com/')) && p() && e('0'); // 测试步骤3：查询不存在的URL
r($pipelineTest->getByUrlTest('https://gitea.local.com')) && p() && e('0'); // 测试步骤4：查询存在但非system用户创建的URL记录
r($pipelineTest->getByUrlTest('https://test.example.com/')) && p('id') && e('202'); // 测试步骤5：查询URL格式变化情况(带尾部斜杠)