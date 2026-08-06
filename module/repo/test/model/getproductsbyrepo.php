#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getProductsByRepo();
timeout=0
cid=18073

- 步骤1：无效代码库ID(0) @0
- 步骤2：不存在的代码库ID(99) @0
- 步骤3：单个产品关联(product=1)属性1 @正常产品1
- 步骤4：多个产品关联(product=2)属性2 @正常产品2
- 步骤5：过滤已删除产品(product=4)属性4 @正常产品4
- 步骤6：产品字段为空(product='') @0
- 步骤7：关联不存在产品(product=10) @0
- 步骤8：关联已删除产品(product=9，已删除) @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_repouser')->gen(0);

$productTable = zenData('product');
$productTable->id->range('1-12');
$productTable->name->range('正常产品1,正常产品2,正常产品3,正常产品4,正常产品5,正常产品6,正常产品7,正常产品8,已删除产品1,已删除产品2,不存在产品1,不存在产品2');
$productTable->code->range('product1,product2,product3,product4,product5,product6,product7,product8,deleted1,deleted2,notexist1,notexist2');
$productTable->deleted->range('0,0,0,0,0,0,0,0,1,1,0,0');
$productTable->gen(12);

$repo = zenData('ops_repo');
$repo->id->range('1-13');
$repo->spaceID->range('1{13}');
$repo->product->range('1,2,3,1,2,2,3,4,5,[]{1},10,99,9');
$repo->name->range('singleProductRepo,multiProductRepo1,multiProductRepo2,testRepo1,testRepo2,mixedRepo1,mixedRepo2,withDeletedProductRepo,validRepo,emptyProductRepo,invalidProductRepo1,invalidProductRepo2,deletedProductRepo');
$repo->gitUID->range('products-uid-1,products-uid-2,products-uid-3,products-uid-4,products-uid-5,products-uid-6,products-uid-7,products-uid-8,products-uid-9,products-uid-10,products-uid-11,products-uid-12,products-uid-13');
$repo->acl->range('private{13}');
$repo->status->range('active{13}');
$repo->deleted->range('0{13}');
$repo->gen(13);

$entry = zenData('entry');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->gen(1);

if(!class_exists('repoGetProductsByRepoHttpClient'))
{
    class repoGetProductsByRepoHttpClient
    {
        public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
        {
            return json_encode(array('code' => 'success', 'data' => array('id' => 1, 'path' => 'space/repo', 'gitURL' => 'http://gitfox.test/space/repo.git', 'importing' => false)));
        }
    }
}

su('admin');

$repo = new repoModelTest();
$oldHttpClient = common::$httpClient;
common::$httpClient = new repoGetProductsByRepoHttpClient();

r($repo->getProductsByRepoTest(0)) && p() && e('0');                    // 步骤1：无效代码库ID(0)
r($repo->getProductsByRepoTest(99)) && p() && e('0');                   // 步骤2：不存在的代码库ID(99)
r($repo->getProductsByRepoTest(1)) && p('1') && e('正常产品1');          // 步骤3：单个产品关联(product=1)
r($repo->getProductsByRepoTest(5)) && p('2') && e('正常产品2');          // 步骤4：多个产品关联(product=2)
r($repo->getProductsByRepoTest(8)) && p('4') && e('正常产品4');          // 步骤5：过滤已删除产品(product=4)
r($repo->getProductsByRepoTest(10)) && p() && e('0');                   // 步骤6：产品字段为空(product='')
r($repo->getProductsByRepoTest(11)) && p() && e('0');                   // 步骤7：关联不存在产品(product=10)
r($repo->getProductsByRepoTest(13)) && p() && e('0');                   // 步骤8：关联已删除产品(product=9，已删除)

common::$httpClient = $oldHttpClient;
