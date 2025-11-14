#!/usr/bin/env php
<?php

/**

title=测试 repoZen::parseErrorContent();
timeout=0
cid=18149

- 执行repoZenTest模块的parseErrorContentTest方法，参数是"can contain only letters, digits, '_', '-' and '.'. Cannot start with '-', end in '.git' or end in '.atom'"  @只能包含字母、数字、'.'-'和'.'。不能以'-'开头、以'.git'结尾或以'.atom'结尾。
- 执行repoZenTest模块的parseErrorContentTest方法，参数是"Branch is exists"  @分支名已存在。
- 执行repoZenTest模块的parseErrorContentTest方法，参数是"Forbidden"  @权限不足。
- 执行repoZenTest模块的parseErrorContentTest方法，参数是"cannot have ASCII control characters"  @分支名不能包含 ' ', '~', '^'或':'。

- 执行repoZenTest模块的parseErrorContentTest方法，参数是"Unknown error message"  @Unknown error message

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoZenTest = new repoZenTest();

r($repoZenTest->parseErrorContentTest("can contain only letters, digits, '_', '-' and '.'. Cannot start with '-', end in '.git' or end in '.atom'")) && p() && e("只能包含字母、数字、'.'-'和'.'。不能以'-'开头、以'.git'结尾或以'.atom'结尾。");
r($repoZenTest->parseErrorContentTest("Branch is exists")) && p() && e('分支名已存在。');
r($repoZenTest->parseErrorContentTest("Forbidden")) && p() && e('权限不足。');
r($repoZenTest->parseErrorContentTest("cannot have ASCII control characters")) && p() && e("分支名不能包含 ' ', '~', '^'或':'。");
r($repoZenTest->parseErrorContentTest("Unknown error message")) && p() && e("Unknown error message");