#!/usr/bin/env php
<?php

/**

title=测试 formdom 解析 UTF-8 表单片段;
timeout=0
cid=1

- 解析包含中文和 HTML 的表单 >> 中文标题
- 解析包含中文和 HTML 的表单 >> 第一行第二行

*/

include dirname(__FILE__, 5) . '/lib/formdom/formdom.class.php';

function testFormdomParse(): array
{
    $parser = new formdom();
    $html   = <<<HTML
<form id="caseForm">
  <input type="text" name="title" value="中文标题" />
  <textarea name="desc">第一行<p>第二行</p></textarea>
</form>
HTML;

    return $parser->parse($html, 'caseForm');
}

$result = testFormdomParse();

if(!isset($result['title']) || $result['title'] !== '中文标题') exit("FAIL title\n");
if(!isset($result['desc']) || $result['desc'] !== '第一行第二行') exit("FAIL desc\n");

echo "PASS\n";
