#!/usr/bin/env php
<?php
ob_start();
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 commonModel::response();
timeout=0
cid=15706

- 应用不存在，返回错误代码404
 - 属性errcode @404
 - 属性errmsg @应用不存在
- 缺少token参数, 返回错误代码401
 - 属性errcode @401
 - 属性errmsg @缺少token参数
- 用户不存在，返回错误代码406
 - 属性errcode @406
 - 属性errmsg @用户不存在
- 直接返回错误信息属性error @ERROR

*/

global $app, $tester, $config, $lang;
$commonModel = $tester->loadModel('common');

$config->entry = new stdclass();
$config->entry->errcode = array();
$config->entry->errcode['PARAM_CODE_MISSING']    = 401;
$config->entry->errcode['PARAM_TOKEN_MISSING']   = 401;
$config->entry->errcode['SESSION_CODE_MISSING']  = 401;
$config->entry->errcode['EMPTY_KEY']             = 401;
$config->entry->errcode['INVALID_TOKEN']         = 401;
$config->entry->errcode['SESSION_VERIFY_FAILED'] = 401;
$config->entry->errcode['IP_DENIED']             = 403;
$config->entry->errcode['ACCOUNT_UNBOUND']       = 403;
$config->entry->errcode['EMPTY_ENTRY']           = 404;
$config->entry->errcode['CALLED_TIME']           = 405;
$config->entry->errcode['INVALID_ACCOUNT']       = 406;
$config->entry->errcode['ERROR_TIMESTAMP']       = 407;

$lang->entry = new stdclass();
$lang->entry->errmsg = array();
$lang->entry->errmsg['PARAM_CODE_MISSING']    = '缺少code参数';
$lang->entry->errmsg['PARAM_TOKEN_MISSING']   = '缺少token参数';
$lang->entry->errmsg['SESSION_CODE_MISSING']  = '缺少session code';
$lang->entry->errmsg['EMPTY_KEY']             = '应用未设置密钥';
$lang->entry->errmsg['INVALID_TOKEN']         = '无效的token参数';
$lang->entry->errmsg['SESSION_VERIFY_FAILED'] = 'session验证失败';
$lang->entry->errmsg['IP_DENIED']             = '该IP被限制访问';
$lang->entry->errmsg['ACCOUNT_UNBOUND']       = '未绑定用户';
$lang->entry->errmsg['INVALID_ACCOUNT']       = '用户不存在';
$lang->entry->errmsg['EMPTY_ENTRY']           = '应用不存在';
$lang->entry->errmsg['CALLED_TIME']           = 'Token已失效';
$lang->entry->errmsg['ERROR_TIMESTAMP']       = '错误的时间戳。';

$reasonPhrase = 'EMPTY_ENTRY';
try
{
    $commonModel->response($reasonPhrase);
}
catch(EndResponseException $e)
{
    $content = json_decode($e->getContent());
}
r($content) && p('errcode,errmsg') && e('404,应用不存在'); // 应用不存在，返回错误代码404

$reasonPhrase = 'PARAM_TOKEN_MISSING';
try
{
    $commonModel->response($reasonPhrase);
}
catch(EndResponseException $e)
{
    $content = json_decode($e->getContent());
}
r($content) && p('errcode,errmsg') && e('401,缺少token参数'); // 缺少token参数, 返回错误代码401

$reasonPhrase = 'INVALID_ACCOUNT';
try
{
    $commonModel->response($reasonPhrase);
}
catch(EndResponseException $e)
{
    $content = json_decode($e->getContent());
}
r($content) && p('errcode,errmsg') && e('406,用户不存在'); // 用户不存在，返回错误代码406

unset($config->entry->errcode);
$reasonPhrase = 'ERROR';
try
{
    $commonModel->response($reasonPhrase);
}
catch(EndResponseException $e)
{
    $content = json_decode($e->getContent());
}
r($content) && p('error') && e('ERROR'); // 直接返回错误信息