#!/usr/bin/env php
<?php

/**

title=测试 searchTao::markKeywords();
timeout=0
cid=18331

- 执行searchTest模块的markKeywordsDirectTest方法，参数是'This is a test content', 'test'  @This is a <span class='text-danger'>test</span>  content
- 执行searchTest模块的markKeywordsDirectTest方法，参数是'This contains key1 and key2 words', 'key1 key2'  @This contains <span class='text-danger'>key1</span>  and <span class='text-danger'>key2</span>  words
- 执行searchTest模块的markKeywordsDirectTest方法，参数是'这是一个测试内容', '测试'  @这是一个<span class='text-danger'>测试</span> 内容
- 执行searchTest模块的markKeywordsDirectTest方法，参数是'Product version 12345 released', '12345'  @Product version <span class='text-danger'>12345</span> released
- 执行searchTest模块的markKeywordsDirectTest方法，参数是'No keywords here', ''  @No keywords here

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$searchTest = new searchTest();

r($searchTest->markKeywordsDirectTest('This is a test content', 'test')) && p() && e("This is a <span class='text-danger'>test</span>  content ");
r($searchTest->markKeywordsDirectTest('This contains key1 and key2 words', 'key1 key2')) && p() && e("This contains <span class='text-danger'>key1</span>  and <span class='text-danger'>key2</span>  words ");
r($searchTest->markKeywordsDirectTest('这是一个测试内容', '测试')) && p() && e("这是一个<span class='text-danger'>测试</span> 内容 ");
r($searchTest->markKeywordsDirectTest('Product version 12345 released', '12345')) && p() && e("Product version <span class='text-danger'>12345</span> released ");
r($searchTest->markKeywordsDirectTest('No keywords here', '')) && p() && e('No keywords here ');