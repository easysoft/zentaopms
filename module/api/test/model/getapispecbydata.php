#!/usr/bin/env php
<?php

/**

title=测试 apiModel::getApiSpecByData();
timeout=0
cid=15103

- 执行apiTest模块的getApiSpecByDataTest方法，参数是$fullData 
 - 属性doc @1
 - 属性title @Test API
 - 属性path @/api/test
 - 属性module @2
 - 属性protocol @HTTP
 - 属性method @POST
- 执行apiTest模块的getApiSpecByDataTest方法，参数是$minData 
 - 属性doc @1
 - 属性title @Min API
 - 属性path @/api/min
 - 属性protocol @HTTP
 - 属性method @GET
 - 属性status @done
 - 属性version @1
- 执行apiTest模块的getApiSpecByDataTest方法，参数是$partialData 
 - 属性doc @1
 - 属性title @Partial API
 - 属性path @/api/partial
 - 属性module @0
 - 属性protocol @HTTP
 - 属性method @GET
 - 属性status @done
- 执行apiTest模块的getApiSpecByDataTest方法，参数是$emptyData 
 - 属性doc @1
 - 属性module @0
 - 属性protocol @HTTP
 - 属性method @GET
 - 属性status @done
- 执行apiTest模块的getApiSpecByDataTest方法，参数是$zeroVersionData 
 - 属性doc @1
 - 属性title @Version Test
 - 属性path @/api/version
 - 属性version @1
 - 属性protocol @HTTP
 - 属性method @GET

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$apiTest = new apiModelTest();

// 用户登录
su('admin');

// 测试步骤1：完整数据输入
$fullData = new stdclass();
$fullData->id = 1;
$fullData->title = 'Test API';
$fullData->path = '/api/test';
$fullData->module = 2;
$fullData->protocol = 'HTTP';
$fullData->method = 'POST';
$fullData->requestType = 'application/json';
$fullData->responseType = 'application/json';
$fullData->status = 'doing';
$fullData->owner = 'testuser';
$fullData->desc = 'Test description';
$fullData->version = 2;
$fullData->params = '{"param1": "value1"}';
$fullData->paramsExample = '{"example": "test"}';
$fullData->responseExample = '{"result": "success"}';
$fullData->response = '{"data": "result"}';

r($apiTest->getApiSpecByDataTest($fullData)) && p('doc,title,path,module,protocol,method') && e('1,Test API,/api/test,2,HTTP,POST');

// 测试步骤2：最小数据输入（包含必需字段）
$minData = new stdclass();
$minData->id = 1;
$minData->title = 'Min API';
$minData->path = '/api/min';

r($apiTest->getApiSpecByDataTest($minData)) && p('doc,title,path,protocol,method,status,version') && e('1,Min API,/api/min,HTTP,GET,done,1');

// 测试步骤3：部分字段缺失测试默认值
$partialData = new stdclass();
$partialData->id = 1;
$partialData->title = 'Partial API';
$partialData->path = '/api/partial';
$partialData->module = '';
$partialData->status = '';

r($apiTest->getApiSpecByDataTest($partialData)) && p('doc,title,path,module,protocol,method,status') && e('1,Partial API,/api/partial,0,HTTP,GET,done');

// 测试步骤4：包含空值字段测试默认值处理
$emptyData = new stdclass();
$emptyData->id = 1;
$emptyData->title = '';
$emptyData->path = '';
$emptyData->module = '';
$emptyData->protocol = '';
$emptyData->method = '';
$emptyData->desc = '';

r($apiTest->getApiSpecByDataTest($emptyData)) && p('doc,module,protocol,method,status') && e('1,0,HTTP,GET,done');

// 测试步骤5：version字段为0
$zeroVersionData = new stdclass();
$zeroVersionData->id = 1;
$zeroVersionData->title = 'Version Test';
$zeroVersionData->path = '/api/version';
$zeroVersionData->version = 0;

r($apiTest->getApiSpecByDataTest($zeroVersionData)) && p('doc,title,path,version,protocol,method') && e('1,Version Test,/api/version,1,HTTP,GET');