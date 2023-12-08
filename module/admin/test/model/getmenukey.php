#!/usr/bin/env php
<?php
/**

title=测试 adminModel->getMenuKey();
timeout=0
cid=1

- 访问后台页面 @null
- 访问备份页面 @system
- 访问定时列表页面 @system
- 访问定时创建页面 @system
- 访问部门页面 @company
- 访问编辑用户页面 @company
- 访问权限列表页面 @company
- 访问功能设置页面 @switch
- 访问项目必填项页面 @model
- 访问阶段类型页面 @model
- 访问检查对象页面 @model
- 访问自定义待办页面 @feature
- 访问自定义产品页面 @feature
- 访问自定义看板页面 @feature
- 访问模板类型页面 @template
- 访问文档模板页面 @template
- 访问文档目录页面 @template
- 访问发信配置页面 @message
- 访问Webhook页面 @message
- 访问短信页面 @message
- 访问浏览器页面 @message
- 访问信息配置页面 @message
- 访问插件页面 @extension
- 访问API页面 @dev
- 访问数据库页面 @dev
- 访问编辑器页面 @dev
- 访问应用页面 @dev
- 访问数据导入页面 @convert

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/admin.class.php';

zdTable('user')->gen(5);
su('admin');
$admin = new adminTest();

$params1 = array('module' => 'project');
$params2 = array('module' => 'todo');
$params3 = array('module' => 'baseline');

r($admin->getMenuKeyTest('admin',     'index'))              && p() && e('null');      //访问后台页面
r($admin->getMenuKeyTest('backup',    'index'))              && p() && e('system');    //访问备份页面
r($admin->getMenuKeyTest('cron',      'index'))              && p() && e('system');    //访问定时列表页面
r($admin->getMenuKeyTest('cron',      'create'))             && p() && e('system');    //访问定时创建页面
r($admin->getMenuKeyTest('dept',      'browse'))             && p() && e('company');   //访问部门页面
r($admin->getMenuKeyTest('user',      'edit'))               && p() && e('company');   //访问编辑用户页面
r($admin->getMenuKeyTest('group',     'browse'))             && p() && e('company');   //访问权限列表页面
r($admin->getMenuKeyTest('admin',     'setmodule'))          && p() && e('switch');    //访问功能设置页面
r($admin->getMenuKeyTest('custom',    'required', $params1)) && p() && e('model');     //访问项目必填项页面
r($admin->getMenuKeyTest('stage',     'settype'))            && p() && e('model');     //访问阶段类型页面
r($admin->getMenuKeyTest('auditcl',   'scrumbrowse'))        && p() && e('model');     //访问检查对象页面
r($admin->getMenuKeyTest('custom',    'set', $params2))      && p() && e('feature');   //访问自定义待办页面
r($admin->getMenuKeyTest('custom',    'product'))            && p() && e('feature');   //访问自定义产品页面
r($admin->getMenuKeyTest('custom',    'kanban'))             && p() && e('feature');   //访问自定义看板页面
r($admin->getMenuKeyTest('custom',    'set', $params3))      && p() && e('template');  //访问模板类型页面
r($admin->getMenuKeyTest('baseline',  'template'))           && p() && e('template');  //访问文档模板页面
r($admin->getMenuKeyTest('baseline',  'catalog'))            && p() && e('template');  //访问文档目录页面
r($admin->getMenuKeyTest('mail',      'detect'))             && p() && e('message');   //访问发信配置页面
r($admin->getMenuKeyTest('webhook',   'browse'))             && p() && e('message');   //访问Webhook页面
r($admin->getMenuKeyTest('sms',       'index'))              && p() && e('message');   //访问短信页面
r($admin->getMenuKeyTest('message',   'browser'))            && p() && e('message');   //访问浏览器页面
r($admin->getMenuKeyTest('message',   'setting'))            && p() && e('message');   //访问信息配置页面
r($admin->getMenuKeyTest('extension', 'browse'))             && p() && e('extension'); //访问插件页面
r($admin->getMenuKeyTest('dev',       'api'))                && p() && e('dev');       //访问API页面
r($admin->getMenuKeyTest('dev',       'db'))                 && p() && e('dev');       //访问数据库页面
r($admin->getMenuKeyTest('dev',       'editor'))             && p() && e('dev');       //访问编辑器页面
r($admin->getMenuKeyTest('entry',     'browse'))             && p() && e('dev');       //访问应用页面
r($admin->getMenuKeyTest('convert',   'convertjira'))        && p() && e('convert');   //访问数据导入页面
