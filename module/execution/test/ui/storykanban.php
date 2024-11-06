#!/usr/bin/env php
<?php

/**
title=需求看板
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/storykanban.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);
