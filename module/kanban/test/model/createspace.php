#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$zd_user = zenData('user');
$zd_user->id->range('1-6');
$zd_user->account->range('admin,po15,po16,user1,user2,user3');
$zd_user->realname->range('admin,po15,po16,user1,user2,user3');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(6);

su('admin');

/**

title=测试 kanbanModel->createSpace();
timeout=0
cid=16901

- 创建私人空间
 - 属性name @测试创建私人空间
 - 属性type @private
 - 属性desc @私人空间的描述
 - 属性owner @admin
 - 属性team @po15,admin
 - 属性whitelist @po15
- 创建协作空间
 - 属性name @测试创建协作空间
 - 属性type @cooperation
 - 属性desc @协作空间的描述
 - 属性owner @po16
 - 属性team @po15,admin,po16
 - 属性whitelist @~~
- 创建公共空间
 - 属性name @测试创建公共空间
 - 属性type @public
 - 属性desc @公共空间的描述
 - 属性owner @user1
 - 属性team @user2,user3,admin,user1
 - 属性whitelist @~~
- 创建不填写名称的空间第name条的0属性 @『空间名称』不能为空。
- 创建不填写负责人的空间第owner条的0属性 @『负责人』不能为空。

*/

$space1 = new stdclass();
$space1->type            = 'private';
$space1->name            = '测试创建私人空间';
$space1->owner           = '';
$space1->desc            = '私人空间的描述';
$space1->whitelist       = 'po15';
$space1->team            = 'po15';

$space2 = new stdclass();
$space2->type            = 'cooperation';
$space2->name            = '测试创建协作空间';
$space2->owner           = 'po16';
$space2->desc            = '协作空间的描述';
$space2->team            = 'po15';

$space3 = new stdclass();
$space3->type            = 'public';
$space3->name            = '测试创建公共空间';
$space3->owner           = 'user1';
$space3->desc            = '公共空间的描述';
$space3->team            = 'user2,user3';

$space4 = new stdclass();
$space4->type            = 'public';
$space4->name            = '';
$space4->owner           = 'user1';
$space4->desc            = '不填写名字的公共空间描述';
$space4->team            = 'user2,user3';

$space5 = new stdclass();
$space5->type            = 'public';
$space5->name            = '不填写负责人的公共空间';
$space5->owner           = '';
$space5->desc            = '不填写负责人的公共空间描述';
$space5->team            = 'user2,user3';

$kanban = new kanbanModelTest();
r($kanban->createSpaceTest($space1)) && p('name|type|desc|owner|team|whitelist', '|') && e('测试创建私人空间|private|私人空间的描述|admin|po15,admin|po15');               // 创建私人空间
r($kanban->createSpaceTest($space2)) && p('name|type|desc|owner|team|whitelist', '|') && e('测试创建协作空间|cooperation|协作空间的描述|po16|po15,admin,po16|~~');     // 创建协作空间
r($kanban->createSpaceTest($space3)) && p('name|type|desc|owner|team|whitelist', '|') && e('测试创建公共空间|public|公共空间的描述|user1|user2,user3,admin,user1|~~'); // 创建公共空间
r($kanban->createSpaceTest($space4)) && p('name:0')                                   && e('『空间名称』不能为空。');                                                  // 创建不填写名称的空间
r($kanban->createSpaceTest($space5)) && p('owner:0')                                  && e('『负责人』不能为空。');                                                    // 创建不填写负责人的空间