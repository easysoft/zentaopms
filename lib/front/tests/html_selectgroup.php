#!/usr/bin/env php
<?php
/**
 * 测试html的selectgroup方法。
 *
 * 1. 正常情况。
 * 2. name中含有中括弧，验证生成的id是否正确。
 * 3. name中含有中括弧，且有数字，验证生成的id是否正确。
 * 4. 验证单个selectedItems是否正确。
 * 5. 验证多个selectedItems是否正确。
 * 6. 验证selectedItems包含options里面的某一个key，验证selected是否正确。
 * 7. 验证attri的传毒是否正确。
 * 8. 验证options为空，是否返回false。
 *
 * @author  chunsheng.wang <wwccss@gmail.com>
 * @version $Id$
 */
include '../front.class.php';

$groups['group1']['a'] = 'texta';
$groups['group1']['b'] = 'textb';
$groups['group2']['c'] = 'textc';
$groups['group2']['d'] = 'textd';

echo html::selectgroup('select', $groups);
?>
