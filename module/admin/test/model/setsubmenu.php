#!/usr/bin/env php
<?php

/**

title=测试 adminModel::setSubMenu();
timeout=0
cid=0

- 执行adminTest模块的setSubMenuTest方法，参数是'system', $normalMenu 属性disabled @0
- 执行adminTest模块的setSubMenuTest方法，参数是'test', $emptyOrderMenu  @rray()
- 执行adminTest模块的setSubMenuTest方法，参数是'invalid', $invalidMenu 属性disabled @1
- 执行adminTest模块的setSubMenuTest方法，参数是'message', $messageMenu 属性subMenu @Mail|mail|detect|
属性mail @Mail|mail|detect|
属性link @Mail|mail|detect|
- 执行adminTest模块的setSubMenuTest方法，参数是'dev', $devMenu 属性subMenu @Editor|editor|index|
属性editor @Editor|editor|index|
属性link @Editor|editor|index|

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

su('admin');

$adminTest = new adminTest();

// 测试步骤1：测试正常的二级菜单配置
$normalMenu = array(
    'subMenu' => array(
        'system' => array(
            'name' => '系统设置',
            'link' => 'System|system|index|'
        ),
        'user' => array(
            'name' => '用户管理',
            'link' => 'Users|user|admin|'
        )
    ),
    'menuOrder' => array(
        1 => 'system',
        2 => 'user'
    ),
    'link' => '',
    'disabled' => true
);
r($adminTest->setSubMenuTest('system', $normalMenu)) && p('disabled') && e('0');

// 测试步骤2：测试空子菜单排序的情况
$emptyOrderMenu = array(
    'subMenu' => array(
        'test' => array(
            'name' => '测试',
            'link' => 'Test|test|index|'
        )
    ),
    'menuOrder' => array()
);
r($adminTest->setSubMenuTest('test', $emptyOrderMenu)) && p() && e(array());

// 测试步骤3：测试无效菜单配置的情况（缺少subMenu）
$invalidMenu = array(
    'menuOrder' => array(
        1 => 'invalid'
    ),
    'disabled' => true
);
r($adminTest->setSubMenuTest('invalid', $invalidMenu)) && p('disabled') && e('1');

// 测试步骤4：测试特殊菜单（message|mail）配置
$messageMenu = array(
    'subMenu' => array(
        'mail' => array(
            'name' => '邮件设置',
            'link' => 'Mail|mail|index|'
        )
    ),
    'menuOrder' => array(
        1 => 'mail'
    ),
    'link' => '',
    'disabled' => true
);
r($adminTest->setSubMenuTest('message', $messageMenu)) && p('subMenu,mail,link') && e('Mail|mail|detect|');

// 测试步骤5：测试特殊菜单（dev|editor）配置
$devMenu = array(
    'subMenu' => array(
        'editor' => array(
            'name' => '编辑器',
            'link' => 'Editor|editor|index|'
        )
    ),
    'menuOrder' => array(
        1 => 'editor'
    ),
    'link' => '',
    'disabled' => true
);
r($adminTest->setSubMenuTest('dev', $devMenu)) && p('subMenu,editor,link') && e('Editor|editor|index|');
