#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/message.class.php';
su('admin');

zdTable('user')->gen(50);
zdTable('todo')->gen(1);
zdTable('testtask')->gen(1);
zdTable('meeting')->config('meeting')->gen(1);
zdTable('mr')->gen(1);
zdTable('release')->gen(1);
zdTable('task')->gen(1);
zdTable('story')->gen(1);

/**

title=测试 messageModel->getToList();
cid=1
pid=1

通过一条todo数据展示      >> admin
通过一条testtask数据展示  >> user3
通过一条meeting数据展示   >> admin,admin,
通过一条mr数据展示        >> admin,admin
通过一条release数据展示   >> admin
通过一条task数据展示      >> 0

*/

$message = new messageTest();

r($message->getToListTest('todo'))     && p() && e('admin');        //通过一条todo数据展示$toList
r($message->getToListTest('testtask')) && p() && e('user3');        //通过一条testtask数据展示$toList
r($message->getToListTest('meeting'))  && p() && e('admin,admin,'); //通过一条meeting数据展示$toList
r($message->getToListTest('mr'))       && p() && e('admin,admin');  //通过一条mr数据展示$toList
r($message->getToListTest('release'))  && p() && e('po1');          //通过一条release数据展示$toList
r($message->getToListTest('task'))     && p() && e('0');            //通过一条task数据展示$toList
