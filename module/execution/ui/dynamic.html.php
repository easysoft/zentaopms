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
            on::change('changeUser'),
        ),
    ),
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
        ),
    );
}
else
{
    $content     = array();
    $firstAction = '';
    foreach($dateGroups as $date => $actions)
    {
        $isToday   = date(DT_DATE4) == $date;
        $content[] = div
        (
            setClass('flex flex-col mt-4'),
            div
            (
                setClass('flex items-center justify-between w-30 p-2 border-2 rounded-md date-block'),
                span
                (
                    setClass('text-gray'),
                    $isToday ? $lang->action->dynamic->today : $date,
                ),
                btn
                (
                    setClass('ml-4 btn-link nav-feature'),
                    set::type('link'),
                    icon('caret-down btn-caret'),
                    on::click('toggleActions'),
                )
            ),
            div
            (
                setClass('flex-auto p-2 ml-4 border-2 rounded-md px-4'),
                dynamic
                (
                    set::dynamics($actions),
                    set::users($accountPairs),
                )
            )
        );
    }
}

panel
(
    $content
);
