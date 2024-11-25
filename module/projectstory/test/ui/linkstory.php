#!/usr/bin/env php
<?php

/**

title=关联需求
timeout=0
cid=1

- 正常关联需求
 - 最终测试状态 @SUCCESS
 - 测试结果 @关联需求成功

 */

chdir(__DIR__);
include '../lib/linkstory.ui.class.php';

$product = zenData('product');
$product->id->range('1');
