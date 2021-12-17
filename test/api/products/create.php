#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试创建产品;
cid=1
pid=1

测试创建所属项目集为1的产品 >> CESHI1,CESHI1
测试创建独立产品 >> 0
测试产品名称为空的情况 >> 『产品名称』不能为空。
测试产品代号为空的情况 >> 『产品代号』不能为空。
测试状态码 >> 400
测试产品名称已存在 >> 『产品名称』已经有『CESHI1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。
测试状态码 >> 400
测试产品代号已存在 >> 『产品代号』已经有『CESHI1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。

*/
global $token;
$header = array('Token' => $token->token);

$pass = $rest->post('/products', array('name' => 'CESHI1', 'program' => '1', 'code' => 'CESHI1'), $header);
r($pass) && c(200) && p('name,code') && e('CESHI1,CESHI1'); //测试创建所属项目集为1的产品

$noProgram = $rest->post('/products', array('name' => 'CESHI2', 'code' => 'CESHI2'), $header);
r($noProgram) && c(200) && p('program') && e('0'); //测试创建独立产品

$emptyName = $rest->post('/products', array(), $header);
r($emptyName) && c(400) && p('error') && e('『产品名称』不能为空。'); //测试产品名称为空的情况

$emptyCode = $rest->post('/products', array('name' => 'CESHI3'), $header);
r($emptyCode) && c(400) && p('error') && e('『产品代号』不能为空。'); //测试产品代号为空的情况

$existedName = $rest->post('/products', array('name' => 'CESHI1', 'code' => 'CESHI4'), $header);
r($existedName->status_code) && p() && e('400'); //测试状态码
r($existedName->body->error) && p('name:0') && e('『产品名称』已经有『CESHI1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); //测试产品名称已存在

$existedCode = $rest->post('/products', array('name' => 'CESHI4','code' => 'CESHI1'), $header);
r($existedCode->status_code) && p() && e('400'); //测试状态码
r($existedCode->body->error) && p('code:0') && e('『产品代号』已经有『CESHI1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); //测试产品代号已存在