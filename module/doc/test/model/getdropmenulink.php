#!/usr/bin/env php
<?php
/**

title=测试 docModel->getDropMenuLink();
cid=1

- 获取type=all, objectID=0, module=doc, method=index的链接 @0
- 获取type=project, objectID=0, module=doc, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=0&module=doc&method=index
- 获取type=product, objectID=0, module=doc, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=0&module=doc&method=index
- 获取type=all, objectID=1, module=doc, method=index的链接 @0
- 获取type=project, objectID=1, module=doc, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=1&module=doc&method=index
- 获取type=product, objectID=1, module=doc, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=1&module=doc&method=index
- 获取type=all, objectID=11, module=doc, method=index的链接 @0
- 获取type=project, objectID=11, module=doc, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=11&module=doc&method=index
- 获取type=product, objectID=11, module=doc, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=11&module=doc&method=index
- 获取type=all, objectID=0, module=api, method=index的链接 @0
- 获取type=project, objectID=0, module=api, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=0&module=doc&method=projectSpace
- 获取type=product, objectID=0, module=api, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=0&module=doc&method=productSpace
- 获取type=all, objectID=1, module=api, method=index的链接 @0
- 获取type=project, objectID=1, module=api, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=1&module=doc&method=projectSpace
- 获取type=product, objectID=1, module=api, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=1&module=doc&method=productSpace
- 获取type=all, objectID=11, module=api, method=index的链接 @0
- 获取type=project, objectID=11, module=api, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=11&module=doc&method=projectSpace
- 获取type=product, objectID=11, module=api, method=index的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=11&module=doc&method=productSpace
- 获取type=all, objectID=0, module=doc, method=browse的链接 @0
- 获取type=project, objectID=0, module=doc, method=browse的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=0&module=doc&method=browse
- 获取type=product, objectID=0, module=doc, method=browse的链接 @getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=0&module=doc&method=browse

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('user')->gen(5);

$types     = array('all', 'project', 'product');
$objectIds = array(0, 1, 11);
$modules   = array('doc', 'api');
$methods   = array('index', 'browse');

$docTester = new docTest();
r($docTester->getDropMenuLinkTest($types[0], $objectIds[0], $modules[0], $methods[0])) && p() && e('0');                                                                                                         // 获取type=all, objectID=0, module=doc, method=index的链接
r($docTester->getDropMenuLinkTest($types[1], $objectIds[0], $modules[0], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=0&module=doc&method=index');         // 获取type=project, objectID=0, module=doc, method=index的链接
r($docTester->getDropMenuLinkTest($types[2], $objectIds[0], $modules[0], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=0&module=doc&method=index');         // 获取type=product, objectID=0, module=doc, method=index的链接
r($docTester->getDropMenuLinkTest($types[0], $objectIds[1], $modules[0], $methods[0])) && p() && e('0');                                                                                                         // 获取type=all, objectID=1, module=doc, method=index的链接
r($docTester->getDropMenuLinkTest($types[1], $objectIds[1], $modules[0], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=1&module=doc&method=index');         // 获取type=project, objectID=1, module=doc, method=index的链接
r($docTester->getDropMenuLinkTest($types[2], $objectIds[1], $modules[0], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=1&module=doc&method=index');         // 获取type=product, objectID=1, module=doc, method=index的链接
r($docTester->getDropMenuLinkTest($types[0], $objectIds[2], $modules[0], $methods[0])) && p() && e('0');                                                                                                         // 获取type=all, objectID=11, module=doc, method=index的链接
r($docTester->getDropMenuLinkTest($types[1], $objectIds[2], $modules[0], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=11&module=doc&method=index');        // 获取type=project, objectID=11, module=doc, method=index的链接
r($docTester->getDropMenuLinkTest($types[2], $objectIds[2], $modules[0], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=11&module=doc&method=index');        // 获取type=product, objectID=11, module=doc, method=index的链接
r($docTester->getDropMenuLinkTest($types[0], $objectIds[0], $modules[1], $methods[0])) && p() && e('0');                                                                                                         // 获取type=all, objectID=0, module=api, method=index的链接
r($docTester->getDropMenuLinkTest($types[1], $objectIds[0], $modules[1], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=0&module=doc&method=projectSpace');  // 获取type=project, objectID=0, module=api, method=index的链接
r($docTester->getDropMenuLinkTest($types[2], $objectIds[0], $modules[1], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=0&module=doc&method=productSpace');  // 获取type=product, objectID=0, module=api, method=index的链接
r($docTester->getDropMenuLinkTest($types[0], $objectIds[1], $modules[1], $methods[0])) && p() && e('0');                                                                                                         // 获取type=all, objectID=1, module=api, method=index的链接
r($docTester->getDropMenuLinkTest($types[1], $objectIds[1], $modules[1], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=1&module=doc&method=projectSpace');  // 获取type=project, objectID=1, module=api, method=index的链接
r($docTester->getDropMenuLinkTest($types[2], $objectIds[1], $modules[1], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=1&module=doc&method=productSpace');  // 获取type=product, objectID=1, module=api, method=index的链接
r($docTester->getDropMenuLinkTest($types[0], $objectIds[2], $modules[1], $methods[0])) && p() && e('0');                                                                                                         // 获取type=all, objectID=11, module=api, method=index的链接
r($docTester->getDropMenuLinkTest($types[1], $objectIds[2], $modules[1], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=11&module=doc&method=projectSpace'); // 获取type=project, objectID=11, module=api, method=index的链接
r($docTester->getDropMenuLinkTest($types[2], $objectIds[2], $modules[1], $methods[0])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=11&module=doc&method=productSpace'); // 获取type=product, objectID=11, module=api, method=index的链接
r($docTester->getDropMenuLinkTest($types[0], $objectIds[0], $modules[0], $methods[1])) && p() && e('0');                                                                                                         // 获取type=all, objectID=0, module=doc, method=browse的链接
r($docTester->getDropMenuLinkTest($types[1], $objectIds[0], $modules[0], $methods[1])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=project&objectID=0&module=doc&method=browse');        // 获取type=project, objectID=0, module=doc, method=browse的链接
r($docTester->getDropMenuLinkTest($types[2], $objectIds[0], $modules[0], $methods[1])) && p() && e('getdropmenulink.php?m=doc&f=ajaxGetDropMenu&objectType=product&objectID=0&module=doc&method=browse');        // 获取type=product, objectID=0, module=doc, method=browse的链接
