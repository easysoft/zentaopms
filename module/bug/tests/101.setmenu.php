#!/usr/bin/env php
<?php
<<<TC
title: testing the setMenu() method.
TC;

include '../../../test/init.php';
include '../model.php';
$app->user = new stdclass();
$app->user->account = 'test';
$bug = new bugModel();
$bug->setMenu(array(1, 2, 3), 1);
print_r($lang->bug->menu->product);
