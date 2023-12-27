<?php
declare(strict_types=1);
/**
 * The dynamic view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('executionID', $executionID);
/* zin: Define the feature bar on main menu. */
featureBar
(
    set::current($type),
    set::linkParams("executionID={$executionID}&type={key}"),
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
                setClass('cursor-pointer leading-5'),
                span
                (
                    icon('angle-down text-primary border-2 rounded-full z-10 bg-canvas align-middle')
                ),
                span
                (
                    setClass('ml-2'),
                    $isToday ? $lang->action->dynamic->today : $date
                ),
                on::click('toggleCollapse')
            ),
            div
            (
                setClass('border-l border-l-1 mx-2 px-4 py-3'),
                div
                (
                    setClass('flex-auto px-4 alert actions-box'),
                    setClass($type == 'today' ? 'border-secondary' : ''),
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
        setClass('timeline list-none pl-0'),
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
    $preLink   = $hasPre ? inlink('dynamic', "executionID=$executionID&type=$type&param=$param&recTotal={$recTotal}&date=" . strtotime($firstDate) . '&direction=pre') : 'javascript:;';
    $nextLink  = $hasNext ? inlink('dynamic', "executionID=$executionID&type=$type&param=$param&recTotal={$recTotal}&date=" . strtotime($lastDate) . '&direction=next') : 'javascript:;';

    if($hasPre || $hasNext)
    {
        floatPreNextBtn
        (
            empty($hasNext)  ? null : set::preLink($nextLink),
            empty($hasPre) ? null : set::nextLink($preLink)
        );
    }
}
