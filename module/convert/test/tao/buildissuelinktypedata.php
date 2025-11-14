#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildIssueLinkTypeData();
timeout=0
cid=15816

- 执行convertTest模块的buildIssueLinkTypeDataTest方法，参数是$fullData
 - 属性id @12345
 - 属性linkname @Relates To
 - 属性inward @relates to
 - 属性outward @relates to
 - 属性pstyle @issue-link
- 执行convertTest模块的buildIssueLinkTypeDataTest方法，参数是$minimalData
 - 属性id @67890
 - 属性linkname @Blocks
 - 属性inward @~~
 - 属性outward @~~
 - 属性pstyle @~~
- 执行convertTest模块的buildIssueLinkTypeDataTest方法，参数是$emptyIdData
 - 属性id @~~
 - 属性linkname @~~
 - 属性inward @~~
 - 属性outward @~~
 - 属性pstyle @~~
- 执行convertTest模块的buildIssueLinkTypeDataTest方法，参数是$specialData
 - 属性id @special-123
 - 属性linkname @Link with "quotes" & <html> tags
- 执行convertTest模块的buildIssueLinkTypeDataTest方法，参数是$extraData
 - 属性id @99999
 - 属性linkname @Duplicates
 - 属性inward @is duplicated by
 - 属性outward @duplicates
 - 属性pstyle @duplicate-style

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 测试步骤：必须包含至少5个测试步骤

// 步骤1：完整数据构建测试
$fullData = array(
    'id' => '12345',
    'linkname' => 'Relates To',
    'inward' => 'relates to',
    'outward' => 'relates to',
    'style' => 'issue-link'
);
r($convertTest->buildIssueLinkTypeDataTest($fullData)) && p('id,linkname,inward,outward,pstyle') && e('12345,Relates To,relates to,relates to,issue-link');

// 步骤2：必需字段缺失测试（缺少可选字段）
$minimalData = array(
    'id' => '67890',
    'linkname' => 'Blocks'
);
r($convertTest->buildIssueLinkTypeDataTest($minimalData)) && p('id,linkname,inward,outward,pstyle') && e('67890,Blocks,~~,~~,~~');

// 步骤3：空字符串输入测试
$emptyIdData = array(
    'id' => '',
    'linkname' => ''
);
r($convertTest->buildIssueLinkTypeDataTest($emptyIdData)) && p('id,linkname,inward,outward,pstyle') && e('~~,~~,~~,~~,~~');

// 步骤4：特殊字符处理测试
$specialData = array(
    'id' => 'special-123',
    'linkname' => 'Link with "quotes" & <html> tags',
    'inward' => 'inward with spaces',
    'outward' => 'outward with\ttabs',
    'style' => 'style-with-dashes'
);
r($convertTest->buildIssueLinkTypeDataTest($specialData)) && p('id,linkname') && e('special-123,Link with "quotes" & <html> tags');

// 步骤5：大量数据字段测试（包含额外字段）
$extraData = array(
    'id' => '99999',
    'linkname' => 'Duplicates',
    'inward' => 'is duplicated by',
    'outward' => 'duplicates',
    'style' => 'duplicate-style',
    'description' => 'This field should be ignored',
    'created' => '2024-01-01 00:00:00',
    'modified' => '2024-01-02 00:00:00',
    'creator' => 'testuser',
    'extra_field' => 'extra_value'
);
r($convertTest->buildIssueLinkTypeDataTest($extraData)) && p('id,linkname,inward,outward,pstyle') && e('99999,Duplicates,is duplicated by,duplicates,duplicate-style');