#!/usr/bin/env php
<?php

/**

title=测试 biModel::isSafeFilterValue();
timeout=0
cid=0

- 字符串字面量通过校验 @1
- 整数通过校验 @1
- 小数通过校验 @1
- 负数通过校验 @1
- 空字符串字面量通过校验 @1
- 双写单引号转义通过校验 @1
- 双引号字符串通过校验 @1
- 反斜线转义通过校验 @1
- SQL 拼接 payload 不通过校验 @0
- 报错注入 payload 不通过校验 @0
- 裸文本不通过校验 @0
- 子查询不通过校验 @0
- 空值不通过校验 @0
- IN 字面量列表通过校验 @1
- IN 数字列表通过校验 @1
- IN 表达式不通过校验 @0
- IN 缺少右括号不通过校验 @0
- NOT IN 字面量列表通过校验 @1
- BETWEEN 两个字面量通过校验 @1
- BETWEEN 含子查询不通过校验 @0
- IS NULL 空值通过校验 @1
- IS NULL 带值不通过校验 @0
- IS NOT NULL 空值通过校验 @1
- LIKE 字面量通过校验 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$biTest = new biModelTest();

r($biTest->isSafeFilterValueTest("'a'", '='))                                     && p() && e(1); // 字符串字面量通过校验
r($biTest->isSafeFilterValueTest('123', '='))                                     && p() && e(1); // 整数通过校验
r($biTest->isSafeFilterValueTest('1.5', '='))                                     && p() && e(1); // 小数通过校验
r($biTest->isSafeFilterValueTest('-3', '='))                                      && p() && e(1); // 负数通过校验
r($biTest->isSafeFilterValueTest("''", '='))                                      && p() && e(1); // 空字符串字面量通过校验
r($biTest->isSafeFilterValueTest("'a''b'", '='))                                  && p() && e(1); // 双写单引号转义通过校验
r($biTest->isSafeFilterValueTest('"abc"', '='))                                   && p() && e(1); // 双引号字符串通过校验
r($biTest->isSafeFilterValueTest("'a\\'b'", '='))                                 && p() && e(1); // 反斜线转义通过校验
r($biTest->isSafeFilterValueTest("'a' and sleep(3) and '1'='1'", '='))            && p() && e(0); // SQL 拼接 payload 不通过校验
r($biTest->isSafeFilterValueTest("'a' and extractvalue(1,concat(0x7e,(select password from zt_user limit 1))) and '1'='1'", '=')) && p() && e(0); // 报错注入 payload 不通过校验
r($biTest->isSafeFilterValueTest('abc', '='))                                     && p() && e(0); // 裸文本不通过校验
r($biTest->isSafeFilterValueTest('(select password from zt_user limit 1)', '='))  && p() && e(0); // 子查询不通过校验
r($biTest->isSafeFilterValueTest('', '='))                                        && p() && e(0); // 空值不通过校验
r($biTest->isSafeFilterValueTest("('a', 'b')", 'IN'))                             && p() && e(1); // IN 字面量列表通过校验
r($biTest->isSafeFilterValueTest('(1,2)', 'IN'))                                  && p() && e(1); // IN 数字列表通过校验
r($biTest->isSafeFilterValueTest("('a' or 1=1)", 'IN'))                           && p() && e(0); // IN 表达式不通过校验
r($biTest->isSafeFilterValueTest("('a'", 'IN'))                                   && p() && e(0); // IN 缺少右括号不通过校验
r($biTest->isSafeFilterValueTest("('a')", 'NOT IN'))                              && p() && e(1); // NOT IN 字面量列表通过校验
r($biTest->isSafeFilterValueTest("'2024-01-01 00:00:00' and '2024-01-02 23:59:59'", 'BETWEEN')) && p() && e(1); // BETWEEN 两个字面量通过校验
r($biTest->isSafeFilterValueTest("'a' and (select 1)", 'BETWEEN'))                && p() && e(0); // BETWEEN 含子查询不通过校验
r($biTest->isSafeFilterValueTest('', 'IS NULL'))                                  && p() && e(1); // IS NULL 空值通过校验
r($biTest->isSafeFilterValueTest("'x'", 'IS NULL'))                               && p() && e(0); // IS NULL 带值不通过校验
r($biTest->isSafeFilterValueTest('', 'IS NOT NULL'))                              && p() && e(1); // IS NOT NULL 空值通过校验
r($biTest->isSafeFilterValueTest("'%a%'", 'LIKE'))                                && p() && e(1); // LIKE 字面量通过校验
