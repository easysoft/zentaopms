#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getDBFile();
timeout=0
cid=1

- 获取代号为code1的插件包安装SQL文件。 @/apps/zentao/extension/pkg/code1/db/install.sql
- 获取代号为code1的插件包升级SQL文件。 @/apps/zentao/extension/pkg/code1/db/upgrade.sql
- 获取代号为code1的插件包其他SQL文件。 @/apps/zentao/extension/pkg/code1/db/delete.sql
- 获取代号为zentaopatch的安装SQL文件。 @/apps/zentao/extension/pkg/zentaopatch/db/install.sql
- 获取代号为zentaopatch的升级SQL文件。 @/apps/zentao/extension/pkg/zentaopatch/db/upgrade.sql
- 获取代号为zentaopatch的其他SQL文件。 @/apps/zentao/extension/pkg/zentaopatch/db/delete.sql

*/

global $tester;
$tester->loadModel('extension');
$tester->extension->pkgRoot = '/apps/zentao/extension/pkg/';

r($tester->extension->getDBFile('code1', 'install'))        && p() && e('/apps/zentao/extension/pkg/code1/db/install.sql');       // 获取代号为code1的插件包安装SQL文件。
r($tester->extension->getDBFile('code1', 'upgrade'))        && p() && e('/apps/zentao/extension/pkg/code1/db/upgrade.sql');       // 获取代号为code1的插件包升级SQL文件。
r($tester->extension->getDBFile('code1', 'delete'))         && p() && e('/apps/zentao/extension/pkg/code1/db/delete.sql');        // 获取代号为code1的插件包其他SQL文件。
r($tester->extension->getDBFile('zentaopatch', 'install'))  && p() && e('/apps/zentao/extension/pkg/zentaopatch/db/install.sql'); // 获取代号为zentaopatch的安装SQL文件。
r($tester->extension->getDBFile('zentaopatch', 'upgrade'))  && p() && e('/apps/zentao/extension/pkg/zentaopatch/db/upgrade.sql'); // 获取代号为zentaopatch的升级SQL文件。
r($tester->extension->getDBFile('zentaopatch', 'delete'))   && p() && e('/apps/zentao/extension/pkg/zentaopatch/db/delete.sql');  // 获取代号为zentaopatch的其他SQL文件。
