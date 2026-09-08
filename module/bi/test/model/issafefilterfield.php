#!/usr/bin/env php
<?php

/**

title=测试 biModel::isSafeFilterField();
timeout=0
cid=0

- year 通过校验 @1
- field1 通过校验 @1
- _field 通过校验 @1
- year2 通过校验 @1
- tt.year 通过校验 @1
- 空字段名不通过校验 @0
- 以数字开头的字段名不通过校验 @0
- 含有空格的字段名不通过校验 @0
- 含有反引号的字段名不通过校验 @0
- 含有双引号的字段名不通过校验 @0
- 含有单引号的字段名不通过校验 @0
- 含有连字符的字段名不通过校验 @0
- 含有分号的字段名不通过校验 @0
- 含有逗号的字段名不通过校验 @0
- 含有括号的字段名不通过校验 @0
- 含有多个点的字段名不通过校验 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$biTest = new biModelTest();

r($biTest->isSafeFilterFieldTest('year'))    && p() && e(1); // year 通过校验
r($biTest->isSafeFilterFieldTest('field1'))  && p() && e(1); // field1 通过校验
r($biTest->isSafeFilterFieldTest('_field'))  && p() && e(1); // _field 通过校验
r($biTest->isSafeFilterFieldTest('year2'))   && p() && e(1); // year2 通过校验
r($biTest->isSafeFilterFieldTest('tt.year')) && p() && e(1); // tt.year 通过校验
r($biTest->isSafeFilterFieldTest(''))        && p() && e(0); // 空字段名不通过校验
r($biTest->isSafeFilterFieldTest('1year'))   && p() && e(0); // 以数字开头的字段名不通过校验
r($biTest->isSafeFilterFieldTest('a b'))     && p() && e(0); // 含有空格的字段名不通过校验
r($biTest->isSafeFilterFieldTest('a`b'))     && p() && e(0); // 含有反引号的字段名不通过校验
r($biTest->isSafeFilterFieldTest('a"b'))     && p() && e(0); // 含有双引号的字段名不通过校验
r($biTest->isSafeFilterFieldTest("a'b"))     && p() && e(0); // 含有单引号的字段名不通过校验
r($biTest->isSafeFilterFieldTest('a-b'))     && p() && e(0); // 含有连字符的字段名不通过校验
r($biTest->isSafeFilterFieldTest('a;b'))     && p() && e(0); // 含有分号的字段名不通过校验
r($biTest->isSafeFilterFieldTest('a,b'))     && p() && e(0); // 含有逗号的字段名不通过校验
r($biTest->isSafeFilterFieldTest('year()'))  && p() && e(0); // 含有括号的字段名不通过校验
r($biTest->isSafeFilterFieldTest('a.b.c'))   && p() && e(0); // 含有多个点的字段名不通过校验