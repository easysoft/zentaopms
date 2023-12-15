<?php
declare(strict_types=1);
/**
 * The storyestimate view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->execution->storyEstimate),
    set::entityText($story->title),
    set::entityID($story->id)
);

if(empty($team))
{
    div
    (
        setClass('h-60 flex items-center justify-center'),
        p
        (
            setClass('text-gray-400'),
            span($lang->execution->noTeam)
        )
    );
}
else
{
    if(!empty($rounds))
    {
        $loadUrl = inLink('storyEstimate', "executionID={$executionID}&storyID={$storyID}&round=%s");

        div
        (
            setClass('flex items-center ml-2'),
            span($lang->execution->selectRound),
            picker
            (
                setClass('w-40 mx-3 my-1'),
                set::name('round'),
                set::items($rounds),
                set::value($round),
                set::required(true),
                on::change("selectRound('{$loadUrl}')")
            ),
            btn
            (
                set::type('primary'),
                on::click('$(".new-estimate, .form-actions > .btn").removeClass("hidden")'),
                $lang->execution->reestimate
            )
        );
    }

    $tableRows = array();
    foreach($team as $user)
    {
        $estimate = isset($estimateInfo->estimate->{$user->account}) ? $estimateInfo->estimate->{$user->account}->estimate : '';

        $tableRows[] = h::tr
        (
            h::td
            (
                input
                (
                    setClass('form-control'),
                    set::value(zget($users, $user->account)),
                    set::disabled(true)
                ),
                input
                (
                    set::type('hidden'),
                    set::name('account[]'),
                    set::value($user->account)
                )
            ),
            h::td
            (
                setClass('story-estimate'),
                input
                (
                    setClass('form-control'),
                    empty($estimateInfo->estimate) ? set::name('estimate[]') : null,
                    set::disabled(!empty($estimateInfo->estimate)),
                    set::value($estimate),
                    on::input('updateAverage()')
                )
            ),
            !empty($estimateInfo->estimate) ? h::td
            (
                setClass('new-estimate hidden'),
                input
                (
                    setClass('form-control'),
                    set::name('estimate[]'),
                    set::value($estimate),
                    on::input('updateAverage()')
                )
            ) : null
        );
    }

    formBase
    (
        h::table
        (
            setID('storyEstimateTable'),
            setClass('table condensed'),
            h::tbody
            (
                h::tr
                (
                    h::th($lang->execution->team),
                    h::th($lang->story->estimate),
                    !empty($estimateInfo->estimate) ? h::th(setClass('new-estimate hidden'), $lang->execution->newEstimate) : null
                ),
                $tableRows,
                h::tr
                (
                    h::td
                    (
                        input
                        (
                            setClass('form-control text-primary'),
                            set::value($lang->execution->average),
                            set::disabled(true)
                        )
                    ),
                    h::td
                    (
                        input
                        (
                            setClass('form-control text-primary'),
                            empty($estimateInfo->estimate) ? set::name('average') : null,
                            set::readonly(true),
                            set::value(!empty($estimateInfo->estimate) ? $estimateInfo->average : '')
                        )
                    ),
                    !empty($estimateInfo->estimate) ? h::td
                    (
                        setClass('new-estimate hidden'),
                        input
                        (
                            set::name('average'),
                            setClass('form-control text-primary'),
                            set::readonly(true),
                            set::value($estimateInfo->average)
                        )
                    ) : null
                )
            )
        ),
        set::actions(array(array(
            'text'      => $lang->save,
            'type'      => empty($estimateInfo->estimate) ? 'primary' : 'primary hidden',
            'btnType'   => 'submit'
        )))
    );
}
