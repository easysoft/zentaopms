#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testtask.class.php';

zdTable('testtask')->config('testtask')->gen(18);

su('admin');

/**

title=测试 testtaskModel->getPairs();
cid=1
pid=1

*/

$testtask = new testtaskTest();

r($testtask->getPairsTest(0))    && p() && e(0); // 产品 0 没有测试单。
r($testtask->getPairsTest(1))    && p() && e(0); // 产品 1 没有测试单。
r($testtask->getPairsTest(2))    && p() && e(4); // 产品 2 有 4 个测试单。
r($testtask->getPairsTest(2, 0)) && p() && e(4); // 产品 2 执行 0 有 4 个测试单。
r($testtask->getPairsTest(2, 1)) && p() && e(2); // 产品 2 执行 1 有 2 个测试单。
r($testtask->getPairsTest(2, 2)) && p() && e(2); // 产品 2 执行 2 有 2 个测试单。
r($testtask->getPairsTest(2, 3)) && p() && e(0); // 产品 2 执行 3 有测试单但都是单元测试。
r($testtask->getPairsTest(2, 4)) && p() && e(0); // 产品 2 执行 4 有测试单但都删除了。
r($testtask->getPairsTest(2, 5)) && p() && e(0); // 产品 2 执行 5 没有测试单。

r($testtask->getPairsTest(2, 1, 0))  && p() && e(2); // 产品 2 执行 1 有 2 个测试单，测试单参数为空，最终有 2 个测试单。
r($testtask->getPairsTest(2, 1, 2))  && p() && e(2); // 产品 2 执行 1 有 2 个测试单，测试单 2 是普通测试单但在前面已经包含，最终有 2 个测试单。
r($testtask->getPairsTest(2, 1, 3))  && p() && e(2); // 产品 2 执行 1 有 2 个测试单，测试单 2 是普通测试单但在前面已经包含，最终有 2 个测试单。
r($testtask->getPairsTest(2, 1, 4))  && p() && e(3); // 产品 2 执行 1 有 2 个测试单，测试单 3 是单元测试，最终有 3 个测试单。
r($testtask->getPairsTest(2, 1, 5))  && p() && e(3); // 产品 2 执行 1 有 2 个测试单，测试单 5 是普通测试单，最终有 3 个测试单。
r($testtask->getPairsTest(2, 1, 20)) && p() && e(2); // 产品 2 执行 1 有 2 个测试单，测试单 20 不存在，最终有 2 个测试单。
