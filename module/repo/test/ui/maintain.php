#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/ui/repo.ui.class.php';

$repo = zenData('repo')->loadYaml('repo', false, 2)->gen(5);
$tester = new repo();
r($tester->maintain()) && p('message,status') && e('代码库列表展示成功,SUCCESS'); //检查代码库页面
