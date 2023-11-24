#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';

su('admin');

/**

title=测试 searchModel->setSearchParams();
timeout=0
cid=1

- 测试module的值属性module @bug
- 测试searchFields的值属性searchFields @{"title":"Bug Title"}
- 测试fieldParams的值
 - 属性fieldParams @{"title":{"operator":"include"\\
- 测试actionURL的值属性actionURL @/index.php?m=bug&f=browse&productID=110&branch=0&browseType=bySearch&queryID=myQueryID
- 测试onMenuBar的值属性onMenuBar @yes
- 测试queryID的值属性queryID @0
- 测试style的值属性style @full

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

r($search->setSearchParams($searchConfig)) && p('module')            && e('bug');                                                                                    //测试module的值
r($search->setSearchParams($searchConfig)) && p('searchFields')      && e('{"title":"Bug Title"}');                                                                  //测试searchFields的值
r($search->setSearchParams($searchConfig)) && p('fieldParams', ';')  && e('{"title":{"operator":"include","control":"input","value":""}}');                          //测试fieldParams的值
r($search->setSearchParams($searchConfig)) && p('actionURL')         && e('/index.php?m=bug&f=browse&productID=110&branch=0&browseType=bySearch&queryID=myQueryID'); //测试actionURL的值
r($search->setSearchParams($searchConfig)) && p('onMenuBar')         && e('yes');                                                                                    //测试onMenuBar的值
r($search->setSearchParams($searchConfig)) && p('queryID')           && e('0');                                                                                      //测试queryID的值
r($search->setSearchParams($searchConfig)) && p('style')             && e('full');                                                                                    //测试style的值
