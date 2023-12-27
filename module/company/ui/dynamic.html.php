<?php
declare(strict_types=1);
/**
 * The dynamic view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('browseType', $browseType);

/* zin: Define the feature bar on main menu. */
featureBar
(
    set::current($browseType),
    set::linkParams("browseType={key}&param={$param}&recTotal=0&date=&direction=next&userID={$userID}&productID={$productID}&projectID={$projectID}&executionID={$executionID}&orderBy={$orderBy}"),
    li
    (
        setClass('w-28 ml-4'),
        picker
        (
            setID('user'),
            set::name('user'),
            set::placeholder($lang->user->common),
            set::items($userIdPairs),
            set::value($userID),
            on::change('changeItem')
        )
    ),
    $this->config->vision == 'rnd' ? li
    (
        setClass('w-28 ml-4'),
        picker
        (
            setID('product'),
            set::name('product'),
            set::placeholder($lang->product->common),
            set::items($products),
            set::value($productID),
            set::required(true),
            on::change('changeItem')
        )
    ) : null,
    li
    (
        setClass('w-28 ml-4'),
        picker
        (
            setID('project'),
            set::name('project'),
            set::placeholder($lang->project->common),
            set::items($projects),
            set::value($projectID),
            set::required(true),
            on::change('changeItem')
        )
    ),
    li
    (
        setClass('w-28 ml-4'),
        picker
        (
            setID('execution'),
            set::name('execution'),
            set::placeholder($lang->execution->common),
            set::items($executions),
            set::value($executionID),
            set::required(true),
            on::change('changeItem')
        )
    ),
    li
    (
        setClass('w-28 ml-4'),
        picker
        (
            setID('orderBy'),
            set::name('orderBy'),
            set::placeholder($lang->execution->common),
            set::items($lang->company->order),
            set::value($orderBy),
            set::required(true),
            on::change('changeItem')
        )
    ),
    li(searchToggle(set::module('action'), set::open($browseType == 'bysearch')))
);

$content = null;
if(empty($dateGroups))
{
    $content = div
    (
        setClass('flex items-center justify-center h-64'),
        span
        (
            setClass('text-gray'),
            $lang->action->noDynamic
        )
    );
}
else
{
    $content     = array();
    $firstAction = '';
    $lastAction  = '';
    foreach($dateGroups as $date => $actions)
    {
        $isToday = date(DT_DATE3) == $date;
        if(empty($firstAction)) $firstAction = reset($actions);
        $content[] = li
        (
            div
            (
                setClass('cursor-pointer leading-5'),
                span
                (
                    icon('angle-down text-primary border-2 rounded-full z-10 bg-canvas align-middle')
                ),
                span
                (
                    setClass('ml-2'),
                    $isToday ? $lang->today : $date
                ),
                on::click('toggleCollapse')
            ),
            div
            (
                setClass('border-l border-l-1 mx-2 px-4 py-3'),
                div
                (
                    setClass('flex-auto px-4 alert actions-box'),
                    setClass($browseType == 'today' ? 'border-secondary' : ''),
                    dynamic
                    (
                        set::dynamics($actions),
                        set::users($accountPairs)
                    )
                )
            )
        );
        $lastAction = end($actions);
    }

    $content = ul
    (
        setClass('timeline list-none p-0'),
        $content
    );
}


panel
(
    setID('companyDynamic'),
    $content
);

if(!empty($firstAction))
{
    $firstDate = date('Y-m-d', strtotime($firstAction->originalDate) + 24 * 3600);
    $lastDate  = substr($lastAction->originalDate, 0, 10);
    $hasPre    = $this->action->hasPreOrNext($firstDate, 'pre');
    $hasNext   = $this->action->hasPreOrNext($lastDate, 'next');
    $preLink   = $hasPre ? inlink('dynamic', "browseType={$browseType}&param={$param}&recTotal=0&date=" . strtotime($firstDate) . "&direction=pre&userID={$userID}&productID={$productID}&projectID={$projectID}&executionID={$executionID}&orderBy={$orderBy}") : 'javascript:;';
    $nextLink  = $hasNext ? inlink('dynamic', "browseType={$browseType}&param={$param}&recTotal=0&date=" . strtotime($lastDate) . "&direction=next&userID={$userID}&productID={$productID}&projectID={$projectID}&executionID={$executionID}&orderBy={$orderBy}") : 'javascript:;';

    if($hasPre || $hasNext)
    {
        floatPreNextBtn
        (
            empty($hasNext)  ? null : set::preLink($nextLink),
            empty($hasPre) ? null : set::nextLink($preLink)
        );
    }
}
