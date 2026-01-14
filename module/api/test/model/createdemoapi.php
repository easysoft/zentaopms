#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createDemoApi();
timeout=0
cid=15091

- 执行apiTest模块的createDemoApiTest方法，参数是1, '16.0', array  @1
- 执行apiTest模块的createDemoApiTest方法，参数是2, '16.0', array  @1
- 执行apiTest模块的createDemoApiTest方法，参数是3, '16.0', array  @1
- 执行apiTest模块的createDemoApiTest方法，参数是4, '16.0', array  @1
- 执行apiTest模块的createDemoApiTest方法，参数是5, '16.0', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doclib')->loadYaml('doclib_createdemoapi', false, 2)->gen(5);
$moduleTable = zenData('module');
$moduleTable->id->range('2949-2964');
$moduleTable->root->range('1{16}');
$moduleTable->type->range('api{16}');
$moduleTable->name->range('用户模块,产品模块,项目模块,测试模块,文档模块,系统模块,管理模块,报表模块,设置模块,API模块,扩展模块,工具模块,插件模块,配置模块,接口模块,服务模块');
$moduleTable->path->range(',2949,{1},2950,{1},2951,{1},2952,{1},2953,{1},2954,{1},2955,{1},2956,{1},2957,{1},2958,{1},2959,{1},2960,{1},2961,{1},2962,{1},2963,{1},2964,{1}');
$moduleTable->grade->range('1{16}');
$moduleTable->order->range('1-16');
$moduleTable->deleted->range('0{16}');
$moduleTable->gen(16);

su('admin');

$apiTest = new apiModelTest();

r(is_array($apiTest->createDemoApiTest(1, '16.0', array(2949 => 2949, 2950 => 2950, 2951 => 2951, 2952 => 2952, 2953 => 2953, 2954 => 2954, 2955 => 2955, 2956 => 2956, 2957 => 2957, 2958 => 2958, 2959 => 2959, 2960 => 2960, 2961 => 2961, 2962 => 2962, 2963 => 2963, 2964 => 2964), 'admin'))) && p() && e(1);
r(is_array($apiTest->createDemoApiTest(2, '16.0', array(2949 => 2949, 2950 => 2950, 2951 => 2951, 2952 => 2952, 2953 => 2953, 2954 => 2954, 2955 => 2955, 2956 => 2956, 2957 => 2957, 2958 => 2958, 2959 => 2959, 2960 => 2960, 2961 => 2961, 2962 => 2962, 2963 => 2963, 2964 => 2964), 'user1'))) && p() && e(1);
r(is_array($apiTest->createDemoApiTest(3, '16.0', array(2949 => 2949, 2950 => 2950, 2951 => 2951, 2952 => 2952, 2953 => 2953, 2954 => 2954, 2955 => 2955, 2956 => 2956, 2957 => 2957, 2958 => 2958, 2959 => 2959, 2960 => 2960, 2961 => 2961, 2962 => 2962, 2963 => 2963, 2964 => 2964), 'admin'))) && p() && e(1);
r(is_array($apiTest->createDemoApiTest(4, '16.0', array(2949 => 2949, 2950 => 2950, 2951 => 2951, 2952 => 2952, 2953 => 2953, 2954 => 2954, 2955 => 2955, 2956 => 2956, 2957 => 2957, 2958 => 2958, 2959 => 2959, 2960 => 2960, 2961 => 2961, 2962 => 2962, 2963 => 2963, 2964 => 2964), 'admin'))) && p() && e(1);
r(count($apiTest->createDemoApiTest(5, '16.0', array(2949 => 2949, 2950 => 2950, 2951 => 2951, 2952 => 2952, 2953 => 2953, 2954 => 2954, 2955 => 2955, 2956 => 2956, 2957 => 2957, 2958 => 2958, 2959 => 2959, 2960 => 2960, 2961 => 2961, 2962 => 2962, 2963 => 2963, 2964 => 2964), 'test')) > 0) && p() && e(1);