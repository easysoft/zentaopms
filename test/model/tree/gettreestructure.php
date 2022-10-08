#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getTreeStructure();
cid=1
pid=1

测试获取root 1 story 的树结构 >> 1821:2;2221:2;1822:0;2222:0;1823:0;2223:0;1824:0;2224:0;
测试获取root 2 story 的树结构 >> 1825:2;2225:2;1826:0;2226:0;1827:0;2227:0;1828:0;2228:0;
测试获取root 3 story 的树结构 >> 1831:0;2231:0;1832:0;2232:0;1829:2;2229:2;1830:0;2230:0;
测试获取root 41 story 的树结构 >> 1981:2;2381:2;1982:0;2382:0;1983:0;2383:0;1984:0;2384:0;
测试获取root 42 story 的树结构 >> 1985:2;2385:2;1986:0;2386:0;1987:0;2387:0;1988:0;2388:0;
测试获取root 43 story 的树结构 >> 1991:0;2391:0;1992:0;2392:0;1989:2;2389:2;1990:0;2390:0;
测试获取root 1 case 的树结构 >> 1821:2;2221:2;3921:2;1822:0;2222:0;1823:0;2223:0;1824:0;2224:0;
测试获取root 2 case 的树结构 >> 3922:2;1825:2;2225:2;1826:0;2226:0;1827:0;2227:0;1828:0;2228:0;
测试获取root 3 case 的树结构 >> 1831:0;2231:0;1832:0;2232:0;3923:2;1829:2;2229:2;1830:0;2230:0;
测试获取root 1 doc 的树结构 >> 3621:2;
测试获取root 2 doc 的树结构 >> 3622:2;
测试获取root 3 doc 的树结构 >> 3623:2;
测试获取root 101 task 的树结构 >> 0
测试获取root 102 task 的树结构 >> 0
测试获取root 103 task 的树结构 >> 0

*/
$root = array(1, 2, 3, 41, 42, 43, 101, 102, 103);
$type = array('story', 'case', 'doc', 'task');

$tree = new treeTest();

r($tree->getTreeStructureTest($root[0], $type[0])) && p() && e('1821:2;2221:2;1822:0;2222:0;1823:0;2223:0;1824:0;2224:0;');        // 测试获取root 1 story 的树结构
r($tree->getTreeStructureTest($root[1], $type[0])) && p() && e('1825:2;2225:2;1826:0;2226:0;1827:0;2227:0;1828:0;2228:0;');        // 测试获取root 2 story 的树结构
r($tree->getTreeStructureTest($root[2], $type[0])) && p() && e('1831:0;2231:0;1832:0;2232:0;1829:2;2229:2;1830:0;2230:0;');        // 测试获取root 3 story 的树结构
r($tree->getTreeStructureTest($root[3], $type[0])) && p() && e('1981:2;2381:2;1982:0;2382:0;1983:0;2383:0;1984:0;2384:0;');        // 测试获取root 41 story 的树结构
r($tree->getTreeStructureTest($root[4], $type[0])) && p() && e('1985:2;2385:2;1986:0;2386:0;1987:0;2387:0;1988:0;2388:0;');        // 测试获取root 42 story 的树结构
r($tree->getTreeStructureTest($root[5], $type[0])) && p() && e('1991:0;2391:0;1992:0;2392:0;1989:2;2389:2;1990:0;2390:0;');        // 测试获取root 43 story 的树结构
r($tree->getTreeStructureTest($root[0], $type[1])) && p() && e('1821:2;2221:2;3921:2;1822:0;2222:0;1823:0;2223:0;1824:0;2224:0;'); // 测试获取root 1 case 的树结构
r($tree->getTreeStructureTest($root[1], $type[1])) && p() && e('3922:2;1825:2;2225:2;1826:0;2226:0;1827:0;2227:0;1828:0;2228:0;'); // 测试获取root 2 case 的树结构
r($tree->getTreeStructureTest($root[2], $type[1])) && p() && e('1831:0;2231:0;1832:0;2232:0;3923:2;1829:2;2229:2;1830:0;2230:0;'); // 测试获取root 3 case 的树结构
r($tree->getTreeStructureTest($root[0], $type[2])) && p() && e('3621:2;');                                                         // 测试获取root 1 doc 的树结构
r($tree->getTreeStructureTest($root[1], $type[2])) && p() && e('3622:2;');                                                         // 测试获取root 2 doc 的树结构
r($tree->getTreeStructureTest($root[2], $type[2])) && p() && e('3623:2;');                                                         // 测试获取root 3 doc 的树结构
r($tree->getTreeStructureTest($root[6], $type[3])) && p() && e('0');                                                               // 测试获取root 101 task 的树结构
r($tree->getTreeStructureTest($root[7], $type[3])) && p() && e('0');                                                               // 测试获取root 102 task 的树结构
r($tree->getTreeStructureTest($root[8], $type[3])) && p() && e('0');                                                               // 测试获取root 103 task 的树结构