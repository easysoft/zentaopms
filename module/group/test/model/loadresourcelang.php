#!/usr/bin/env php
<?php

/**

title=测试 groupModel->loadSourceLang();
timeout=0
cid=1

- 测试项目模块的语言项是否已加载属性start @启动项目
- 测试文档模块的common是否已替换为manage属性common @文档管理

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

$group = new groupTest();
$resource = $group->loadResourceLangTest();

r($resource->project) && p('start')  && e('启动项目'); //测试项目模块的语言项是否已加载
r($resource->doc)     && p('common') && e('文档管理'); //测试文档模块的common是否已替换为manage