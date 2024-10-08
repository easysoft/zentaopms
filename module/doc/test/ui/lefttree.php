#!/usr/bin/env php
<?php

/**

title=收藏文档测试
timeout=0
cid=0

- 收藏文档成功
 - 测试结果 @收藏文档成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/lefttree.ui.class.php';

$tester = new createDocTester();
$tester->login();

$docName = new stdClass();
$docName->dcName = '我的文档A';

r($tester->myFavorites($docName)) && p('message,status') && e('收藏文档成功,SUCCESS'); //收藏文档成功