<?php
declare(strict_types=1);
/**
 * The dynamic view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

include './featurebar.html.php';

/* zin: Define the feature bar on main menu. */
$dynamicNavs = array();
foreach($lang->user->featureBar['dynamic'] as $key => $label)
{
    $dynamicNavs[$key] = array('text' => $label, 'url' => inlink('dynamic', "userID={$user->id}&period={$key}"));
}
if(isset($dynamicNavs[$period])) $dynamicNavs[$period]['active'] = true;

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
                    setClass($period == 'today' ? 'border-secondary' : ''),
                    dynamic
                    (
                        set::dynamics($actions),
                        set::users($users)
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


panel
(
    set::title(null),
    set::headingActions(array(nav(set::items($dynamicNavs)))),
    $content
);

if(!empty($firstAction))
{
    $firstDate = date('Y-m-d', strtotime($firstAction->originalDate) + 24 * 3600);
    $lastDate  = substr($lastAction->originalDate, 0, 10);
    $hasPre    = $this->action->hasPreOrNext($firstDate, 'pre');
    $hasNext   = $this->action->hasPreOrNext($lastDate, 'next');
    $preLink   = $hasPre ? inlink('dynamic', "userID={$user->id}&period={$period}&recTotal={$recTotal}&date=" . strtotime($firstDate) . '&direction=pre') : 'javascript:;';
    $nextLink  = $hasNext ? inlink('dynamic', "userID={$user->id}&period={$period}&recTotal={$recTotal}&date=" . strtotime($lastDate) . '&direction=next') : 'javascript:;';

    if($hasPre || $hasNext)
    {
        floatPreNextBtn
        (
            empty($hasNext)  ? null : set::preLink($nextLink),
            empty($hasPre) ? null : set::nextLink($preLink)
        );
    }
}

render();
