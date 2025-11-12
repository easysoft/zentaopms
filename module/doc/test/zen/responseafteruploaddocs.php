#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterUploadDocs();
timeout=0
cid=0

- 步骤1:空参数返回失败属性result @fail
- 步骤2:combinedDocs格式上传
 - 属性result @success
 - 属性id @1
- 步骤3:combinedDocs带附件
 - 属性result @success
 - 属性id @2
- 步骤4:separateDocs格式属性result @success
- 步骤5:separateDocs带附件属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->responseAfterUploadDocsTest('')) && p('result') && e('fail'); // 步骤1:空参数返回失败
r($docTest->responseAfterUploadDocsTest(array('id' => 1), array('uploadFormat' => 'combinedDocs'))) && p('result,id') && e('success,1'); // 步骤2:combinedDocs格式上传
r($docTest->responseAfterUploadDocsTest(array('id' => 2, 'files' => array('file1.txt', 'file2.txt')), array('uploadFormat' => 'combinedDocs'))) && p('result,id') && e('success,2'); // 步骤3:combinedDocs带附件
r($docTest->responseAfterUploadDocsTest(array('docsAction' => array()), array('uploadFormat' => 'separateDocs'))) && p('result') && e('success'); // 步骤4:separateDocs格式
r($docTest->responseAfterUploadDocsTest(array('docsAction' => array(3 => (object)array('title' => 'doc3.txt'), 4 => (object)array('title' => 'doc4.txt'))), array('uploadFormat' => 'separateDocs'))) && p('result') && e('success'); // 步骤5:separateDocs带附件