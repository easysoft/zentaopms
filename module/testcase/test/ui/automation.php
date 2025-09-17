#!/usr/bin/env php
<?php
chdir(__DIR__);

/**

title=自动化设置测试
timeout=0
cid=1

- 验证自动化测试设置
 - 测试结果 @验证自动化测试设置成功
 - 最终测试状态 @SUCCESS

*/
include '../lib/ui/testcase.ui.class.php';
$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('0');
$product->bind->range('0');
$product->acl->range('open');
$product->createdBy->range('admin');
$product->vision->range('rnd');
$product->gen(1);

$story = zenData('story');
$story->id->range('1-2');
$story->root->range('1-2');
$story->path->range('`,1,`, `,2,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->title->range('激活研发需求,激活用户需求,激活业务需求');
$story->type->range('story,requirement,epic');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(2);

$hostName = 'testHost';
$host = zenData('host');
$host->id->range('1');
$host->name->range($hostName);
$host->type->range('node');
$host->hostType->range('physics');
$host->deleted->range('0');
$host->gen(1);

$tester = new testcase();

$url = array(
    'productID' => 1
);

$automation = array(
    'node'       => $hostName,
    'scriptPath' => '/var/www/html/bin',
    'shell'      => '/var/www/html/bin/xxcPackage.sh'
);
r($tester->automation($url, $automation)) && p('message,status') && e('验证自动化测试设置成功,SUCCESS'); //验证自动化测试设置
$tester->closeBrowser();