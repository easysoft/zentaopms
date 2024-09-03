<?php

/**
title=维护产品
timeout=0
cid=1
 */

chdir(__DIR__);
#include '../lib/manageproducts.ui.class.php';
include '/opt/dev/pms/test/lib/ui.php';
$product = zenData('product');
$product->id->range('1-4');
$product->name->range('产品1, 产品2, 产品3, 产品4');
$product->type->range('normal');
$product->gen(4);
