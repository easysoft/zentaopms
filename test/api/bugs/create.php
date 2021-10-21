#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试API 添加一个bug
cid=1
pid=1

产品ID检查 >> Need product id.
bug名称必填项检查 >> 『Bug标题』不能为空。
bug版本必填项检查 >> 『影响版本』不能为空。

*/

global $token;
$header = array('token' => $token->token);
$title  = '测试bug' . helper::now();

$data = array(
    'product'        => '1',
    'title'          => $title,
    'openedBuild'    => '1',
    'project'        => '131',
    'module'         => '0',
    'execution'      => '391',
    'assignedTo'     => '',
    'deadline'       => '0000-00-00',
    'type'           => 'codeerror',
    'os'             => '',
    'browser'        => '',
    'color'          => '',
    'severity'       => '1',
    'pri'            => '1',
    'steps'          => '',
    'story'          => '0',
    'task'           => '0',
    'mailto'         => '',
    'keywords'       => '',
    'status'         => 'active',
    'case'           => '0',
    'caseVersion'    => '0',
    'result'         => '0',
    'testtask'       => '0',
);

$bug = $rest->post('/bugs', $data, $header);
$bug->body = array($bug->body);

$noNameData = $data;
unset($noNameData['title']);
$noNameBug = $rest->post('/bugs', $noNameData, $header);

$noBuildData = $data;
unset($noBuildData['openedBuild']);
$noBuildBug = $rest->post('/bugs', $noBuildData, $header);

unset($data['product']);
$noProductBug = $rest->post('/bugs', $data, $header);

r($noProductBug) && c(400) && p('error') && e('Need product id.');       // 产品ID检查
r($noNameBug)    && c(400) && p('error') && e('『Bug标题』不能为空。');  // bug名称必填项检查
r($noBuildBug)   && c(400) && p('error') && e('『影响版本』不能为空。'); // bug版本必填项检查

r($bug) && c(201) && p('title') && e($title); // 创建一个bug