#!/usr/bin/env php
<?php

chdir(__DIR__);
include '../lib/createdoc.ui.class.php';

$tester = new createDocTester();
$tester->login();

$draftName = new stdClass();
$draftName->nullName = '';
$draftName->dftName  = '我的草稿文档1';

$docName = new stdClass();
$docName->dcName = '我的文档1';

r($tester->createDraft($draftName)) && p('message,status') && e('创建草稿表单页提示信息正确,SUCCESS');
r($tester->createDoc($docName))     && p('message,status') && e('创建文档表单页提示信息正确,SUCCESS');