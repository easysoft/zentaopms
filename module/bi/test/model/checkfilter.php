#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::checkFilter();
timeout=0
cid=0

- 合法等于过滤通过校验 @1
- 合法 IN 字面量列表通过校验 @1
- 合法 IS NULL 过滤通过校验 @1
- BETWEEN 字面量通过校验 @1
- 过滤值注入 payload 被拒绝 @0
- 报错注入 payload 被拒绝 @0
- 过滤操作符非白名单被拒绝 @0
- 过滤字段名非法被拒绝 @0
- 过滤字段名为空被拒绝 @0

*/

$bi = new biModelTest();

r($bi->checkFilterTest('name', '=', "'a'"))                        && p() && e(1); // 合法等于过滤通过校验
r($bi->checkFilterTest('name', 'IN', "('a', 'b')"))                && p() && e(1); // 合法 IN 字面量列表通过校验
r($bi->checkFilterTest('name', 'IS NULL', ''))                     && p() && e(1); // 合法 IS NULL 过滤通过校验
r($bi->checkFilterTest('createdDate', 'BETWEEN', "'2024-01-01' and '2024-01-02'")) && p() && e(1); // BETWEEN 字面量通过校验
r($bi->checkFilterTest('name', '=', "'a' and sleep(3) and '1'='1'")) && p() && e(0); // 过滤值注入 payload 被拒绝
r($bi->checkFilterTest('name', '=', "'a' and extractvalue(1,concat(0x7e,(select password from zt_user limit 1)))")) && p() && e(0); // 报错注入 payload 被拒绝
r($bi->checkFilterTest('name', '= 1 or 1=1', "'a'"))               && p() && e(0); // 过滤操作符非白名单被拒绝
r($bi->checkFilterTest('name`', '=', "'a'"))                       && p() && e(0); // 过滤字段名非法被拒绝
r($bi->checkFilterTest('', '=', "'a'"))                            && p() && e(0); // 过滤字段名为空被拒绝
