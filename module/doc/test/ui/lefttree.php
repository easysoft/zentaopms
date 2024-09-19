#!/usr/bin/env php
<?php

/**

title=收藏文档测试
timeout=0

- 创建文档，显示在我创建的文档列表中
 - 测试结果 @检查我创建的文档列表，检验成功
 - 最终测试状态 @SUCCESS
- 收藏文档，收藏成功
 - 测试结果 @检查收藏文档成功
 - 最终测试状态 @SUCCESS
- 编辑文档，显示在我编辑的文档列表中
 - 测试结果 @检查我编辑的文档列表，检验成功
 - 最终测试状态 @SUCCESS

 */
chdir(__DIR__);
include '../lib/lefttree.ui.class.php';

$tester = new createDocTester();
$tester->login();
$docName = new stdClass();
$docName->dcName = '我的文档A';

r($tester->createdByMe($docName)) && p('message,staus') && e('我创建的文档校验成功，SUCESS');  //校验我创建的文档成功
r($tester->myFavorites($docName)) && p('message,status') && e('收藏文档成功,SUCCESS');         //收藏文档成功
