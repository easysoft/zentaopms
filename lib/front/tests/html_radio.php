#!/usr/bin/env php
<?php
/**
 * 测试html的radio方法。
 *
 * 1. 正常情况。
 * 2. 验证单个selectedItems是否正确。
 * 3. 验证多个selectedItems是否正确。(没有一个选中。)
 * 4. 验证attrib传递是否正确。
 * 5. 验证options为空，是否返回false。
 *
 * @author  chunsheng.wang <wwccss@gmail.com>
 * @version $Id: html_radio.php 1156 2009-04-24 08:53:44Z wwccss $
 */
include '../front.class.php';

$options['a']  = 'texta';
$options['b']  = 'textb';
$options['c']  = 'textc';

echo html::radio('radio', $options);
echo html::radio('radio', $options, 'a');
echo html::radio('radio', $options, 'a,b');
echo html::radio('radio', $options, '', 'style="color:red"');
var_dump(html::radio('radio', array()));
<<<expect
html_radio.expect
expect
?>
