#!/usr/bin/env php
<?php

/**

title=测试 mailTao::getObjectTitle();
timeout=0
cid=0

- 传入空的objectType和objectID @0
- 传入空的objectType但有objectID @0
- 传入空的objectID但有objectType @0
- 传入不存在的objectType @0
- 传入不存在的objectID @0
- 获取testtask对象的标题 @测试单1
- 获取doc对象的标题 @文档标题1
- 获取story对象的标题 @用户需求版本一1
- 获取bug对象的标题 @BUG1
- 获取task对象的标题 @开发任务12
- 获取release对象的标题 @产品正常的正常的发布1
- 获取kanbancard对象的标题 @卡片1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

$mail = new mailTest();

r($mail->getObjectTitleTest('', 0))          && p() && e('0');           // 传入空的objectType和objectID
r($mail->getObjectTitleTest('', 1))          && p() && e('0');           // 传入空的objectType但有objectID
r($mail->getObjectTitleTest('testtask', 0))  && p() && e('0');           // 传入空的objectID但有objectType
r($mail->getObjectTitleTest('invalid', 1))   && p() && e('0');           // 传入不存在的objectType
r($mail->getObjectTitleTest('testtask', 999)) && p() && e('0');          // 传入不存在的objectID
r($mail->getObjectTitleTest('testtask', 1))   && p() && e('测试单1');     // 获取testtask对象的标题
r($mail->getObjectTitleTest('doc', 1))        && p() && e('文档标题1');   // 获取doc对象的标题
r($mail->getObjectTitleTest('story', 1))      && p() && e('用户需求版本一1'); // 获取story对象的标题
r($mail->getObjectTitleTest('bug', 1))        && p() && e('BUG1');       // 获取bug对象的标题
r($mail->getObjectTitleTest('task', 1))       && p() && e('开发任务12');  // 获取task对象的标题
r($mail->getObjectTitleTest('release', 1))    && p() && e('产品正常的正常的发布1'); // 获取release对象的标题
r($mail->getObjectTitleTest('kanbancard', 1)) && p() && e('卡片1');       // 获取kanbancard对象的标题