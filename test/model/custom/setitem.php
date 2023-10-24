#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->setItem();
cid=1
pid=1

测试path中.出现的个数为0，value正常存在，不能保存 >> 0
测试path中.出现的个数为1，value正常存在，不能保存 >> 0
测试path中.出现的个数为2，value正常存在，能保存 >> zh-cn,story,categoryList,功能
测试path中.出现的个数为2，value为空，能保存 >> zh-cn,story,categoryList,
测试path中.出现的个数为3，value正常存在，能保存 >> zh-cn,story,feature,功能
测试path中.出现的个数为3，value为空，能保存 >> zh-cn,story,feature,
测试path中.出现的个数为4，value正常存在，能保存 >> zh-cn,story,feature,功能
测试path中.出现的个数为5，value为空，能保存 >> zh-cn,story,feature,

*/
$path  = array('zh-cn', 'zh-cn.story', 'zh-cn.story.categoryList', 'zh-cn.story.categoryList.feature', 'zh-cn.story.categoryList.feature.1');
$value = array('功能', '');

$custom = new customTest();

r($custom->setItemTest($path[0], $value[0])) && p()                        && e('0');                              //测试path中.出现的个数为0，value正常存在，不能保存
r($custom->setItemTest($path[1], $value[0])) && p()                        && e('0');                              //测试path中.出现的个数为1，value正常存在，不能保存
r($custom->setItemTest($path[2], $value[0])) && p('lang,module,key,value') && e('zh-cn,story,categoryList,功能');  //测试path中.出现的个数为2，value正常存在，能保存
r($custom->setItemTest($path[2], $value[1])) && p('lang,module,key,value') && e('zh-cn,story,categoryList,');      //测试path中.出现的个数为2，value为空，能保存
r($custom->setItemTest($path[3], $value[0])) && p('lang,module,key,value') && e('zh-cn,story,feature,功能');       //测试path中.出现的个数为3，value正常存在，能保存
r($custom->setItemTest($path[3], $value[1])) && p('lang,module,key,value') && e('zh-cn,story,feature,');           //测试path中.出现的个数为3，value为空，能保存
r($custom->setItemTest($path[4], $value[0])) && p('lang,module,key,value') && e('zh-cn,story,feature,功能');       //测试path中.出现的个数为4，value正常存在，能保存
r($custom->setItemTest($path[4], $value[1])) && p('lang,module,key,value') && e('zh-cn,story,feature,');           //测试path中.出现的个数为5，value为空，能保存
