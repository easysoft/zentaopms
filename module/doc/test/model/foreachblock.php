#!/usr/bin/env php
<?php

/**

title=测试 docModel::forEachDocBlock();
timeout=0
cid=16080

- 步骤1：正常情况-遍历所有区块并计数 @15
- 步骤2：正常情况-过滤特定flavour(affine:paragraph)获取区块数量 @12
- 步骤3：正常情况-过滤标题类型(h1)获取区块数量 @1
- 步骤4：正常情况-过滤多个标题类型(h2,h3)获取区块数量 @5
- 步骤5：正常情况-获取所有标题文本内容(按类型分组) @总标题,标题 1,标题 2,标题 1.1,标题 2.1,标题 2.2,标题 2.2.1
- 步骤6：边界值-空内容返回初始数据 @0
- 步骤7：边界值-无匹配flavour返回初始数据 @0
- 步骤8：正常情况-获取h4标题内容 @标题 2.2.1
- 步骤9：正常情况-过滤affine:note获取区块数量 @1
- 步骤10：正常情况-过滤affine:surface获取区块数量 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 加载模型
global $tester;
$docModel = $tester->loadModel('doc');

// 5. 准备测试数据
$testDocContent = json_decode('{"type":"page","meta":{"id":"gv59xPh7Ss","title":"Test doc 2","createDate":1735009861666,"tags":[]},"blocks":{"type":"block","id":"bFt3Zebq4C","flavour":"affine:page","version":2,"props":{"title":{"$blocksuite:internal:text$":true,"delta":[{"insert":"Test doc 2"}]}},"children":[{"type":"block","id":"3ZDtESrTwV","flavour":"affine:surface","version":5,"props":{"elements":{}},"children":[]},{"type":"block","id":"2uKZ34xemh","flavour":"affine:note","version":1,"props":{"xywh":"[0,0,498,92]","background":"--affine-note-background-white","index":"a0","lockedBySelf":false,"hidden":false,"displayMode":"both","edgeless":{"style":{"borderRadius":8,"borderSize":4,"borderStyle":"none","shadowType":"--affine-note-shadow-box"}}},"children":[{"type":"block","id":"YUKg2P9jRd","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"h1","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":"总标题"}]},"collapsed":false},"children":[]},{"type":"block","id":"57fqsxdUPx","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"h2","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":"标题 1"}]},"collapsed":false},"children":[]},{"type":"block","id":"8K8dPj1sCM","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"text","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":"test"}]},"collapsed":false},"children":[]},{"type":"block","id":"t2QW0-7wjo","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"h3","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":"标题 1.1"}]},"collapsed":false},"children":[]},{"type":"block","id":"cSxIMNwE5r","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"text","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":"test2"}]},"collapsed":false},"children":[]},{"type":"block","id":"OLPb8QmDkm","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"h2","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":"标题 2"}]},"collapsed":false},"children":[]},{"type":"block","id":"UtmatxoW-t","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"text","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":"test3"}]},"collapsed":false},"children":[]},{"type":"block","id":"6dnzn0y_X0","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"h3","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":"标题 2.1"}]},"collapsed":false},"children":[]},{"type":"block","id":"J7tikZIhh0","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"text","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":"test3"}]},"collapsed":false},"children":[]},{"type":"block","id":"FUUm-Oy6iC","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"h3","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":"标题 2.2"}]},"collapsed":false},"children":[]},{"type":"block","id":"AGXLCs2K9D","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"h4","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":"标题 2.2.1"}]},"collapsed":false},"children":[]},{"type":"block","id":"PWCMwmaZqD","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"text","text":{"$blocksuite:internal:text$":true,"delta":[]},"collapsed":false},"children":[]}]}]}}', true);

// 6. 定义测试辅助函数

/**
 * 计数回调函数。
 * Counting callback.
 */
$countCallback = function($block, $data, $depth, $level, $index)
{
    return $data + 1;
};

/**
 * 获取标题文本的回调函数。
 * Get heading text callback.
 */
$getHeadingTextCallback = function($block, $data, $depth, $level, $index)
{
    if(!isset($block['props']['text']['delta'][0]['insert'])) return $data;
    $text = $block['props']['text']['delta'][0]['insert'];
    if(!empty($text)) $data[] = $text;
    return $data;
};

// 7. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况-遍历所有区块并计数
$result1 = docModel::forEachDocBlock($testDocContent, $countCallback, 0);
r($result1) && p() && e('15');

// 步骤2：正常情况-过滤特定flavour(affine:paragraph)获取区块数量
$result2 = docModel::forEachDocBlock($testDocContent, $countCallback, 0, 'affine:paragraph');
r($result2) && p() && e('12');

// 步骤3：正常情况-过滤标题类型(h1)获取区块数量
$result3 = docModel::forEachDocBlock($testDocContent, $countCallback, 0, 'affine:paragraph', 'block', array('type' => 'h1'));
r($result3) && p() && e('1');

// 步骤4：正常情况-过滤多个标题类型(h2,h3)获取区块数量
$result4H2 = docModel::forEachDocBlock($testDocContent, $countCallback, 0, 'affine:paragraph', 'block', array('type' => 'h2'));
$result4H3 = docModel::forEachDocBlock($testDocContent, $countCallback, 0, 'affine:paragraph', 'block', array('type' => 'h3'));
$result4 = $result4H2 + $result4H3;
r($result4) && p() && e('5');

// 步骤5：正常情况-获取所有标题文本内容
$headingTypes = array('h1', 'h2', 'h3', 'h4');
$allHeadings = array();
foreach($headingTypes as $type)
{
    $headings = docModel::forEachDocBlock($testDocContent, $getHeadingTextCallback, array(), 'affine:paragraph', 'block', array('type' => $type));
    $allHeadings = array_merge($allHeadings, $headings);
}
r(implode(',', $allHeadings)) && p() && e('总标题,标题 1,标题 2,标题 1.1,标题 2.1,标题 2.2,标题 2.2.1');

// 步骤6：边界值-空内容返回初始数据
$emptyContent = array();
$result6 = docModel::forEachDocBlock($emptyContent, $countCallback, 0);
r($result6) && p() && e('0');

// 步骤7：边界值-无匹配flavour返回初始数据
$result7 = docModel::forEachDocBlock($testDocContent, $countCallback, 0, 'non:existent:flavour');
r($result7) && p() && e('0');

// 步骤8：正常情况-获取h4标题内容
$h4Headings = docModel::forEachDocBlock($testDocContent, $getHeadingTextCallback, array(), 'affine:paragraph', 'block', array('type' => 'h4'));
r(implode(',', $h4Headings)) && p() && e('标题 2.2.1');

// 步骤9：正常情况-过滤affine:note获取区块数量
$result9 = docModel::forEachDocBlock($testDocContent, $countCallback, 0, 'affine:note');
r($result9) && p() && e('1');

// 步骤10：正常情况-过滤affine:surface获取区块数量
$result10 = docModel::forEachDocBlock($testDocContent, $countCallback, 0, 'affine:surface');
r($result10) && p() && e('1');
