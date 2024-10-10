#!/usr/bin/env php
<?php

/**

title=编辑修改公司信息
timeout=0
cid=2

- 编辑修改公司信息
 - 测试结果 @编辑公司信息成功
 - 最终测试状态 @SUCCESS

*/

chdir(__DIR__);
include '../lib/editcompany.ui.class.php';

$tester = new editCompanyTester();
$tester->login();
