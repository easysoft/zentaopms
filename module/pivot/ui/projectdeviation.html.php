<?php
declare(strict_types = 1);
/**
 * The project deviation view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$generateData = function() use ($module, $method, $lang, $executions)
{
    if(empty($module) || empty($method)) return div(setClass('bg-white center text-gray w-full h-40'), $lang->error->noData);

    $canViewProject = hasPriv('product', 'index');

    $cols = array(
        'executionID'   => array('title' => $lang->pivot->id,            'width' => '70',   'fixed' => 'left'),
        'projectName'   => array('title' => $lang->pivot->project,       'fixed' => 'left', 'link' => $canViewProject ? createLink('project', 'index', "id={projectID}") : ''),
        'executionName' => array('title' => $lang->pivot->execution,     'type'  => 'html', 'fixed' => 'left'),
        'estimate'      => array('title' => $lang->pivot->estimate,      'width' => '100',  'align' => 'center'),
        'consumed'      => array('title' => $lang->pivot->consumed,      'width' => '100',  'align' => 'center'),
        'deviation'     => array('title' => $lang->pivot->deviation,     'width' => '100',  'align' => 'center'),
        'deviationRate' => array('title' => $lang->pivot->deviationRate, 'width' => '100',  'align' => 'center')
    );

    return div
    (
        setClass('w-full'),
        div
        (
            setID('conditions'),
            setClass('bg-white mb-4 p-2'),
        ),
        dtable
        (
            set::cols($cols),
            set::data($executions),
            set::fixedLeftWidth('0.25'),
            set::emptyTip($lang->error->noData)
        ),
        echarts
        (
        )
    );
};
