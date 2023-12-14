<?php
declare(strict_types=1);
/**
 * The dynamic view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('projectID', $projectID);
/* zin: Define the feature bar on main menu. */
featureBar
(
    set::current($type),
    set::linkParams("projectID={$projectID}&type={key}"),
    li
    (
        setClass('w-40'),
        picker
        (
            setID('user'),
            set::name('user'),
            set::placeholder($lang->execution->viewByUser),
            set::items($userIdPairs),
            set::value($param),
            on::change('changeUser')
        )
    )
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
        $isToday   = date(DT_DATE4) == $date;
        if(empty($firstAction)) $firstAction = reset($actions);
        $content[] = li
        (
            div
            (
                setClass('my-4 cursor-pointer'),
                icon('angle-down text-primary border-2 rounded-full z-10 bg-canvas'),
                span
                (
                    setClass('font-bold ml-2'),
                    $isToday ? $lang->action->dynamic->today : $date
                ),
                on::click('toggleCollapse')
            ),
            div
            (
                setClass('flex-auto mx-6 mt-2 px-4 alert lighter'),
                setClass($type == 'today' ? 'border-secondary' : ''),
                dynamic
                (
                    set::dynamics($actions),
                    set::users($accountPairs)
                )
            )
        );
        $lastAction = end($actions);
    }

    $content = ul
    (
        setClass('timeline'),
        $content
    );
}


panel($content);

if(!empty($firstAction))
{
    $firstDate = date('Y-m-d', strtotime($firstAction->originalDate) + 24 * 3600);
    $lastDate  = substr($lastAction->originalDate, 0, 10);
    $hasPre    = $this->action->hasPreOrNext($firstDate, 'pre');
    $hasNext   = $this->action->hasPreOrNext($lastDate, 'next');
    $preLink   = $hasPre ? inlink('dynamic', "projectID=$projectID&type=$type&param=$param&recTotal={$recTotal}&date=" . strtotime($firstDate) . '&direction=pre') : 'javascript:;';
    $nextLink  = $hasNext ? inlink('dynamic', "projectID=$projectID&type=$type&param=$param&recTotal={$recTotal}&date=" . strtotime($lastDate) . '&direction=next') : 'javascript:;';

    if($hasPre || $hasNext)
    {
        floatPreNextBtn
        (
            empty($hasNext)  ? null : set::preLink($nextLink),
            empty($hasPre) ? null : set::nextLink($preLink)
        );
    }
}
