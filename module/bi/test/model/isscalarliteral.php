#!/usr/bin/env php
<?php

/**

title=测试 biModel::isScalarLiteral();
timeout=0
cid=0

- 整数通过校验 @1
- 小数通过校验 @1
- 负数通过校验 @1
- 单引号字符串通过校验 @1
- 空字符串字面量通过校验 @1
- 双引号字符串通过校验 @1
- 双写单引号转义通过校验 @1
- 反斜线转义通过校验 @1
- 裸文本不通过校验 @0
- 空值不通过校验 @0
- 表达式不通过校验 @0
- 两个字面量拼接不通过校验 @0
- 子查询不通过校验 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$biTest = new biModelTest();

r($biTest->isScalarLiteralTest('123'))                   && p() && e(1); // 整数通过校验
r($biTest->isScalarLiteralTest('1.5'))                   && p() && e(1); // 小数通过校验
r($biTest->isScalarLiteralTest('-3'))                    && p() && e(1); // 负数通过校验
r($biTest->isScalarLiteralTest("'a'"))                   && p() && e(1); // 单引号字符串通过校验
r($biTest->isScalarLiteralTest("''"))                    && p() && e(1); // 空字符串字面量通过校验
r($biTest->isScalarLiteralTest('"abc"'))                 && p() && e(1); // 双引号字符串通过校验
r($biTest->isScalarLiteralTest("'a''b'"))                && p() && e(1); // 双写单引号转义通过校验
r($biTest->isScalarLiteralTest("'a\\'b'"))               && p() && e(1); // 反斜线转义通过校验
r($biTest->isScalarLiteralTest('abc'))                   && p() && e(0); // 裸文本不通过校验
r($biTest->isScalarLiteralTest(''))                      && p() && e(0); // 空值不通过校验
r($biTest->isScalarLiteralTest("'a' and 'b'"))           && p() && e(0); // 两个字面量拼接不通过校验
r($biTest->isScalarLiteralTest('sleep(3)'))              && p() && e(0); // 表达式不通过校验
r($biTest->isScalarLiteralTest('(select 1)'))            && p() && e(0); // 子查询不通过校验
