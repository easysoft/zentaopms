#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createFeedbackLink();
timeout=0
cid=19351

- 测试创建module 1 的 caseliblink属性url @feedback-browse-byModule-1.html
- 测试创建module 2 的 caseliblink属性url @feedback-browse-byModule-2.html
- 测试创建module 3 的 caseliblink属性url @feedback-browse-byModule-3.html
- 测试创建module 4 的 caseliblink属性url @feedback-browse-byModule-4.html
- 测试创建module 5 的 caseliblink属性url @feedback-browse-byModule-5.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$tree = new treeModelTest();

r($tree->createFeedbackLinkTest(1)) && p('url') && e("feedback-browse-byModule-1.html"); // 测试创建module 1 的 caseliblink
r($tree->createFeedbackLinkTest(2)) && p('url') && e("feedback-browse-byModule-2.html"); // 测试创建module 2 的 caseliblink
r($tree->createFeedbackLinkTest(3)) && p('url') && e("feedback-browse-byModule-3.html"); // 测试创建module 3 的 caseliblink
r($tree->createFeedbackLinkTest(4)) && p('url') && e("feedback-browse-byModule-4.html"); // 测试创建module 4 的 caseliblink
r($tree->createFeedbackLinkTest(5)) && p('url') && e("feedback-browse-byModule-5.html"); // 测试创建module 5 的 caseliblink