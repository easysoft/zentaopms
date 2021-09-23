#!/usr/bin/env php
<?php
/**
title=测试 branchModel::getById();
cid=1
pid=1
*/

include dirname(dirname(__DIR__)) . '/lib/init.php';

$branch = $tester->loadModel('branch');

/* group: 1. */
r($branch->getByID(1)) && p('name') && e('A'); // step: 1.1 使用branchID获取一个存在的分支. >>
