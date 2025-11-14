#!/usr/bin/env php
<?php

/**

title=测试 productZen::responseAfterEdit();
timeout=0
cid=17603

- 步骤1:编辑产品,有programID属性result @success
- 步骤2:编辑产品,无programID属性result @success
- 步骤3:产品ID为0属性result @success
- 步骤4:验证load字段属性load @responseafteredit.php?m=product&f=view&product=5
- 步骤5:验证message字段属性message @保存成功

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('product')->gen(10);

su('admin');

$productTest = new productZenTest();

r($productTest->responseAfterEditTest(1, 1)) && p('result') && e('success'); // 步骤1:编辑产品,有programID
r($productTest->responseAfterEditTest(1, 0)) && p('result') && e('success'); // 步骤2:编辑产品,无programID
r($productTest->responseAfterEditTest(0, 0)) && p('result') && e('success'); // 步骤3:产品ID为0
r($productTest->responseAfterEditTest(5, 0)) && p('load') && e('responseafteredit.php?m=product&f=view&product=5'); // 步骤4:验证load字段
r($productTest->responseAfterEditTest(3, 2)) && p('message') && e('保存成功'); // 步骤5:验证message字段