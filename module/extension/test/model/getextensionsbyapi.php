#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getExtensionsByAPI();
timeout=0
cid=1

- 判断返回的数据是否包含title,layouts,extensions,dbPager,pager字段。
 -  @title
 - 属性1 @layouts
 - 属性2 @extensions
 - 属性3 @dbPager
 - 属性4 @pager
- 检查是否返回了插件数据。 @1
- 检查返回的插件数据是否包含id,code,keyword,addedTime,status,images,objectID字段。
 -  @id
 - 属性2 @code
 - 属性5 @keyword
 - 属性10 @addedTime
 - 属性15 @status
 - 属性20 @images
 - 属性25 @objectID
- 检查是否按照要求分页。属性recPerPage @15

*/

global $tester;
$tester->loadModel('extension');
$apiList = $tester->extension->getExtensionsByAPI('byupdatedtime', '', 0, 15, 1);
$extensions = array_values((array)$apiList->extensions);

r(array_keys((array)$apiList)) && p('0,1,2,3,4') && e('title,layouts,extensions,dbPager,pager');                         // 判断返回的数据是否包含title,layouts,extensions,dbPager,pager字段。
r(count((array)$apiList->extensions) > 0) && p() && e('1');                                                              // 检查是否返回了插件数据。
r(array_keys((array)$extensions[0])) && p('0,2,5,10,15,20,25') && e('id,code,keyword,addedTime,status,images,objectID'); // 检查返回的插件数据是否包含id,code,keyword,addedTime,status,images,objectID字段。
r($apiList->dbPager) && p('recPerPage') && e('15');                                                                      // 检查是否按照要求分页。
