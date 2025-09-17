#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/ui/repo.ui.class.php';

$tester = new repo();
$maintain = array(
    'SCM'            => 'GitFox',
    'serviceHost'    => 'GitFox',
    'serviceProject' => 'php-demo',
    'product'        => 'devops专属产品',
    'desc'           => '一句话描述test',
    'acl'            => 'aclPrivate'
);
r($tester->createMaintain($maintain)) && p('message,status') && e('关联GitFox代码库成功,SUCCESS'); //验证关联GitFox代码库
