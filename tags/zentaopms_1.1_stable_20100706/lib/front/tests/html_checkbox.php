#!/usr/bin/env php
<?php
/**
 * 测试html的checkbox方法。
 *
 * 1. 正常情况。
 * 2. 验证单个selectedItems是否正确。
 * 3. 验证多个selectedItems是否正确。
 * 4. 验证selectedItems包含options里面的某一个key，验证selected是否正确。
 * 5. 验证attrib传递是否正确。
 * 6. 验证options为空，是否返回false。
 *
 * @author  chunsheng.wang <chunsheng@cnezsoft.com>
 * @version $Id$
 */
include '../front.class.php';

$options['a']  = 'texta';
$options['b']  = 'textb';
$options['c']  = 'textc';

echo html::checkbox('checkbox', $options) . "\n";
echo html::checkbox('checkbox', $options, 'a') . "\n";
echo html::checkbox('checkbox', $options, 'a,b') . "\n";
echo html::checkbox('checkbox', $options, 'ab') . "\n";
echo html::checkbox('checkbox', $options, '', 'style="color:red"') . "\n";
var_dump(html::checkbox('checkbox', array()));
<<<expect
html_checkbox.expect
expect
?>
