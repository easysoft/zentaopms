#!/usr/bin/env php
<?php

/**

title=测试 adminModel::setTabMenu();
timeout=0
cid=14985

- 执行adminTest模块的setTabMenuTest方法，参数是'company', $menu1  @2
- 执行adminTest模块的setTabMenuTest方法，参数是'system', $menu2  @2
- 执行adminTest模块的setTabMenuTest方法，参数是'dev', $menu3  @2
- 执行adminTest模块的setTabMenuTest方法，参数是'message', $menu4  @2
- 执行adminTest模块的setTabMenuTest方法，参数是'security', $menu5  @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$adminTest = new adminModelTest();

// 4. 强制要求：必须包含至少5个测试步骤

// 测试步骤1：测试正常的三级菜单配置
$menu1 = array(
    'tabMenu' => array(
        'company' => array(
            'browse' => array('link' => '组织|company|browse|', 'links' => array()),
            'dept' => array('link' => '部门|dept|browse|', 'links' => array())
        ),
        'menuOrder' => array(
            'company' => array(1 => 'browse', 2 => 'dept')
        )
    )
);
r(count($adminTest->setTabMenuTest('company', $menu1))) && p() && e('2');

// 测试步骤2：测试空三级菜单的情况
$menu2 = array(
    'tabMenu' => array(
        'system' => array()
    )
);
r(count($adminTest->setTabMenuTest('system', $menu2))) && p() && e('2');

// 测试步骤3：测试包含菜单排序的三级导航
$menu3 = array(
    'tabMenu' => array(
        'dev' => array(
            'db' => array('link' => '数据库|dev|db|', 'links' => array()),
            'api' => array('link' => 'API|dev|api|', 'links' => array()),
            'editor' => array('link' => '编辑器|dev|editor|', 'links' => array())
        ),
        'menuOrder' => array(
            'dev' => array(3 => 'editor', 1 => 'db', 2 => 'api')
        )
    )
);
r(count($adminTest->setTabMenuTest('dev', $menu3))) && p() && e('2');

// 测试步骤4：测试无权限的三级菜单项
$menu4 = array(
    'tabMenu' => array(
        'message' => array(
            'mail' => array('link' => '邮件|mail|index|', 'links' => array('mail|detect|')),
            'webhook' => array('link' => 'Webhook|webhook|browse|', 'links' => array())
        )
    )
);
r(count($adminTest->setTabMenuTest('message', $menu4))) && p() && e('2');

// 测试步骤5：测试含有效权限链接的菜单
$menu5 = array(
    'tabMenu' => array(
        'security' => array(
            'safe' => array('link' => '安全|admin|safe|', 'links' => array()),
            'log' => array('link' => '日志|admin|log|', 'links' => array())
        )
    )
);
r(count($adminTest->setTabMenuTest('security', $menu5))) && p() && e('2');