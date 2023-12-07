#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getDBFile();
timeout=0
cid=1

- 获取代号为code1的插件包安装SQL文件。 @/var/web/ztpms/extension/pkg/code1/db/install.sql
- 获取代号为code1的插件包升级SQL文件。 @/var/web/ztpms/extension/pkg/code1/db/upgrade.sql
- 获取代号为code1的插件包其他SQL文件。 @/var/web/ztpms/extension/pkg/code1/db/delete.sql
- 获取代号为zentaopatch的安装SQL文件。 @/var/web/ztpms/extension/pkg/zentaopatch/db/install.sql
- 获取代号为zentaopatch的升级SQL文件。 @/var/web/ztpms/extension/pkg/zentaopatch/db/upgrade.sql
- 获取代号为zentaopatch的其他SQL文件。 @/var/web/ztpms/extension/pkg/zentaopatch/db/delete.sql

*/

global $tester;
$tester->loadModel('extension');

r($tester->extension->getDBFile('code1', 'install'))        && p() && e('/var/web/ztpms/extension/pkg/code1/db/install.sql');       // 获取代号为code1的插件包安装SQL文件。
r($tester->extension->getDBFile('code1', 'upgrade'))        && p() && e('/var/web/ztpms/extension/pkg/code1/db/upgrade.sql');       // 获取代号为code1的插件包升级SQL文件。
r($tester->extension->getDBFile('code1', 'delete'))         && p() && e('/var/web/ztpms/extension/pkg/code1/db/delete.sql');        // 获取代号为code1的插件包其他SQL文件。
r($tester->extension->getDBFile('zentaopatch', 'install'))  && p() && e('/var/web/ztpms/extension/pkg/zentaopatch/db/install.sql'); // 获取代号为zentaopatch的安装SQL文件。
r($tester->extension->getDBFile('zentaopatch', 'upgrade'))  && p() && e('/var/web/ztpms/extension/pkg/zentaopatch/db/upgrade.sql'); // 获取代号为zentaopatch的升级SQL文件。
r($tester->extension->getDBFile('zentaopatch', 'delete'))   && p() && e('/var/web/ztpms/extension/pkg/zentaopatch/db/delete.sql');  // 获取代号为zentaopatch的其他SQL文件。
