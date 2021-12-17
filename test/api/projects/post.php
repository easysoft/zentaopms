#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=创建项目;
cid=1
pid=1

 >> Y-m-d
创建失败，没有name字段 >> `『项目名称』`
创建失败，没有code字段 >> `『项目代号』`
创建失败，没有end字段 >> `『计划完成』`
创建成功，获取创建的name和code字段 >> test111,test222
创建失败，获取错误信息 >> `『test111』`

*/
global $token;

$postData = array();
$postData['parent']      = '0';
$postData['name']        = 'test111';
$postData['code']        = '';
$postData['PM']          = '';
$postData['budget']      = '';
$postData['budgetUnit']  = 'CNY';
$postData['begin']       = date('Y-m-d');
$postData['end']         = date('Y-m-d', time() + 10 * 24 * 3600);
$postData['days']        = '10';
$postData['acl']         = 'private';
$postData['auth']        = 'extend';
$postData['model']       = 'scrum';
$postData['products'][]  = 1;
$postData['plan'][]      = '';
$postData['whitelist'][] = '';

$postData['name'] = '';
$postData['code'] = 'test222';
$noNameError = $rest->post('/projects', $postData, array("Token" => $token));
$noNameError = $noNameError->body->error->name[0];

$postData['name'] = 'test111';
$postData['code'] = '';
$noCodeError = $rest->post('/projects', $postData, array("Token" => $token));
$noCodeError = $noCodeError->body->error->code[0];

$postData['code'] = 'test222';
$postData['end']  = '';
$noEndError = $rest->post('/projects', $postData, array("Token" => $token));
$noEndError = $noEndError->body->error->end[0];

$postData['end'] = date('Y-m-d', time() + 10 * 24 * 3600);
$project = $rest->post('/projects', $postData, array("Token" => $token));
$error   = $rest->post('/projects', $postData, array("Token" => $token));
$error   = $error->body->error->name[0];

r($noNameError)         && p('error')          && e('`『项目名称』`');  // 创建失败，没有name字段
r($noCodeError)         && p('error')          && e('`『项目代号』`');  // 创建失败，没有code字段
r($noEndError)          && p('error')          && e('`『计划完成』`');  // 创建失败，没有end字段
r($project) && c('201') && p('name,code', ',') && e('test111,test222'); // 创建成功，获取创建的name和code字段
r($error)               && p('error')          && e('`『test111』`');   // 创建失败，获取错误信息