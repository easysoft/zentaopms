#!/usr/bin/env php
<?php
/**

title=测试 pipelineModel->getByID();
cid=1

- 获取id为0的流水线信息 @0
- 获取id为1流水线信息
 - 属性type @gitlab
 - 属性name @gitLab
 - 属性url @https://gitlabdev.qc.oop.cc/
 - 属性account @root
 - 属性private @08bcc98f75d7d40053dc80722bdc117b
- 获取id不存在的流水线信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pipeline.class.php';

zdTable('user')->gen(5);
zdTable('pipeline')->config('pipeline')->gen(1);

$idList = array(0, 1, 2);

$pipelineTester = new pipelineTest();
r($pipelineTester->getByIDTest($idList[0])) && p()                                && e('0');                                                                                // 获取id为0的流水线信息
r($pipelineTester->getByIDTest($idList[1])) && p('type,name,url,account,private') && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root,08bcc98f75d7d40053dc80722bdc117b'); // 获取id为1流水线信息
r($pipelineTester->getByIDTest($idList[2])) && p()                                && e('0');                                                                                // 获取id不存在的流水线信息
