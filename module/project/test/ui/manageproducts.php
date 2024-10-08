#!/usr/bin/env php
<?php

/**
title=项目产品管理
timeout=0
cid=1

- 校验选择产品不能为空测试结果 @关联其他产品必填提示信息正确
- 关联产品测试结果 @关联产品成功
- 取消关联产品测试结果 @取消关联产品成功

 */
chdir(__DIR__);
include '../lib/manageproducts.ui.class.php';

zendata('project')->loadYaml('execution', false, 2)->gen(10);
zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('projectproduct')->loadYaml('projectproduct', false, 1)->gen(5);
$tester = new manageProductsTester();
$tester->login();

$project = array(
    array('otherProducts' => array('multiPicker' => '产品3')),
);

r($tester->linkNoProducts($project)) && p('message') && e('关联其他产品必填提示信息正确'); //校验选择产品不能为空
r($tester->linkProducts($project['0'])) && p('message') && e('关联产品成功');              //关联产品
r($tester->unlinkProducts($project)) && p('message') && e('取消关联产品成功');             //取消关联产品

$tester->closeBrowser();
