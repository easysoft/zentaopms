#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=创建发布页面字段检查
timeout=0
cid=80

*/
chdir(__DIR__);
include '../lib/ui/createrelease.ui.class.php';

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

$system = zenData('system');
$system->id->range('1');
$system->product->range('1');
$system->name->range('应用1');
$system->status->range('active');
$system->integrated->range('0');
$system->createdBy->range('admin');
$system->gen(1);

$release = zenData('release');
$release->gen(0);

$tester = new createReleaseTeaster();
$tester->login();

$releaseName               = '发布';
$releaseStatus             = array();
$releaseStatus['wait']     = '未开始';
$releaseStatus['released'] = '已发布';

r($tester->createRelease($releaseStatus['wait'].$releaseName,     $releaseStatus['wait']))     && p('status,message') && e('SUCCESS,创建未开始发布成功');
r($tester->createRelease($releaseStatus['released'].$releaseName, $releaseStatus['released'])) && p('status,message') && e('SUCCESS,创建已发布发布成功');
$tester->closeBrowser();
