<?php
declare(strict_types=1);
/**
* The welcome view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      LiuRuoGu <liuruogu@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$usageDays     = 9999;
$doneReview    = 9999;
$finishTask    = 9999;
$fixBug        = 9999;

$reviewByMe['feedback'] = array('number' => '9999', 'delay' => '1111');
$reviewByMe['testcase'] = array('number' => '9999');
$reviewByMe['baseline'] = array('number' => '9999');
$assignToMe['task']     = array('number' => '9999', 'delay' => '1111');
$assignToMe['bug']      = array('number' => '9999');

$feedbackCount = 9999;
if($doneReview > $finishTask && $doneReview > $fixBug)
{
    $honorary = 'review';
}
else if($finishTask > $doneReview && $finishTask > $fixBug)
{
    $honorary = 'task';
}
else
{
    $honorary = 'bug';
}

panel
(
    set('class', 'welcome-block'),
    to::heading
    (
        div
        (
            set('class', 'panel-title flex w-full'),
            cell
            (
                set('width', '22%'),
                set('class', 'center'),
                span($todaySummary),

            ),
            cell
            (
                set::class('pr-8'),
                span(set('class', 'text-sm font-normal'), html(sprintf($lang->block->summary->welcome, $usageDays, $doneReview, $finishTask, $fixBug)))
            )
        )
    ),
    div
    (
        set('class', 'flex py-2'),
        cell
        (
            set('width', '22%'),
            set('align', 'center'),
            set('class', 'border-right'),
            center
            (
                set('class', 'font-bold'),
                sprintf($lang->block->welcomeList[$welcomeType], $app->user->realname)
            ),
            center
            (
                set('class', 'my-1'),
                center
                (
                    set('class', 'rounded-full avatar-border-one'),
                    center
                    (
                        set('class', 'rounded-full avatar-border-two'),
                        userAvatar
                        (
                            set('class', 'welcome-avatar ellipsis'),
                            set('user', $this->app->user)
                        )
                    )
                )
            ),
            center(span(set('class', 'label circle honorary text-xs'), $lang->block->honorary[$honorary]))
        ),
        cell
        (
            set('width', '45%'),
            set('class', 'border-right px-4'),
            div
            (
                set('class', 'font-bold'),
                $lang->block->welcome->reviewByMe
            ),
            div
            (
                setClass('flex justify-around pt-1'),
                getMeasureItem($reviewByMe)
            )
        ),
        cell
        (
            set('width', '35%'),
            set('class', 'border-right px-4'),
            div
            (
                set('class', 'font-bold'),
                $lang->block->welcome->assignToMe
            ),
            div
            (
                setClass('flex justify-around pt-1'),
                getMeasureItem($assignToMe)
            )
        )
    )
);

render();

function getMeasureItem($data)
{
    global $lang;

    $items = array();
    foreach($data as $key => $info)
    {
        $items[] = div
        (
            set('class', 'text-center'),
            div
            (
                set('class', 'text-3xl text-primary font-bold h-40px'),
                $info['number']
            ),
            div($lang->block->welcome->{$key}),
            !empty($info['delay']) ? div
            (
                set('class', 'label danger-pale circle size-sm'),
                $lang->block->delay . ' ' . $info['delay']
            ) : null
        );
    }
    return $items;
}
