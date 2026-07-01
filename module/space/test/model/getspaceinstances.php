#!/usr/bin/env php
<?php
/**

title=测试 spaceModel->getSpaceInstances();
cid=18396

- 获取状态为空的应用列表 @0
- 获取全部应用第 1 页的数量 @5
- 获取全部应用第 1 页第一条记录 @5,应用20,5,1,stopped
- 获取全部应用第 2 页第一条记录 @5,应用15,5,1,abnormal
- 获取每页 10 条时第 2 页第一条记录 @5,应用10,5,1,running
- 获取每页 20 条且页码越界时返回第一页数据 @5,应用20,5,1,stopped
- 按名称搜索应用后第 1 页第一条记录 @5,应用20,5,1,stopped
- 获取运行中应用第 1 页第一条记录 @4,应用19,4,1,running
- 获取运行中应用第 2 页数量 @2
- 获取停止应用第 1 页第一条记录 @5,应用20,5,1,stopped
- 获取停止应用第 2 页数量 @2
- 获取异常应用第 1 页第一条记录 @3,应用18,3,1,abnormal
- 获取异常应用第 2 页数量 @1
- 获取测试状态应用 @0
- 获取空间 1 的空状态应用列表 @0
- 获取空间 1 的全部应用数量 @4
- 获取空间 1 的全部应用第一条记录 @1,应用16,1,1,running
- 获取空间 1 的运行中应用第一条记录 @1,应用16,1,1,running
- 获取空间 1 的停止应用第一条记录 @1,应用11,1,1,stopped
- 获取空间 1 的异常应用第一条记录 @1,应用6,1,1,abnormal
- 获取空间 1 的测试状态应用 @0
- 获取空间 1 按名称搜索后的第一条记录 @1,应用16,1,1,running
- 获取不存在空间的应用列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('space')->loadYaml('space')->gen(5);
zenData('instance')->loadYaml('instance')->gen(20);

$spaceTester = new spaceModelTest();
r($spaceTester->getSpaceInstancesTest(0, '', '', 5, 1))                                     && p()                                    && e('0');                    // 获取状态为空的应用列表
r(count($spaceTester->getSpaceInstancesTest(0, 'all', '', 5, 1)))                           && p()                                    && e('5');                    // 获取全部应用第 1 页的数量
r($spaceTester->getSpaceInstancesTest(0, 'all', '', 5, 1))                                  && p('20:space,name,appID,version,status') && e('5,应用20,5,1,stopped'); // 获取全部应用第 1 页第一条记录
r($spaceTester->getSpaceInstancesTest(0, 'all', '', 5, 2))                                  && p('15:space,name,appID,version,status') && e('5,应用15,5,1,abnormal'); // 获取全部应用第 2 页第一条记录
r($spaceTester->getSpaceInstancesTest(0, 'all', '', 10, 2))                                 && p('10:space,name,appID,version,status') && e('5,应用10,5,1,running'); // 获取每页 10 条时第 2 页第一条记录
r($spaceTester->getSpaceInstancesTest(0, 'all', '', 20, 2))                                 && p('20:space,name,appID,version,status') && e('5,应用20,5,1,stopped'); // 获取每页 20 条且页码越界时返回第一页数据
r($spaceTester->getSpaceInstancesTest(0, 'all', '应用', 5, 1))                              && p('20:space,name,appID,version,status') && e('5,应用20,5,1,stopped'); // 按名称搜索应用后第 1 页第一条记录
r($spaceTester->getSpaceInstancesTest(0, 'running', '', 5, 1))                              && p('19:space,name,appID,version,status') && e('4,应用19,4,1,running'); // 获取运行中应用第 1 页第一条记录
r(count($spaceTester->getSpaceInstancesTest(0, 'running', '', 5, 2)))                       && p()                                    && e('2');                    // 获取运行中应用第 2 页数量
r($spaceTester->getSpaceInstancesTest(0, 'stopped', '', 5, 1))                              && p('20:space,name,appID,version,status') && e('5,应用20,5,1,stopped'); // 获取停止应用第 1 页第一条记录
r(count($spaceTester->getSpaceInstancesTest(0, 'stopped', '', 5, 2)))                       && p()                                    && e('2');                    // 获取停止应用第 2 页数量
r($spaceTester->getSpaceInstancesTest(0, 'abnormal', '', 5, 1))                             && p('18:space,name,appID,version,status') && e('3,应用18,3,1,abnormal'); // 获取异常应用第 1 页第一条记录
r(count($spaceTester->getSpaceInstancesTest(0, 'abnormal', '', 5, 2)))                      && p()                                    && e('1');                    // 获取异常应用第 2 页数量
r($spaceTester->getSpaceInstancesTest(0, 'test', '', 5, 1))                                 && p()                                    && e('0');                    // 获取测试状态应用
r($spaceTester->getSpaceInstancesTest(1, '', '', 5, 1))                                     && p()                                    && e('0');                    // 获取空间 1 的空状态应用列表
r(count($spaceTester->getSpaceInstancesTest(1, 'all', '', 5, 1)))                           && p()                                    && e('4');                    // 获取空间 1 的全部应用数量
r($spaceTester->getSpaceInstancesTest(1, 'all', '', 5, 1))                                  && p('16:space,name,appID,version,status') && e('1,应用16,1,1,running'); // 获取空间 1 的全部应用第一条记录
r($spaceTester->getSpaceInstancesTest(1, 'running', '', 5, 1))                              && p('16:space,name,appID,version,status') && e('1,应用16,1,1,running'); // 获取空间 1 的运行中应用第一条记录
r($spaceTester->getSpaceInstancesTest(1, 'stopped', '', 5, 1))                              && p('11:space,name,appID,version,status') && e('1,应用11,1,1,stopped'); // 获取空间 1 的停止应用第一条记录
r($spaceTester->getSpaceInstancesTest(1, 'abnormal', '', 5, 1))                             && p('6:space,name,appID,version,status')  && e('1,应用6,1,1,abnormal'); // 获取空间 1 的异常应用第一条记录
r($spaceTester->getSpaceInstancesTest(1, 'test', '', 5, 1))                                 && p()                                    && e('0');                    // 获取空间 1 的测试状态应用
r($spaceTester->getSpaceInstancesTest(1, 'all', '应用', 5, 1))                              && p('16:space,name,appID,version,status') && e('1,应用16,1,1,running'); // 获取空间 1 按名称搜索后的第一条记录
r($spaceTester->getSpaceInstancesTest(6, 'all', '', 5, 1))                                  && p()                                    && e('0');                    // 获取不存在空间的应用列表
