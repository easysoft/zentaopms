#!/usr/bin/env php
<?php

/**
title=编辑分支测试
timeout=0
cid=13

-校验分支名称必填
 -测试结果 @分支名称必填提示信息正确
 -最终测试状态 @SUCCESS
-校验分支重复
 -测试结果 @分支已存在提示信息正确
 -最终测试状态 @SUCCESS
-校验分支正常编辑
 -测试结果 @编辑分支成功$editBranch->name = '分支test编辑';
 -最终测试状态 @SUCCESS

 */
