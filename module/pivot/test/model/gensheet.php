#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 pivotModel->genSheet();
timeout=0
cid=0

- 步骤1：验证分组字段解析正确 @account
- 步骤2：验证列头标签生成正确 @用户名,用户编号的计数(总计百分比)
- 步骤3：验证透视表数据内容正确 @admin,50%;user1,50%
- 步骤4：验证未开启列总计 @0
- 步骤5：验证合并配置生成正确 @1,1;1,1
- 步骤6：验证缺少columns配置时返回空数据 @0
- 步骤7：验证缺少columns配置时返回空合并配置 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$testAccount = 'pivotuser9001';
if(!$tester->dao->select('id')->from(TABLE_USER)->where('account')->eq($testAccount)->fetch())
{
    $user = new stdClass();
    $user->id       = 9001;
    $user->account  = $testAccount;
    $user->realname = '用户1';
    $user->email    = 'pivotuser9001@test.com';
    $user->password = md5('123456');
    $user->deleted  = '0';
    $user->type     = 'inside';
    $user->dept     = 1;
    $user->role     = 'dev';
    $tester->dao->insert(TABLE_USER)->data($user)->exec();
}

$pivot = new pivotModelTest();

$fields = array(
    'account' => array('object' => 'user', 'field' => 'account', 'type' => 'string'),
    'id'      => array('object' => 'user', 'field' => 'id',      'type' => 'number')
);

$settings = array(
    'group1'  => 'account',
    'columns' => array(
        array('field' => 'id', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'total', 'monopolize' => 1, 'showOrigin' => 0)
    )
);

$langs = array('account' => '用户名', 'id' => '用户编号');

list($data, $configs) = $pivot->genSheet($fields, $settings, 'SELECT account, id FROM zt_user WHERE account IN ("admin", "pivotuser9001") ORDER BY id', false, $langs);
list($emptyData, $emptyConfigs) = $pivot->genSheet($fields, array('group1' => 'account'), 'SELECT account, id FROM zt_user WHERE account = "admin"', false, $langs);

r($data->groups)                                                             && p('0')                                  && e('account');
r($data->cols[0][0]->label . ',' . $data->cols[0][1]->label)                 && p()                                     && e('用户名,用户编号的计数(总计百分比)');
r($data->array)                                                              && p('0:account,id0_percentage;1:account,id0_percentage') && e('admin,50%;pivotuser9001,50%');
r($data->showAllTotal === false)                                              && p()                                     && e('1');
r($configs)                                                                  && p('0:0,1;1:0,1')                      && e('1,1;1,1');
r(count($emptyData->array))                                                  && p()                                     && e('0');
r(count($emptyConfigs))                                                      && p()                                     && e('0');
