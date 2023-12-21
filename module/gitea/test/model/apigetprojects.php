#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::apiGetProjects();
timeout=0
cid=0

- 错误的服务器ID @0
- 正确的服务器ID，设置不增加sudo参数
 - 第0条的id属性 @1
 - 第0条的full_name属性 @gitea/unittest
- 正确的服务器ID，设置增加sudo参数
 - 第0条的id属性 @1
 - 第0条的full_name属性 @gitea/unittest
- 没有权限的用户，设置增加sudo参数 @0
- 没有权限的用户，设置不增加sudo参数
 - 第0条的id属性 @1
 - 第0条的full_name属性 @gitea/unittest
- 有权限的用户，设置增加sudo参数
 - 第0条的id属性 @1
 - 第0条的html_url属性 @https://giteadev.qc.oop.cc/gitea/unittest
- 有权限的用户，设置不增加sudo参数
 - 第0条的id属性 @1
 - 第0条的html_url属性 @https://giteadev.qc.oop.cc/gitea/unittest

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$giteaModel = $tester->loadModel('gitea');

$giteaID = 1;
r($giteaModel->apiGetProjects($giteaID)) && p() && e('0'); // 错误的服务器ID

$giteaID = 4;
r($giteaModel->apiGetProjects($giteaID, false)) && p('0:id,full_name') && e('1,gitea/unittest'); // 正确的服务器ID，设置不增加sudo参数
r($giteaModel->apiGetProjects($giteaID, true))  && p('0:id,full_name') && e('1,gitea/unittest'); // 正确的服务器ID，设置增加sudo参数

su('user1');
r($giteaModel->apiGetProjects($giteaID, true))  && p()                 && e('0');                // 没有权限的用户，设置增加sudo参数
r($giteaModel->apiGetProjects($giteaID, false)) && p('0:id,full_name') && e('1,gitea/unittest'); // 没有权限的用户，设置不增加sudo参数

su('user2');
r($giteaModel->apiGetProjects($giteaID, true))  && p('0:id,html_url') && e('1,https://giteadev.qc.oop.cc/gitea/unittest'); // 有权限的用户，设置增加sudo参数
r($giteaModel->apiGetProjects($giteaID, false)) && p('0:id,html_url') && e('1,https://giteadev.qc.oop.cc/gitea/unittest'); // 有权限的用户，设置不增加sudo参数