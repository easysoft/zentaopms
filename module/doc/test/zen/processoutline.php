#!/usr/bin/env php
<?php

/**

title=测试 docZen::processOutline();
timeout=0
cid=16211

- 执行docTest模块的processOutlineTest方法，参数是createDoc
 - 属性anchorCount @2
 - 属性anchor0 @0
 - 属性anchor1 @1
- 执行docTest模块的processOutlineTest方法，参数是createDoc
 - 属性anchorCount @1
 - 属性anchor0 @1
- 执行docTest模块的processOutlineTest方法，参数是createDoc
 - 属性anchorCount @0
 - 属性hasH1 @0
- 执行docTest模块的processOutlineTest方法，参数是createDoc
 - 属性anchorCount @1
 - 属性anchor0 @0
- 执行docTest模块的processOutlineTest方法，参数是createDoc
 - 属性anchorCount @3
 - 属性anchor2 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

function createDoc($content) {
    $doc = new stdClass();
    $doc->content = $content;
    return $doc;
}

r($docTest->processOutlineTest(createDoc("<h1>Title 1</h1><p>Content 1</p><h2>Title 2</h2><p>Content 2</p>"))) && p('anchorCount;anchor0;anchor1') && e('2;0;1');
r($docTest->processOutlineTest(createDoc("<h1></h1><p>Content</p><h2>Valid Title</h2>"))) && p('anchorCount;anchor0') && e('1;1');
r($docTest->processOutlineTest(createDoc("<p>Only paragraph content</p><div>No headings here</div>"))) && p('anchorCount;hasH1') && e('0;0');
r($docTest->processOutlineTest(createDoc("<h1>Single Title</h1><p>Some content</p>"))) && p('anchorCount;anchor0') && e('1;0');
r($docTest->processOutlineTest(createDoc("<h1>Level 1</h1><p>Content</p><h2>Level 2</h2><p>Content</p><h3>Level 3</h3>"))) && p('anchorCount;anchor2') && e('3;2');