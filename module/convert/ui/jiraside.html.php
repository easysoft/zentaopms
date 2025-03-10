<?php
declare(strict_types=1);
/**
 * The side view file of convert module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     convert
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();
foreach($stepList as $currentStep => $stepLabel)
{
    $icon = '';
    $url  = 'javascript:;';
    if($step == $currentStep)
    {
        $icon = icon('ellipsis-v', setClass('secondary rounded-full rotate-90 p-1 mx-2'));
    }
    else if(!empty($stepStatus[$currentStep]) && $stepStatus[$currentStep] == 'done')
    {
        $icon = icon('check', setClass('success rounded-full p-1 mx-2'));
        $url  = inlink('mapJira2Zentao', "method={$method}&dbName={$dbName}&step={$currentStep}");
        if($currentStep == 'user') $url = inlink('initJiraUser', "method={$method}&dbName={$dbName}");
    }
    else
    {
        $icon = icon('ellipsis-v', setClass('gray-200 rounded-full rotate-90 text-white p-1 mx-2'));
    }

    $items[] = a(set::href($url), div(setClass('h-10 border content-center mb-4'), $icon, span(setClass('text-black'), $stepLabel)));
}

featureBar();
toolbar
(
    $backUrl ? item(set(array('text' => $lang->convert->jira->back, 'class' => 'default', 'url' => $backUrl))) : null,
    $step != 'confirme' ? item(set(array('text' => $lang->convert->jira->next, 'class' => 'primary', 'data-on' => 'click', 'data-call' => 'next', 'data-params' => 'event'))) : null
);
