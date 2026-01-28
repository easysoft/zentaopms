#!/usr/bin/env php
<?php

/**

title=测试 biModel::getTableAndFields();
timeout=0
cid=15188

- 执行bi模块的getTableAndFields方法，参数是'SELECT * FROM zt_story' 第tables条的0属性 @zt_story
- 执行bi模块的getTableAndFields方法，参数是'SELECT * FROM zt_story' 第fields条的*属性 @*
- 执行bi模块的getTableAndFields方法，参数是'SELECT id, name FROM zt_story' 第tables条的0属性 @zt_story
- 执行bi模块的getTableAndFields方法，参数是'SELECT id, name FROM zt_story'
 - 第fields条的id属性 @id
 - 第fields条的name属性 @name
- 执行bi模块的getTableAndFields方法，参数是'SELECT u.id, p.name FROM zt_story u LEFT JOIN zt_product p ON u.product = p.id'
 - 第tables条的0属性 @zt_story
 - 第tables条的1属性 @zt_product

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$bi = new biModelTest();

r($bi->getTableAndFields('SELECT * FROM zt_story')) && p('tables:0') && e('zt_story');
r($bi->getTableAndFields('SELECT * FROM zt_story')) && p('fields:*') && e('*');
r($bi->getTableAndFields('SELECT id,name FROM zt_story')) && p('tables:0') && e('zt_story');
r($bi->getTableAndFields('SELECT id,name FROM zt_story')) && p('fields:id,name') && e('id,name');
r($bi->getTableAndFields('SELECT u.id, p.name FROM zt_story u LEFT JOIN zt_product p ON u.product = p.id')) && p('tables:0,1') && e('zt_story,zt_product');