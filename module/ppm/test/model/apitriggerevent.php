#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::apiTriggerEvent();
timeout=0
cid=0

- 执行ppmModel模块的apiTriggerEventTest方法，参数是42, 81, 'create' 属性apiMessage @尝试认证失败[the acting principal is not authenticated]
- 执行ppmModel模块的apiTriggerEventTest方法，参数是42, 81, 'close' 属性apiMessage @尝试认证失败[the acting principal is not authenticated]
- 执行ppmModel模块的apiTriggerEventTest方法，参数是42, 81, 'reopen' 属性apiMessage @尝试认证失败[the acting principal is not authenticated]
- 执行ppmModel模块的apiTriggerEventTest方法，参数是42, 81, 'merge' 属性apiMessage @尝试认证失败[the acting principal is not authenticated]
- 执行ppmModel模块的apiTriggerEventTest方法，参数是42, 81, 'custom' 属性apiMessage @尝试认证失败[the acting principal is not authenticated]

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

$ppmModel = new ppmModelTest();

r($ppmModel->apiTriggerEventTest(42, 81, 'create')) && p('apiMessage') && e('尝试认证失败[the acting principal is not authenticated]');
r($ppmModel->apiTriggerEventTest(42, 81, 'close')) && p('apiMessage') && e('尝试认证失败[the acting principal is not authenticated]');
r($ppmModel->apiTriggerEventTest(42, 81, 'reopen')) && p('apiMessage') && e('尝试认证失败[the acting principal is not authenticated]');
r($ppmModel->apiTriggerEventTest(42, 81, 'merge')) && p('apiMessage') && e('尝试认证失败[the acting principal is not authenticated]');
r($ppmModel->apiTriggerEventTest(42, 81, 'custom')) && p('apiMessage') && e('尝试认证失败[the acting principal is not authenticated]');