#!/usr/bin/env php
<?php

/**

title=测试 holidayModel::isHoliday();
timeout=0
cid=16747

- 步骤1：正常节假日（元旦） @It is a holiday
- 步骤2：正常工作日（不在节假日范围） @It is not a holiday
- 步骤3：边界值测试（节假日开始日期） @It is a holiday
- 步骤4：边界值测试（节假日结束日期） @It is a holiday
- 步骤5：异常输入（空字符串） @It is not a holiday
- 步骤6：异常输入（无效日期格式） @It is not a holiday
- 步骤7：测试工作日类型记录（不认为是节假日） @It is not a holiday

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备 - 手动插入数据确保准确性
global $tester;
$tester->dbh->query('DELETE FROM zt_holiday');
$tester->dbh->query('INSERT INTO zt_holiday (id, name, type, year, begin, end, `desc`) VALUES
    (1, "元旦", "holiday", "2024", "2024-01-01", "2024-01-01", "元旦节假日"),
    (2, "春节", "holiday", "2024", "2024-02-10", "2024-02-17", "春节节假日"),
    (3, "清明节", "holiday", "2024", "2024-04-04", "2024-04-06", "清明节假日"),
    (4, "劳动节", "holiday", "2024", "2024-05-01", "2024-05-03", "劳动节节假日"),
    (5, "补班日", "working", "2024", "2024-02-18", "2024-02-18", "补班日")');

zenData('user')->gen(1);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$holidayTest = new holidayModelTest();

// 5. 测试步骤（至少5个）
r($holidayTest->isHolidayTest('2024-01-01')) && p() && e('It is a holiday');      // 步骤1：正常节假日（元旦）
r($holidayTest->isHolidayTest('2024-03-15')) && p() && e('It is not a holiday'); // 步骤2：正常工作日（不在节假日范围）
r($holidayTest->isHolidayTest('2024-02-10')) && p() && e('It is a holiday');      // 步骤3：边界值测试（节假日开始日期）
r($holidayTest->isHolidayTest('2024-02-17')) && p() && e('It is a holiday');      // 步骤4：边界值测试（节假日结束日期）
r($holidayTest->isHolidayTest('')) && p() && e('It is not a holiday');           // 步骤5：异常输入（空字符串）
r($holidayTest->isHolidayTest('invalid-date')) && p() && e('It is not a holiday'); // 步骤6：异常输入（无效日期格式）
r($holidayTest->isHolidayTest('2024-02-18')) && p() && e('It is not a holiday'); // 步骤7：测试工作日类型记录（不认为是节假日）