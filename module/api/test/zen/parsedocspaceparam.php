#!/usr/bin/env php
<?php

/**

title=测试 apiZen::parseDocSpaceParam();
timeout=0
cid=15126

- 步骤1：无cookie时保留默认产品空间 @product,1,6
- 步骤2：product类型cookie切换到产品空间 @product,1,6
- 步骤3：objectID为0的无效cookie回退默认值 @product,1,6
- 步骤4：不存在的库ID会回退到当前对象的首个库 @product,1,6
- 步骤5：cookie中的moduleID会写入视图 @product,1,6,2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('测试产品1');
$product->code->range('PRD001');
$product->bind->range('0');
$product->line->range('0');
$product->type->range('normal');
$product->status->range('normal');
$product->subStatus->range('');
$product->desc->range('');
$product->PO->range('admin');
$product->QD->range('admin');
$product->RD->range('admin');
$product->acl->range('open');
$product->whitelist->range('');
$product->createdBy->range('admin');
$product->createdDate->range('`2023-01-01 00:00:00`');
$product->createdVersion->range('');
$product->order->range('1');
$product->vision->range('rnd');
$product->deleted->range('0');
$product->gen(1);

$doclib = zenData('doclib');
$doclib->id->range('6');
$doclib->type->range('api');
$doclib->vision->range('rnd');
$doclib->parent->range('0');
$doclib->product->range('1');
$doclib->project->range('0');
$doclib->execution->range('0');
$doclib->name->range('产品API库');
$doclib->baseUrl->range('`http://localhost/api`');
$doclib->acl->range('open');
$doclib->groups->range('');
$doclib->users->range('');
$doclib->main->range('0');
$doclib->collector->range('');
$doclib->desc->range('');
$doclib->order->range('1');
$doclib->addedBy->range('admin');
$doclib->addedDate->range('`2023-01-01 00:00:00`');
$doclib->deleted->range('0');
$doclib->archived->range('0');
$doclib->orderBy->range('id_asc');
$doclib->gen(1);

su('admin');

$apiTest       = new apiZenTest();
$productLib    = $tester->dao->select('*')->from(TABLE_DOCLIB)->where('id')->eq(6)->fetch();
$productLibs   = array(6 => $productLib);
$productLibID  = 6;
$productObjectID = 1;

$cookieProduct = json_encode(array('type' => 'product', 'objectID' => $productObjectID, 'libID' => $productLibID, 'moduleID' => 0, 'browseType' => 'all', 'param' => 0));
$cookieInvalid = json_encode(array('type' => 'product', 'objectID' => 0, 'libID' => $productLibID, 'moduleID' => 0, 'browseType' => 'all', 'param' => 0));
$cookieMissing = json_encode(array('type' => 'product', 'objectID' => $productObjectID, 'libID' => 999, 'moduleID' => 0, 'browseType' => 'all', 'param' => 0));
$cookieModule  = json_encode(array('type' => 'product', 'objectID' => $productObjectID, 'libID' => $productLibID, 'moduleID' => 2, 'browseType' => 'all', 'param' => 0));

r($apiTest->parseDocSpaceParamTest($productLibs, $productLibID, 'product', $productObjectID, 0, 'product', 0, ''))            && p('type,objectID,libID')          && e('product,1,6');
r($apiTest->parseDocSpaceParamTest($productLibs, $productLibID, 'nolink', 0, 0, 'nolink', 0, $cookieProduct))                  && p('type,objectID,libID')          && e('product,1,6');
r($apiTest->parseDocSpaceParamTest($productLibs, $productLibID, 'product', $productObjectID, 0, 'product', 0, $cookieInvalid)) && p('type,objectID,libID')          && e('product,1,6');
r($apiTest->parseDocSpaceParamTest($productLibs, $productLibID, 'nolink', 0, 0, 'nolink', 0, $cookieMissing))                  && p('type,objectID,libID')          && e('product,1,6');
r($apiTest->parseDocSpaceParamTest($productLibs, $productLibID, 'nolink', 0, 0, 'nolink', 0, $cookieModule))                   && p('type,objectID,libID,moduleID') && e('product,1,6,2');
