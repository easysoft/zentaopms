#!/usr/bin/env php
<?php
/**
 * 测试setMenu.
 */
include '../../../test/init.php';
chdir(dirname(__FILE__));
include '../model.php';
$app->user->account = 'test';
$bug = new bugModel();
$bug->setMenu(array(1, 2, 3), 1);
print_r($lang->bug->menu->product);
