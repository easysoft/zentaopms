#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/browse.ui.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);

$tester = new browseTester();
$tester->login();
$planurl['productID'] = 1;
r($tester->switchBrowseType($planurl, 'kanban')) && p('message,status') && e('成功切换到看板模式,SUCCESS');//切换到看板模式
r($tester->switchBrowseType($planurl, 'list'))   && p('message,status') && e('成功切换到列表模式,SUCCESS');//切换到列表模式
$tester->closeBrowser();
