#!/usr/bin/env php
<?php
/**

title=测试 spaceModel->getExternalAppByApp();
cid=1

- 获取域名为空的外部应用信息
 - 属性type @gitlab
 - 属性name @gitLab
 - 属性url @https://gitlabdev.qc.oop.cc/
 - 属性account @root
- 获取域名为gitlabdev的外部应用信息
 - 属性type @gitlab
 - 属性name @gitLab
 - 属性url @https://gitlabdev.qc.oop.cc/
 - 属性account @root
- 获取域名为sonardev的外部应用信息
 - 属性type @sonarqube
 - 属性name @SonarQube
 - 属性url @https://sonardev.qc.oop.cc/
 - 属性account @sonar
- 获取域名为giteadev的外部应用信息
 - 属性type @gitea
 - 属性name @gitea
 - 属性url @https://giteadev.qc.oop.cc/
 - 属性account @gitea
- 获取域名为gogsdev的外部应用信息
 - 属性type @gogs
 - 属性name @Gogs
 - 属性url @https://gogsdev.qc.oop.cc/
 - 属性account @gogs-admin
- 获取域名为jenkinsdev的外部应用信息
 - 属性type @jenkins
 - 属性name @Jenkins
 - 属性url @https://jenkinsdev.qc.oop.cc/
 - 属性account @jenkins
- 获取域名不存在的外部应用信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/space.class.php';

zdTable('user')->gen(5);
zdTable('pipeline')->config('pipeline')->gen(5);

$domains = array('', 'gitlabdev', 'sonardev', 'giteadev', 'gogsdev', 'jenkinsdev', 'testdev');

$spaceTester = new spaceTest();
r($spaceTester->getExternalAppByAppTest($domains[0])) && p('type,name,url,account') && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取域名为空的外部应用信息
r($spaceTester->getExternalAppByAppTest($domains[1])) && p('type,name,url,account') && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取域名为gitlabdev的外部应用信息
r($spaceTester->getExternalAppByAppTest($domains[2])) && p('type,name,url,account') && e('sonarqube,SonarQube,https://sonardev.qc.oop.cc/,sonar'); // 获取域名为sonardev的外部应用信息
r($spaceTester->getExternalAppByAppTest($domains[3])) && p('type,name,url,account') && e('gitea,gitea,https://giteadev.qc.oop.cc/,gitea');         // 获取域名为giteadev的外部应用信息
r($spaceTester->getExternalAppByAppTest($domains[4])) && p('type,name,url,account') && e('gogs,Gogs,https://gogsdev.qc.oop.cc/,gogs-admin');       // 获取域名为gogsdev的外部应用信息
r($spaceTester->getExternalAppByAppTest($domains[5])) && p('type,name,url,account') && e('jenkins,Jenkins,https://jenkinsdev.qc.oop.cc/,jenkins'); // 获取域名为jenkinsdev的外部应用信息
r($spaceTester->getExternalAppByAppTest($domains[6])) && p()                        && e('0');                                                     // 获取域名不存在的外部应用信息
