#!/usr/bin/env php
<?php

/**

title=测试 biModel::isAllowedFilterOperator();
timeout=0
cid=0

- IN 通过校验 @1
- NOT IN 通过校验 @1
- LIKE 通过校验 @1
- NOT LIKE 通过校验 @1
- BETWEEN 通过校验 @1
- = 通过校验 @1
- != 通过校验 @1
- <> 通过校验 @1
- > 通过校验 @1
- < 通过校验 @1
- >= 通过校验 @1
- <= 通过校验 @1
- IS NULL 通过校验 @1
- IS NOT NULL 通过校验 @1
- 空字符串不通过校验 @0
- AND 不通过校验 @0
- OR 不通过校验 @0
- XOR 不通过校验 @0
- || 不通过校验 @0
- UNION 不通过校验 @0
- SELECT 不通过校验 @0
- -- 不通过校验 @0
- # 不通过校验 @0
- /* 不通过校验 @0
- ; 不通过校验 @0
- = 1 不通过校验 @0
- LIKE BINARY 不通过校验 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$biTest = new biModelTest();

r($biTest->isAllowedFilterOperatorTest('IN'))          && p() && e(1); // IN 通过校验
r($biTest->isAllowedFilterOperatorTest('NOT IN'))      && p() && e(1); // NOT IN 通过校验
r($biTest->isAllowedFilterOperatorTest('LIKE'))        && p() && e(1); // LIKE 通过校验
r($biTest->isAllowedFilterOperatorTest('NOT LIKE'))    && p() && e(1); // NOT LIKE 通过校验
r($biTest->isAllowedFilterOperatorTest('BETWEEN'))     && p() && e(1); // BETWEEN 通过校验
r($biTest->isAllowedFilterOperatorTest('='))           && p() && e(1); // = 通过校验
r($biTest->isAllowedFilterOperatorTest('!='))          && p() && e(1); // != 通过校验
r($biTest->isAllowedFilterOperatorTest('<>'))          && p() && e(1); // <> 通过校验
r($biTest->isAllowedFilterOperatorTest('>'))           && p() && e(1); // > 通过校验
r($biTest->isAllowedFilterOperatorTest('<'))           && p() && e(1); // < 通过校验
r($biTest->isAllowedFilterOperatorTest('>='))          && p() && e(1); // >= 通过校验
r($biTest->isAllowedFilterOperatorTest('<='))          && p() && e(1); // <= 通过校验
r($biTest->isAllowedFilterOperatorTest('IS NULL'))     && p() && e(1); // IS NULL 通过校验
r($biTest->isAllowedFilterOperatorTest('IS NOT NULL')) && p() && e(1); // IS NOT NULL 通过校验
r($biTest->isAllowedFilterOperatorTest(''))            && p() && e(0); // 空字符串不通过校验
r($biTest->isAllowedFilterOperatorTest('AND'))         && p() && e(0); // AND 不通过校验
r($biTest->isAllowedFilterOperatorTest('OR'))          && p() && e(0); // OR 不通过校验
r($biTest->isAllowedFilterOperatorTest('XOR'))         && p() && e(0); // XOR 不通过校验
r($biTest->isAllowedFilterOperatorTest('||'))          && p() && e(0); // || 不通过校验
r($biTest->isAllowedFilterOperatorTest('UNION'))       && p() && e(0); // UNION 不通过校验
r($biTest->isAllowedFilterOperatorTest('SELECT'))      && p() && e(0); // SELECT 不通过校验
r($biTest->isAllowedFilterOperatorTest('--'))          && p() && e(0); // -- 不通过校验
r($biTest->isAllowedFilterOperatorTest('#'))           && p() && e(0); // # 不通过校验
r($biTest->isAllowedFilterOperatorTest('/*'))          && p() && e(0); // /* 不通过校验
r($biTest->isAllowedFilterOperatorTest(';'))           && p() && e(0); // ; 不通过校验
r($biTest->isAllowedFilterOperatorTest('= 1'))         && p() && e(0); // = 1 不通过校验
r($biTest->isAllowedFilterOperatorTest('LIKE BINARY')) && p() && e(0); // LIKE BINARY 不通过校验
