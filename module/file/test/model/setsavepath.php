#!/usr/bin/env php
<?php

/**

title=测试 fileModel::setSavePath();
timeout=0
cid=16535

- 步骤1：测试默认公司ID(1)的savePath设置 @/data/upload/1/
- 步骤2：测试不同公司ID(2)的savePath设置 @/data/upload/2/
- 步骤3：测试公司ID为3的savePath设置 @/data/upload/3/
- 步骤4：测试公司ID为10的savePath设置 @/data/upload/10/
- 步骤5：测试重新设置回默认公司ID的savePath @/data/upload/1/

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$file = new fileModelTest();

r($file->setSavePathTest()) && p() && e('/data/upload/1/');                    // 步骤1：测试默认公司ID(1)的savePath设置
r($file->setSavePathTest(2)) && p() && e('/data/upload/2/');                   // 步骤2：测试不同公司ID(2)的savePath设置
r($file->setSavePathTest(3)) && p() && e('/data/upload/3/');                   // 步骤3：测试公司ID为3的savePath设置
r($file->setSavePathTest(10)) && p() && e('/data/upload/10/');                 // 步骤4：测试公司ID为10的savePath设置
r($file->setSavePathTest(1)) && p() && e('/data/upload/1/');                   // 步骤5：测试重新设置回默认公司ID的savePath