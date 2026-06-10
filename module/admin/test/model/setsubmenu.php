#!/usr/bin/env php
<?php

/**

title=测试 adminModel::setSubMenu();
timeout=0
cid=0

- 执行 setSubMenuTest('system', $normalMenu) 属性disabled @0
- 执行 setSubMenuTest('test', $emptyOrderMenu) 属性isEmpty @1
- 执行 setSubMenuTest('invalid', $invalidMenu) 属性disabled @1
- 执行 setSubMenuTest('message', $messageMenu) 属性firstSubMenuKey,subMenuModule,subMenuMethod @mail,mail,detect
- 执行 setSubMenuTest('dev', $devMenu) 属性firstSubMenuKey,subMenuModule,subMenuMethod @editor,editor,index

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$adminTest = new adminModelTest();

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

$emptyOrderMenu = array(
    'subMenu' => array(
        'test' => array(
            'name' => '测试',
            'link' => 'Test|test|index|'
        )
    ),
    'menuOrder' => array()
);
r($adminTest->setSubMenuTest('test', $emptyOrderMenu)) && p('isEmpty') && e('1');

$invalidMenu = array(
    'menuOrder' => array(
        1 => 'invalid'
    ),
    'disabled' => true
);
r($adminTest->setSubMenuTest('invalid', $invalidMenu)) && p('disabled') && e('1');

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
r($adminTest->setSubMenuTest('message', $messageMenu)) && p('firstSubMenuKey,subMenuModule,subMenuMethod') && e('mail,mail,detect');

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
r($adminTest->setSubMenuTest('dev', $devMenu)) && p('firstSubMenuKey,subMenuModule,subMenuMethod') && e('editor,editor,index');
