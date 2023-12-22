#!/usr/bin/env php
<?php

/**

title=测试 docModel->buildDocNode();
cid=1

- 测试模块为空，libID=0时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @0
 - 属性actions @``
- 测试模块为空，libID=11时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @2
 - 属性actions @``
- 测试模块为空，libID=13时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @4
 - 属性actions @``
- 测试模块为空，libID不存在时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @6
 - 属性actions @``
- 测试模块为父模块，libID=0时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @2
 - 属性actions @``
- 测试模块为父模块，libID=11时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @3
 - 属性actions @``
- 测试模块为父模块，libID=13时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @4
 - 属性actions @``
- 测试模块为父模块，libID不存在时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @5
 - 属性actions @``
- 测试模块为普通模块，libID=0时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @0
 - 属性actions @``
- 测试模块为普通模块，libID=11时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @0
 - 属性actions @``
- 测试模块为普通模块，libID=13时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @0
 - 属性actions @``
- 测试模块为普通模块，libID不存在时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @0
 - 属性actions @``
- 测试模块不存在，libID=0时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @0
 - 属性actions @``
- 测试模块不存在，libID=11时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @0
 - 属性actions @``
- 测试模块不存在，libID=13时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @0
 - 属性actions @``
- 测试模块不存在，libID不存在时，获取的文档节点数据
 - 属性type @module
 - 属性docsCount @0
 - 属性actions @``

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('module')->config('module')->gen(3);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);

$libIds = array(0, 11, 13, 14);

/* Empty module object. */
$emptyModule = new stdclass();

/* Parent module object. */
$childModule = new stdclass();
$childModule->id = 2;

$parentModule = new stdclass();
$parentModule->id = 1;
$parentModule->children[] = $childModule;

/* Normal module object. */
$normalModule = new stdclass();
$normalModule->id = 3;

/* Not exist module object. */
$notExistModule = new stdclass();
$notExistModule->id = 4;

$docTester = new docTest();

/* Empty module object. */
r($docTester->buildDocNodeTest($emptyModule, $libIds[0])) && p('type,docsCount,actions') && e('module,0,``'); // 测试模块为空，libID=0时，获取的文档节点数据
r($docTester->buildDocNodeTest($emptyModule, $libIds[1])) && p('type,docsCount,actions') && e('module,2,``'); // 测试模块为空，libID=11时，获取的文档节点数据
r($docTester->buildDocNodeTest($emptyModule, $libIds[2])) && p('type,docsCount,actions') && e('module,4,``'); // 测试模块为空，libID=13时，获取的文档节点数据
r($docTester->buildDocNodeTest($emptyModule, $libIds[3])) && p('type,docsCount,actions') && e('module,6,``'); // 测试模块为空，libID不存在时，获取的文档节点数据

/* Parent module object. */
r($docTester->buildDocNodeTest($parentModule, $libIds[0])) && p('type,docsCount,actions') && e('module,2,``'); // 测试模块为父模块，libID=0时，获取的文档节点数据
r($docTester->buildDocNodeTest($parentModule, $libIds[1])) && p('type,docsCount,actions') && e('module,3,``'); // 测试模块为父模块，libID=11时，获取的文档节点数据
r($docTester->buildDocNodeTest($parentModule, $libIds[2])) && p('type,docsCount,actions') && e('module,4,``'); // 测试模块为父模块，libID=13时，获取的文档节点数据
r($docTester->buildDocNodeTest($parentModule, $libIds[3])) && p('type,docsCount,actions') && e('module,5,``'); // 测试模块为父模块，libID不存在时，获取的文档节点数据

/* Normal module object. */
r($docTester->buildDocNodeTest($normalModule, $libIds[0])) && p('type,docsCount,actions') && e('module,0,``'); // 测试模块为普通模块，libID=0时，获取的文档节点数据
r($docTester->buildDocNodeTest($normalModule, $libIds[1])) && p('type,docsCount,actions') && e('module,0,``'); // 测试模块为普通模块，libID=11时，获取的文档节点数据
r($docTester->buildDocNodeTest($normalModule, $libIds[2])) && p('type,docsCount,actions') && e('module,0,``'); // 测试模块为普通模块，libID=13时，获取的文档节点数据
r($docTester->buildDocNodeTest($normalModule, $libIds[3])) && p('type,docsCount,actions') && e('module,0,``'); // 测试模块为普通模块，libID不存在时，获取的文档节点数据

/* Not exist module object. */
r($docTester->buildDocNodeTest($notExistModule, $libIds[0])) && p('type,docsCount,actions') && e('module,0,``'); // 测试模块不存在，libID=0时，获取的文档节点数据
r($docTester->buildDocNodeTest($notExistModule, $libIds[1])) && p('type,docsCount,actions') && e('module,0,``'); // 测试模块不存在，libID=11时，获取的文档节点数据
r($docTester->buildDocNodeTest($notExistModule, $libIds[2])) && p('type,docsCount,actions') && e('module,0,``'); // 测试模块不存在，libID=13时，获取的文档节点数据
r($docTester->buildDocNodeTest($notExistModule, $libIds[3])) && p('type,docsCount,actions') && e('module,0,``'); // 测试模块不存在，libID不存在时，获取的文档节点数据
