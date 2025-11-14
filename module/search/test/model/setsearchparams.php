#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

/**

title=测试 searchModel->setSearchParams();
timeout=0
cid=18312

- 测试module的值属性module @bug
- 测试actionURL的值属性actionURL @/index.php?m=bug&f=browse&productID=110&branch=0&browseType=bySearch&queryID=myQueryID
- 测试onMenuBar的值属性onMenuBar @yes
- 测试queryID的值属性queryID @0
- 测试style的值属性style @full
- 测试searchFields的值属性title @Bug Title
- 测试fieldParams的值
 - 第title条的operator属性 @include
 - 第title条的control属性 @input
 - 第title条的value属性 @~~

*/

$search = new searchTest();

$searchConfig = array();
$searchConfig['module'] = 'bug';
$searchConfig['fields'] = array();
$searchConfig['fields']['title'] = 'Bug Title';
$searchConfig['params'] = array();
$searchConfig['params']['title']['operator'] = 'include';
$searchConfig['params']['title']['control']  = 'input';
$searchConfig['params']['title']['value']    = '';
$searchConfig['onMenuBar'] = 'yes';
$searchConfig['actionURL'] = '/index.php?m=bug&f=browse&productID=110&branch=0&browseType=bySearch&queryID=myQueryID';
$searchConfig['queryID']   = 0;

$result = $search->setSearchParams($searchConfig);

r($result) && p('module')    && e('bug');                                                                                    //测试module的值
r($result) && p('actionURL') && e('/index.php?m=bug&f=browse&productID=110&branch=0&browseType=bySearch&queryID=myQueryID'); //测试actionURL的值
r($result) && p('onMenuBar') && e('yes');                                                                                    //测试onMenuBar的值
r($result) && p('queryID')   && e('0');                                                                                      //测试queryID的值
r($result) && p('style')     && e('full');                                                                                   //测试style的值

r($result['fields']) && p('title')                        && e('Bug Title');        //测试searchFields的值
r($result['params']) && p('title:operator,control,value') && e('include,input,~~'); //测试fieldParams的值