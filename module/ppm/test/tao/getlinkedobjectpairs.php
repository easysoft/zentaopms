#!/usr/bin/env php
<?php

/**

title=测试 ppmTao::getLinkedObjectPairs();
timeout=0
cid=0

- 执行ppmTao模块的getLinkedObjectPairsTest方法，参数是7101, 'story', 'ppm'  @1|4
- 执行ppmTao模块的getLinkedObjectPairsTest方法，参数是7101, 'bug', 'ppm'  @3
- 执行ppmTao模块的getLinkedObjectPairsTest方法，参数是7101, 'task', 'pullreq'  @2|5
- 执行ppmTao模块的getLinkedObjectPairsTest方法，参数是7999, 'story', 'ppm'  @0
- 执行ppmTao模块的getLinkedObjectPairsTest方法，参数是7101, 'case', 'ppm'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$relation = zenData('relation');
$relation->id->range('1-5');
$relation->product->range('1{5}');
$relation->AType->range('ppm,pullreq,ppm,ppm,pullreq');
$relation->AID->range('7101{5}');
$relation->relation->range('interrated{5}');
$relation->BType->range('story,task,bug,story,task');
$relation->BID->range('1,2,3,4,5');
$relation->gen(5);

su('admin');

$ppmTao = new ppmTaoTest();

r(implode('|', $ppmTao->getLinkedObjectPairsTest(7101, 'story', 'ppm'))) && p() && e('1|4');
r(implode('|', $ppmTao->getLinkedObjectPairsTest(7101, 'bug', 'ppm'))) && p() && e('3');
r(implode('|', $ppmTao->getLinkedObjectPairsTest(7101, 'task', 'pullreq'))) && p() && e('2|5');
r($ppmTao->getLinkedObjectPairsTest(7999, 'story', 'ppm')) && p() && e('0');
r($ppmTao->getLinkedObjectPairsTest(7101, 'case', 'ppm')) && p() && e('0');