#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';

su('admin');

/**

title=测试 searchModel->buildQuery();
timeout=0
cid=1

- 测试field的值第0条的field属性 @title
- 测试operator的值第0条的operator属性 @include
- 测试value的值第0条的value属性 @test
- 测试生成的查询sql的值 @(( 1  AND `title`  LIKE '%test%' ) AND ( 1  ))

*/

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

$postData1 = new stdclass();
$postData1->field1    = 'title';
$postData1->andOr1    = 'and';
$postData1->operator1 = 'include';
$postData1->value1    = 'test';

$postDatas = array();
$postDatas[] = $postData1;

$returnList = array('form', 'query');

$search = new searchTest();
r($search->buildQueryTest($searchConfig, $postDatas, $returnList[0])) && p('0:field')    && e('title');   //测试field的值
r($search->buildQueryTest($searchConfig, $postDatas, $returnList[0])) && p('0:operator') && e('include'); //测试operator的值
r($search->buildQueryTest($searchConfig, $postDatas, $returnList[0])) && p('0:value')    && e('test');    //测试value的值
r($search->buildQueryTest($searchConfig, $postDatas, $returnList[1])) && p() && e("(( 1  AND `title`  LIKE '%test%' ) AND ( 1  ))"); //测试生成的查询sql的值