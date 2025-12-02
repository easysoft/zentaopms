#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

/**

title=测试 screenModel->getByID();
timeout=0
cid=0

- 当from为query时，type为date时，default应该为当前周一的日期，检查是否生成了正确的时间。 @1
- 当from不存在，type为date并且default不存在的时候，生成default应该为null，检查是否生成了正确的default值。第0条的default属性 @~~
- 当from不存在，type为date并且default存在的时候，生成default应该为default的值，检查是否生成了正确的default值。
 -  @1672502400000
 - 属性1 @1672588800000
- 当from不存在，type为date并且default存在的时候，生成default应该为default的值，检查是否生成了正确的default值。第0条的default属性 @1672502400000

*/

zenData('screen')->gen(0);

$screen = new screenTest();

$chart = new stdclass();
$filters = array(
    'from'    =>  'query',
    'type'    =>  'date',
    'default' =>  '$MONDAY'
);
$chart->filters = json_encode(array($filters));
$chart->fields  = json_encode(array());

$chart1 = new stdclass();
$filters = array(
    'from'       =>  'query',
    'type'       =>  'select',
    'typeOption' =>  'user'
);
$chart1->filters = json_encode(array($filters));
$chart1->fields  = json_encode(array());

$chart2 = new stdclass();
$filters = array('type' => 'date');
$chart2->filters = json_encode(array($filters));
$chart2->fields  = json_encode(array());

$chart3 = new stdclass();
$filters = array(
    'type'    => 'date',
    'default' => array(
        'begin' => '2023-01-01',
        'end' => '2023-01-02'
    )
);
$chart3->filters = json_encode(array($filters));
$chart3->fields  = json_encode(array());

$chart4 = new stdclass();
$filters = array(
    'type'  =>  'select',
    'field' =>   'user'
);
$chart4->filters = json_encode(array($filters));
$fields = array(
    'user' => array(
        'type' => 'user',
        'object' => '',
        'field' => 'account'
    )
);
$chart4->fields = json_encode($fields);
$chart4->sql    = '';

$chart5 = new stdclass();
$filters = array(
    'type'    => 'date',
    'default' => array('begin' => '2023-01-01', 'end' => '')
);
$chart5->filters = json_encode(array($filters));
$chart5->fields  = json_encode(array());

$time = date('Y-m-d', time() - (date('N') - 1) * 24 * 3600);
$result = $screen->getChartFiltersTest($chart);
r(date('Y-m-d', $result[0]['default'] / 1000) == $time) && p('') && e('1');  //当from为query时，type为date时，default应该为当前周一的日期，检查是否生成了正确的时间。

r($screen->getChartFiltersTest($chart2)) && p('0:default') && e('~~');  //当from不存在，type为date并且default不存在的时候，生成default应该为null，检查是否生成了正确的default值。

$result = $screen->getChartFiltersTest($chart3);
r($result[0]['default']) && p('0,1') && e('1672502400000,1672588800000');  //当from不存在，type为date并且default存在的时候，生成default应该为default的值，检查是否生成了正确的default值。

$result = $screen->getChartFiltersTest($chart5);
r($result) && p('0:default') && e('1672502400000');  //当from不存在，type为date并且default存在的时候，生成default应该为default的值，检查是否生成了正确的default值。
