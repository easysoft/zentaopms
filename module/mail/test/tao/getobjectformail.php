#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getObjectForMail();
cid=0

- 不传入任何参数 @0
- 只传入objectType参数 @0
- 只传入objectID参数 @0
- 传入不存在的objectID @0
- 传入不存在的objectType @0
- 获取测试单ID=1的数据
 - 属性id @1
 - 属性product @1
 - 属性build @11
 - 属性name @测试单1
- 获取文档ID=2的数据
 - 属性id @2
 - 属性lib @2
 - 属性type @markdown
 - 属性title @文档标题2
 - 属性content @<p>文档正文2</p>
- 获取需求ID=1的数据
 - 属性id @1
 - 属性product @1
 - 属性type @requirement
 - 属性title @用户需求版本一1
 - 属性spec @这是一个软件需求描述1
 - 属性verify @这是一个需求验收1
- 获取任务ID=1的数据
 - 属性id @1
 - 属性project @11
 - 属性execution @101
 - 属性name @开发任务11
 - 属性desc @这里是任务描述1
 - 属性type @design
 - 属性status @wait
- 获取Bug ID=1的数据
 - 属性id @1
 - 属性product @1
 - 属性title @BUG1
 - 属性steps @<p>【步骤】</p><br/><p>【结果】</p><br/><p>【期望】</p><br/>
 - 属性status @active
- 获取看板卡片ID=1的数据
 - 属性id @1
 - 属性kanban @1
 - 属性name @卡片1
 - 属性status @doing
 - 属性assignedTo @admin
- 获取发布ID=1的数据
 - 属性id @1
 - 属性product @1
 - 属性build @1
 - 属性name @产品正常的正常的发布1
 - 属性status @normal

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$testtask = zdTable('testtask');
$testtask->createdBy->range('admin');
$testtask->createdDate->range('`' . date('Y-m-d H:i:s') . '`');
$testtask->gen(2);
$doc = zdTable('doc');
$doc->version->range('1');
$doc->gen(2);
zdTable('doccontent')->gen(5);
zdTable('task')->gen(2);
$story = zdTable('story');
$story->version->range('1');
$story->gen(2);
zdTable('storyspec')->gen(2);
zdTable('bug')->gen(2);
zdTable('kanbancard')->gen(2);
zdTable('release')->gen(2);
zdTable('product')->gen(2);
$project = zdTable('project');
$project->id->range('101-105');
$project->name->range('1-5')->prefix('迭代');
$project->gen(2);

global $tester;
$mailModel = $tester->loadModel('mail');

r($mailModel->getObjectForMail('', 0))                  && p()                                             && e('0');                                                                            //不传入任何参数
r($mailModel->getObjectForMail('testtask', 0))          && p()                                             && e('0');                                                                            //只传入objectType参数
r($mailModel->getObjectForMail('', 1))                  && p()                                             && e('0');                                                                            //只传入objectID参数
r($mailModel->getObjectForMail('testtask', 10))         && p()                                             && e('0');                                                                            //传入不存在的objectID
r($mailModel->getObjectForMail('test', 1))              && p()                                             && e('0');                                                                            //传入不存在的objectType
r((array)$mailModel->getObjectForMail('testtask', 1))   && p('id,product,build,name')                      && e('1,1,11,测试单1');                                                               //获取测试单ID=1的数据
r((array)$mailModel->getObjectForMail('doc', 2))        && p('id,lib,type,title,content')                  && e('2,2,markdown,文档标题2,<p>文档正文2</p>');                                      //获取文档ID=2的数据
r((array)$mailModel->getObjectForMail('story', 1))      && p('id,product,type,title,spec,verify')          && e('1,1,requirement,用户需求版本一1,这是一个软件需求描述1,这是一个需求验收1');      //获取需求ID=1的数据
r((array)$mailModel->getObjectForMail('task', 1))       && p('id,project,execution,name,desc,type,status') && e('1,11,101,开发任务11,这里是任务描述1,design,wait');                              //获取任务ID=1的数据
r((array)$mailModel->getObjectForMail('bug', 1))        && p('id,product,title,steps,status')              && e('1,1,BUG1,<p>【步骤】</p><br/><p>【结果】</p><br/><p>【期望】</p><br/>,active'); //获取Bug ID=1的数据
r((array)$mailModel->getObjectForMail('kanbancard', 1)) && p('id,kanban,name,status,assignedTo')           && e('1,1,卡片1,doing,admin');                                                        //获取看板卡片ID=1的数据
r((array)$mailModel->getObjectForMail('release', 1))    && p('id,product,build,name,status')               && e('1,1,1,产品正常的正常的发布1,normal');                                           //获取发布ID=1的数据
