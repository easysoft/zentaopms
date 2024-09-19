#!/usr/bin/env php
<?php

/**

title=收藏文档测试
timeout=0

 */
chdir(__DIR__);
include '../lib/lefttree.ui.class.php';

$tester = new createDocTester();
$tester->login();
$docName = new stdClass();
$docName->dcName = '我的文档A';

r($tester->createdByMe($docName)) && p('message,staus') && e('我创建的文档校验成功，SUCESS');  //校验我创建的文档成功
r($tester->myFavorites($docName)) && p('message,status') && e('收藏文档成功,SUCCESS');         //收藏文档成功
