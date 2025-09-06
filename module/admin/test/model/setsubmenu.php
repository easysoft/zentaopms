#!/usr/bin/env php
<?php

/**

title=测试 adminModel::setSubMenu();
timeout=0
cid=0

- 执行adminTest模块的setSubMenuTest方法，参数是'system', $normalMenu  @2
- 执行adminTest模块的setSubMenuTest方法，参数是'test', $emptyOrderMenu  @0
- 执行adminTest模块的setSubMenuTest方法，参数是'invalid', $invalidMenu  @1
- 执行adminTest模块的setSubMenuTest方法，参数是'message', $messageMenu  @2
- 执行adminTest模块的setSubMenuTest方法，参数是'dev', $devMenu  @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

// 2. 准备测试数据（不需要zenData，使用手动构建的测试数据）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$adminTest = new adminTest();

// 5. 强制要求：必须包含至少5个测试步骤

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
    )
);
r(count($adminTest->setSubMenuTest('system', $normalMenu))) && p() && e('2');

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
r(count($adminTest->setSubMenuTest('test', $emptyOrderMenu))) && p() && e('0');

// 测试步骤3：测试无效菜单配置的情况（缺少subMenu）
$invalidMenu = array(
    'menuOrder' => array(
        1 => 'invalid'
    )
);
r(count($adminTest->setSubMenuTest('invalid', $invalidMenu))) && p() && e('1');

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
    )
);
r(count($adminTest->setSubMenuTest('message', $messageMenu))) && p() && e('2');

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
    )
);
r(count($adminTest->setSubMenuTest('dev', $devMenu))) && p() && e('2');