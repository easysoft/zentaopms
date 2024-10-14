#!/usr/bin/env php
<?php

/**

title=发布接口测试
timeout=0
cid=0

- 发布接口成功
 - 测试结果 @发布接口成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/releaseapi.ui.class.php';

$doclib = zenData('doclib');
$doclib->id->range('1-2');
$doclib->type->range('api');
