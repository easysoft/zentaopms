#!/usr/bin/env php
<?php

/**
title=关闭/激活分支
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/manage.ui.class.php';

$product = zenData('product');
$product->id->range('11');
$product->name->range('多分支产品');
$product->status->range('normal');
$product->type->range('branch');
$product->gen(1);
