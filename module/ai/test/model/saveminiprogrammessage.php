#!/usr/bin/env php
<?php

/**

title=测试 aiModel::saveMiniProgramMessage();
timeout=0
cid=15062

- 执行aiTest模块的saveMiniProgramMessageTest方法，参数是1, 'req', '这是一个测试请求消息'  @1
- 执行aiTest模块的saveMiniProgramMessageTest方法，参数是2, 'res', '这是一个测试响应消息'  @1
- 执行aiTest模块的saveMiniProgramMessageTest方法，参数是3, 'ntf', '这是一个测试通知消息'  @1
- 执行aiTest模块的saveMiniProgramMessageTest方法，参数是4, 'req', ''  @1
- 执行aiTest模块的saveMiniProgramMessageTest方法，参数是5, 'res', '这是一个超长文本内容用于测试边界情况这是一个超长文本内容用于测试边界情况这是一个超长文本内容用于测试边界情况这是一个超长文本内容用于测试边界情况这是一个超长文本内容用于测试边界情况'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

zendata('ai_message')->loadYaml('ai_message_saveminiprogrammessage', false, 2)->gen(0);

su('admin');

$aiTest = new aiTest();

r($aiTest->saveMiniProgramMessageTest(1, 'req', '这是一个测试请求消息')) && p() && e('1');
r($aiTest->saveMiniProgramMessageTest(2, 'res', '这是一个测试响应消息')) && p() && e('1');
r($aiTest->saveMiniProgramMessageTest(3, 'ntf', '这是一个测试通知消息')) && p() && e('1');
r($aiTest->saveMiniProgramMessageTest(4, 'req', '')) && p() && e('1');
r($aiTest->saveMiniProgramMessageTest(5, 'res', '这是一个超长文本内容用于测试边界情况这是一个超长文本内容用于测试边界情况这是一个超长文本内容用于测试边界情况这是一个超长文本内容用于测试边界情况这是一个超长文本内容用于测试边界情况')) && p() && e('1');