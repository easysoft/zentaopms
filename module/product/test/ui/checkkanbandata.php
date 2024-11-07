#!/usr/bin/env php
<?php

/**
 *
 * title=检查产品看板数据准确性
 * timeout=0
 * cid=0
 *
 */
chdir(__DIR__);
include '../lib/kanban.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->PO->range('admin');
$product->status->range('normal');
$product->type->range('normal');
