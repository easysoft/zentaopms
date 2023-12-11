#!/usr/bin/env php
<?php

/**

title=测试 docModel->getDocQuery();
cid=1

- 测试只查询所有文档库 @1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30')

- 测试只查询所有项目 @1 AND `project` in (11,60,61,100)

- 测试只查询所有产品 @1 AND `product` IN ('1','2','3','4','5')

- 测试只查询所有执行 @1 AND `execution` IN ('104','103','106','105','102','101')

- 测试只查询所有文档库和所有项目 @1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `project` in (11,60,61,100)

- 测试只查询所有文档库和所有产品 @1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `product` IN ('1','2','3','4','5')

- 测试只查询所有文档库和所有执行 @1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `execution` IN ('104','103','106','105','102','101')

- 测试只查询所有项目和所有产品 @1 AND 1 AND `project` in (11,60,61,100) AND `product` IN ('1','2','3','4','5')

- 测试只查询所有项目和所有执行 @1 AND 1 AND `project` in (11,60,61,100) AND `execution` IN ('104','103','106','105','102','101')

- 测试只查询所有产品和所有执行 @1 AND 1 AND `product` IN ('1','2','3','4','5') AND `execution` IN ('104','103','106','105','102','101')

- 测试只查询所有文档库和所有项目和所有产品 @1 AND 1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `project` in (11,60,61,100) AND `product` IN ('1','2','3','4','5')

- 测试只查询所有文档库和所有项目和所有执行 @1 AND 1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `project` in (11,60,61,100) AND `execution` IN ('104','103','106','105','102','101')

- 测试只查询所有文档库和所有产品和所有执行 @1 AND 1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `product` IN ('1','2','3','4','5') AND `execution` IN ('104','103','106','105','102','101')

- 测试只查询所有项目和所有产品和所有执行 @1 AND 1 AND 1 AND `project` in (11,60,61,100) AND `product` IN ('1','2','3','4','5') AND `execution` IN ('104','103','106','105','102','101')

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('project')->config('execution')->gen(10);
zdTable('product')->config('product')->gen(5);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$allLib       = "`lib` = 'all'";
$allProject   = "`project` = 'all'";
$allProduct   = "`product` = 'all'";
$allExecution = "`execution` = 'all'";

$queries[] = $allLib;
$queries[] = $allProject;
$queries[] = $allProduct;
$queries[] = $allExecution;
$queries[] = "{$allLib} AND {$allProject}";
$queries[] = "{$allLib} AND {$allProduct}";
$queries[] = "{$allLib} AND {$allExecution}";
$queries[] = "{$allProject} AND {$allProduct}";
$queries[] = "{$allProject} AND {$allExecution}";
$queries[] = "{$allProduct} AND {$allExecution}";
$queries[] = "{$allLib} AND {$allProject} AND {$allProduct}";
$queries[] = "{$allLib} AND {$allProject} AND {$allExecution}";
$queries[] = "{$allLib} AND {$allProduct} AND {$allExecution}";
$queries[] = "{$allProject} AND {$allProduct} AND {$allExecution}";
$queries[] = "{$allLib} AND {$allProject} AND {$allProduct} AND {$allExecution}";

$docTester = new docTest();
r($docTester->getDocQueryTest($queries[0]))  && p() && e("1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30')");                                                                                                             // 测试只查询所有文档库
r($docTester->getDocQueryTest($queries[1]))  && p() && e("1 AND `project` in (11,60,61,100)");                                                                                                                                                                                                           // 测试只查询所有项目
r($docTester->getDocQueryTest($queries[2]))  && p() && e("1 AND `product` IN ('1','2','3','4','5')");                                                                                                                                                                                                    // 测试只查询所有产品
r($docTester->getDocQueryTest($queries[3]))  && p() && e("1 AND `execution` IN ('104','103','106','105','102','101')");                                                                                                                                                                                  // 测试只查询所有执行
r($docTester->getDocQueryTest($queries[4]))  && p() && e("1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `project` in (11,60,61,100)");                                                                       // 测试只查询所有文档库和所有项目
r($docTester->getDocQueryTest($queries[5]))  && p() && e("1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `product` IN ('1','2','3','4','5')");                                                                // 测试只查询所有文档库和所有产品
r($docTester->getDocQueryTest($queries[6]))  && p() && e("1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `execution` IN ('104','103','106','105','102','101')");                                              // 测试只查询所有文档库和所有执行
r($docTester->getDocQueryTest($queries[7]))  && p() && e("1 AND 1 AND `project` in (11,60,61,100) AND `product` IN ('1','2','3','4','5')");                                                                                                                                                              // 测试只查询所有项目和所有产品
r($docTester->getDocQueryTest($queries[8]))  && p() && e("1 AND 1 AND `project` in (11,60,61,100) AND `execution` IN ('104','103','106','105','102','101')");                                                                                                                                            // 测试只查询所有项目和所有执行
r($docTester->getDocQueryTest($queries[9]))  && p() && e("1 AND 1 AND `product` IN ('1','2','3','4','5') AND `execution` IN ('104','103','106','105','102','101')");                                                                                                                                     // 测试只查询所有产品和所有执行
r($docTester->getDocQueryTest($queries[10])) && p() && e("1 AND 1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `project` in (11,60,61,100) AND `product` IN ('1','2','3','4','5')");                          // 测试只查询所有文档库和所有项目和所有产品
r($docTester->getDocQueryTest($queries[11])) && p() && e("1 AND 1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `project` in (11,60,61,100) AND `execution` IN ('104','103','106','105','102','101')");        // 测试只查询所有文档库和所有项目和所有执行
r($docTester->getDocQueryTest($queries[12])) && p() && e("1 AND 1 AND 1 AND `lib` IN ('6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','25','26','27','28','29','30') AND `product` IN ('1','2','3','4','5') AND `execution` IN ('104','103','106','105','102','101')"); // 测试只查询所有文档库和所有产品和所有执行
r($docTester->getDocQueryTest($queries[13])) && p() && e("1 AND 1 AND 1 AND `project` in (11,60,61,100) AND `product` IN ('1','2','3','4','5') AND `execution` IN ('104','103','106','105','102','101')");                                                                                               // 测试只查询所有项目和所有产品和所有执行
