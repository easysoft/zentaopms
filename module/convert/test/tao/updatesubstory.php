#!/usr/bin/env php
<?php

/**

title=测试 convertTao::updateSubStory();
timeout=0
cid=15875

- 执行convertTest模块的updateSubStoryTest方法，参数是$storyLink1, $issueList1  @1
- 执行convertTest模块的updateSubStoryTest方法，参数是$storyLink2, $issueList2  @1
- 执行convertTest模块的updateSubStoryTest方法，参数是$storyLink3, $issueList3  @1
- 执行convertTest模块的updateSubStoryTest方法，参数是$storyLink4, $issueList4  @1
- 执行convertTest模块的updateSubStoryTest方法，参数是$storyLink5, $issueList5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('story')->gen(0);

su('admin');

$convertTest = new convertTaoTest();

$storyLink1 = array('1' => array('2', '3'));
$issueList1 = array(
    '1' => array('BType' => 'zstory', 'BID' => '1'),
    '2' => array('BType' => 'zstory', 'BID' => '2'),
    '3' => array('BType' => 'zstory', 'BID' => '3')
);
r($convertTest->updateSubStoryTest($storyLink1, $issueList1)) && p() && e('1');

$storyLink2 = array('4' => array('5', '6'));
$issueList2 = array(
    '5' => array('BType' => 'zstory', 'BID' => '5'),
    '6' => array('BType' => 'zstory', 'BID' => '6')
);
r($convertTest->updateSubStoryTest($storyLink2, $issueList2)) && p() && e('1');

$storyLink3 = array('7' => array('8', '9'));
$issueList3 = array(
    '7' => array('BType' => 'invalid_type', 'BID' => '7'),
    '8' => array('BType' => 'zstory', 'BID' => '8'),
    '9' => array('BType' => 'zstory', 'BID' => '9')
);
r($convertTest->updateSubStoryTest($storyLink3, $issueList3)) && p() && e('1');

$storyLink4 = array('10' => array('11', '12'));
$issueList4 = array(
    '10' => array('BType' => 'zstory', 'BID' => '10')
);
r($convertTest->updateSubStoryTest($storyLink4, $issueList4)) && p() && e('1');

$storyLink5 = array('13' => array('14', '15'));
$issueList5 = array(
    '13' => array('BType' => 'zstory', 'BID' => '13'),
    '14' => array('BType' => 'zstory', 'BID' => '14'),
    '15' => array('BType' => 'zepic', 'BID' => '15')
);
r($convertTest->updateSubStoryTest($storyLink5, $issueList5)) && p() && e('1');