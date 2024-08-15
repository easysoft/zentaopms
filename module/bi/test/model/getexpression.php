#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=biModel->getExpression();
timeout=0
cid=1

*/

$bi = new biTest();
r($bi->getExpressionTest(null, '*'))                    && p('') && e('*');                           // 测试 *
r($bi->getExpressionTest(null, 'id'))                   && p('') && e('`id`');                        // 测试 id
r($bi->getExpressionTest('t1', 'id'))                   && p('') && e('`t1`.`id`');                   // 测试 t1.id
r($bi->getExpressionTest('t1', '*'))                    && p('') && e('`t1`.*');                      // 测试 t1.*
