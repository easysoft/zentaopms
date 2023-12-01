<?php
declare(strict_types=1);
/**
 * The browse view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;

$metricTrees = array();
foreach($groupMetrics as $key => $metrics)
{
    if(empty($metrics)) continue;
    $metricCheckItems = array();
    foreach($metrics as $metric)
    {
        $class  = in_array($metric->id, $checkedList) ? 'metric-current' : '';
        $class .= ' font-medium checkbox';
        $metricCheckItems[] = item
            (
                set::text($metric->name),
                set::value($metric->id),
                set::scope($metric->scope),
                set::typeClass($class),
                set::checked(in_array($metric->id, $checkedList)),
                bind::change('window.handleCheckboxChange($element)')
            );
    }
    if($scope != 'collect')
    {
        $metricCount  = count($metrics);
        $metricTrees[] = div(setClass('check-list-title') ,$this->lang->metric->objectList[$key] . "($metricCount)");
    }
    $metricTrees[] = checkList
        (
            set::className('check-list-metric'),
            set::primary(true),
            set::name('metric'),
            set::inline(false),
            $metricCheckItems
        );
}
