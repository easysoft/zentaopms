#!/usr/bin/env php
<?php

/**

title=测试 groupModel::loadResourceLang();
timeout=0
cid=16720

- 测试步骤1：验证项目模块语言加载成功属性start @启动项目
- 测试步骤2：验证文档模块的common被替换为manage属性common @文档管理
- 测试步骤3：验证API模块的common被替换为manage属性common @接口管理
- 测试步骤4：验证自定义模块的common被设置为group.config属性common @配置
- 测试步骤5：验证基线模块语言配置条件处理属性common @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$group = new groupModelTest();
$resource = $group->loadResourceLangTest();

r($resource->project) && p('start') && e('启动项目'); // 测试步骤1：验证项目模块语言加载成功
r($resource->doc) && p('common') && e('文档管理'); // 测试步骤2：验证文档模块的common被替换为manage
r($resource->api) && p('common') && e('接口管理'); // 测试步骤3：验证API模块的common被替换为manage
r($resource->custom) && p('common') && e('配置'); // 测试步骤4：验证自定义模块的common被设置为group.config
r(isset($resource->baseline) ? $resource->baseline : '') && p('common') && e('0'); // 测试步骤5：验证基线模块语言配置条件处理