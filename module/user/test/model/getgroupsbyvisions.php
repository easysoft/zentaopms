#!/usr/bin/env php
<?php

/**

title=测试 userModel->getGroupsByVisions();
timeout=0
cid=19612

- 传空字符串，返回空数组。 @0
- 传只包含逗号的字符串，返回空数组。 @0
- 传空数组，返回空数组。 @0
- 以字符串传参，研发管理界面下有 3 个权限组。 @3
- 以字符串传参，研发管理界面下的权限组分别是 ADMIN、DEV 和 QA。
 - 属性1 @ADMIN
 - 属性2 @DEV
 - 属性3 @QA
- 以数组传参，研发管理界面下有 3 个权限组。 @3
- 以数组传参，研发管理界面下的权限组分别是 ADMIN、DEV 和 QA。
 - 属性1 @ADMIN
 - 属性2 @DEV
 - 属性3 @QA
- 运营管理界面下有 3 个权限组。 @3
- 运营管理界面下的权限组分别是 LITEADMIN、LITEPROJECT 和 LITETEAM。
 - 属性4 @LITEADMIN
 - 属性5 @LITEPROJECT
 - 属性6 @LITETEAM
- 市场和需求管理界面下有 3 个权限组。 @3
- 市场和需求管理界面下的权限组分别是 IPDADMIN、IPDDEMAND 和 IPDPMT。
 - 属性7 @IPDADMIN
 - 属性8 @IPDDEMAND
 - 属性9 @IPDPMT
- 研发管理界面和运营管理界面下有 6 个权限组。 @6
- 研发管理界面下的权限组分别是 ADMIN、DEV 和 QA。
 - 属性1 @研发综合界面 / ADMIN
 - 属性2 @研发综合界面 / DEV
 - 属性3 @研发综合界面 / QA
- 运营管理界面下的权限组分别是 LITEADMIN、LITEPROJECT 和 LITETEAM。
 - 属性4 @运营管理界面 / LITEADMIN
 - 属性5 @运营管理界面 / LITEPROJECT
 - 属性6 @运营管理界面 / LITETEAM
- 研发管理界面和市场和需求管理界面下有 6 个权限组。 @6
- 研发管理界面下的权限组分别是 ADMIN、DEV 和 QA。
 - 属性1 @研发综合界面 / ADMIN
 - 属性2 @研发综合界面 / DEV
 - 属性3 @研发综合界面 / QA
- 市场和需求管理界面下的权限组分别是 IPDADMIN、IPDDEMAND 和 IPDPMT。
 - 属性7 @IPDADMIN
 - 属性8 @IPDDEMAND
 - 属性9 @IPDPMT
- 运营管理界面和市场和需求管理界面下有 6 个权限组。 @6
- 运营管理界面下的权限组分别是 LITEADMIN、LITEPROJECT 和 LITETEAM。
 - 属性4 @运营管理界面 / LITEADMIN
 - 属性5 @运营管理界面 / LITEPROJECT
 - 属性6 @运营管理界面 / LITETEAM
- 市场和需求管理界面下的权限组分别是 IPDADMIN、IPDDEMAND 和 IPDPMT。
 - 属性7 @IPDADMIN
 - 属性8 @IPDDEMAND
 - 属性9 @IPDPMT
- 研发管理界面、运营管理界面和市场和需求管理界面下有 9 个权限组。 @9
- 研发管理界面下的权限组分别是 ADMIN、DEV 和 QA。
 - 属性1 @研发综合界面 / ADMIN
 - 属性2 @研发综合界面 / DEV
 - 属性3 @研发综合界面 / QA
- 运营管理界面下的权限组分别是 LITEADMIN、LITEPROJECT 和 LITETEAM。
 - 属性4 @运营管理界面 / LITEADMIN
 - 属性5 @运营管理界面 / LITEPROJECT
 - 属性6 @运营管理界面 / LITETEAM
- 市场和需求管理界面下的权限组分别是 IPDADMIN、IPDDEMAND 和 IPDPMT。
 - 属性7 @IPDADMIN
 - 属性8 @IPDDEMAND
 - 属性9 @IPDPMT

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

$table = zenData('group');
$table->vision->range('rnd{3},lite{3},or{3}');
$table->name->range('ADMIN,DEV,QA,LITEADMIN,LITEPROJECT,LITETEAM,IPDADMIN,IPDDEMAND,IPDPMT');
$table->gen(9);

su('admin');

global $config, $app;
$config->edition = 'open';
$app::$loadedLangs = array();
$app->loadLang('common');

$userTest = new userTest();

r($userTest->getGroupsByVisionsTest(''))      && p() && e(0); // 传空字符串，返回空数组。
r($userTest->getGroupsByVisionsTest(',,'))    && p() && e(0); // 传只包含逗号的字符串，返回空数组。
r($userTest->getGroupsByVisionsTest(array())) && p() && e(0); // 传空数组，返回空数组。

$groups = $userTest->getGroupsByVisionsTest('rnd');
r(count($groups)) && p()        && e(3);              // 以字符串传参，研发管理界面下有 3 个权限组。
r($groups)        && p('1,2,3') && e('ADMIN,DEV,QA'); // 以字符串传参，研发管理界面下的权限组分别是 ADMIN、DEV 和 QA。

$groups = $userTest->getGroupsByVisionsTest(array('rnd'));
r(count($groups)) && p()        && e(3);              // 以数组传参，研发管理界面下有 3 个权限组。
r($groups)        && p('1,2,3') && e('ADMIN,DEV,QA'); // 以数组传参，研发管理界面下的权限组分别是 ADMIN、DEV 和 QA。

$groups = $userTest->getGroupsByVisionsTest('lite');
r(count($groups)) && p()        && e(3);                                // 运营管理界面下有 3 个权限组。
r($groups)        && p('4,5,6') && e('LITEADMIN,LITEPROJECT,LITETEAM'); // 运营管理界面下的权限组分别是 LITEADMIN、LITEPROJECT 和 LITETEAM。

$groups = $userTest->getGroupsByVisionsTest('or');
r(count($groups)) && p()        && e(3);                           // 市场和需求管理界面下有 3 个权限组。
r($groups)        && p('7,8,9') && e('IPDADMIN,IPDDEMAND,IPDPMT'); // 市场和需求管理界面下的权限组分别是 IPDADMIN、IPDDEMAND 和 IPDPMT。

$groups = $userTest->getGroupsByVisionsTest('rnd,lite');
r(count($groups)) && p()        && e(6);                                                                             // 研发管理界面和运营管理界面下有 6 个权限组。
r($groups)        && p('1,2,3') && e('研发综合界面 / ADMIN,研发综合界面 / DEV,研发综合界面 / QA');                   // 研发管理界面下的权限组分别是 ADMIN、DEV 和 QA。
r($groups)        && p('4,5,6') && e('运营管理界面 / LITEADMIN,运营管理界面 / LITEPROJECT,运营管理界面 / LITETEAM'); // 运营管理界面下的权限组分别是 LITEADMIN、LITEPROJECT 和 LITETEAM。

$groups = $userTest->getGroupsByVisionsTest('rnd,or');
r(count($groups)) && p()        && e(6);                                                           // 研发管理界面和市场和需求管理界面下有 6 个权限组。
r($groups)        && p('1,2,3') && e('研发综合界面 / ADMIN,研发综合界面 / DEV,研发综合界面 / QA'); // 研发管理界面下的权限组分别是 ADMIN、DEV 和 QA。
r($groups)        && p('7,8,9') && e('IPDADMIN,IPDDEMAND,IPDPMT');                                 // 市场和需求管理界面下的权限组分别是 IPDADMIN、IPDDEMAND 和 IPDPMT。

$groups = $userTest->getGroupsByVisionsTest('lite,or');
r(count($groups)) && p()        && e(6);                                                                             // 运营管理界面和市场和需求管理界面下有 6 个权限组。
r($groups)        && p('4,5,6') && e('运营管理界面 / LITEADMIN,运营管理界面 / LITEPROJECT,运营管理界面 / LITETEAM'); // 运营管理界面下的权限组分别是 LITEADMIN、LITEPROJECT 和 LITETEAM。
r($groups)        && p('7,8,9') && e('IPDADMIN,IPDDEMAND,IPDPMT');                                                   // 市场和需求管理界面下的权限组分别是 IPDADMIN、IPDDEMAND 和 IPDPMT。

$groups = $userTest->getGroupsByVisionsTest('rnd,lite,or');
r(count($groups)) && p()        && e(9);                                                                             // 研发管理界面、运营管理界面和市场和需求管理界面下有 9 个权限组。
r($groups)        && p('1,2,3') && e('研发综合界面 / ADMIN,研发综合界面 / DEV,研发综合界面 / QA');                   // 研发管理界面下的权限组分别是 ADMIN、DEV 和 QA。
r($groups)        && p('4,5,6') && e('运营管理界面 / LITEADMIN,运营管理界面 / LITEPROJECT,运营管理界面 / LITETEAM'); // 运营管理界面下的权限组分别是 LITEADMIN、LITEPROJECT 和 LITETEAM。
r($groups)        && p('7,8,9') && e('IPDADMIN,IPDDEMAND,IPDPMT');                                                   // 市场和需求管理界面下的权限组分别是 IPDADMIN、IPDDEMAND 和 IPDPMT。
