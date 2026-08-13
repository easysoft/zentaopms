#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::merge();
timeout=0
cid=0

- 执行ppmModel模块的mergeTest方法，参数是81, 'merge'  @0
- 执行ppmModel模块的mergeTest方法，参数是81, 'fast'  @0
- 执行ppmModel模块的mergeTest方法，参数是81, 'rebase'  @0
- 执行ppmModel模块的mergeTest方法，参数是81, 'merge', true  @0
- 执行ppmModel模块的mergeTest方法，参数是81, 'merge', false, true  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('42');
$repo->spaceID->range('1');
$repo->product->range('1');
$repo->name->range('ppm-repo-42');
$repo->scmType->range('git');
$repo->acl->range('private');
$repo->gen(1);

$ppm = zenData('ops_ppm')->loadYaml('ops_ppm', false, 2);
$ppm->id->range('81');
$ppm->title->range('API PPM 81');
$ppm->repoID->range('42');
$ppm->sourceRepoID->range('42');
$ppm->sourceBranch->range('feature/bbb');
$ppm->sourceSHA->range('6bada47137f46e3b6b3792b397b714e3726e6990');
$ppm->targetRepoID->range('42');
$ppm->targetBranch->range('main');
$ppm->mergeSHA->range('4444444444444444444444444444444444444444');
$ppm->status->range('opened');
$ppm->createdBy->range('admin');
$ppm->gen(1);

su('admin');

$ppmModel = new ppmModelTest();
$gitfox   = $ppmModel->instance->loadModel('gitfox');
$reflect  = new ReflectionObject($gitfox);
$repos    = $reflect->getProperty('repos');
$repos->setAccessible(true);
$cache = $repos->getValue($gitfox);
$cache[42] = (object)array('id' => 42, 'path' => 'space/ppm-repo-42', 'gitURL' => 'http://gitfox.local/space/ppm-repo-42.git');
$repos->setValue($gitfox, $cache);

r($ppmModel->mergeTest(81, 'merge')) && p() && e('0');
r($ppmModel->mergeTest(81, 'fast')) && p() && e('0');
r($ppmModel->mergeTest(81, 'rebase')) && p() && e('0');
r($ppmModel->mergeTest(81, 'merge', true)) && p() && e('0');
r($ppmModel->mergeTest(81, 'merge', false, true)) && p() && e('0');