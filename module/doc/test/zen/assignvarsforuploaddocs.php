#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignVarsForUploadDocs();
timeout=0
cid=16181

- 测试产品空间下上传文档
 - 属性objectType @product
 - 属性libID @1
 - 属性linkType @product
 - 属性hasOptionMenu @1
- 测试项目空间下上传文档
 - 属性objectType @project
 - 属性libID @2
 - 属性linkType @project
- 测试自定义空间下上传文档
 - 属性objectType @custom
 - 属性libID @7
 - 属性linkType @custom
 - 属性hasSpaces @1
- 测试我的空间下上传文档
 - 属性objectType @mine
 - 属性libID @9
 - 属性linkType @mine
 - 属性hasSpaces @1
- 测试执行空间下上传文档
 - 属性objectType @execution
 - 属性objectID @3
- 测试不传入任何参数使用默认值
 - 属性objectType @product
 - 属性linkType @product

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

zenData('doclib')->loadYaml('assignvarsforuploaddocs/doclib', false, 2)->gen(10);
zenData('product')->loadYaml('assignvarsforuploaddocs/product', false, 2)->gen(5);
zenData('project')->loadYaml('assignvarsforuploaddocs/project', false, 2)->gen(5);
zenData('module')->loadYaml('assignvarsforuploaddocs/module', false, 2)->gen(5);
zenData('doc')->loadYaml('assignvarsforuploaddocs/doc', false, 2)->gen(10);
zenData('group')->gen(3);
zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

r($docTest->assignVarsForUploadDocsTest('product', 1, 1, 0, '')) && p('objectType,libID,linkType,hasOptionMenu') && e('product,1,product,1'); // 测试产品空间下上传文档
r($docTest->assignVarsForUploadDocsTest('project', 2, 2, 0, '')) && p('objectType,libID,linkType') && e('project,2,project'); // 测试项目空间下上传文档
r($docTest->assignVarsForUploadDocsTest('custom', 0, 7, 0, '')) && p('objectType,libID,linkType,hasSpaces') && e('custom,7,custom,1'); // 测试自定义空间下上传文档
r($docTest->assignVarsForUploadDocsTest('mine', 0, 9, 0, '')) && p('objectType,libID,linkType,hasSpaces') && e('mine,9,mine,1'); // 测试我的空间下上传文档
r($docTest->assignVarsForUploadDocsTest('execution', 3, 0, 0, '')) && p('objectType,objectID') && e('execution,3'); // 测试执行空间下上传文档
r($docTest->assignVarsForUploadDocsTest('product', 0, 0, 0, '')) && p('objectType,linkType') && e('product,product'); // 测试不传入任何参数使用默认值
