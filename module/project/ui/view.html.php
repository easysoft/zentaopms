<?php
declare(strict_types=1);
/**
 * The view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$blocks = array(
    array(
        'id'    => 1,
        'size'  => 'sm',
        'domID' => 'dynamicBlock'
    ),
    array(
        'id'    => 2,
        'size'  => 'sm',
        'domID' => 'memberBlock'
    ),
    array(
        'id'   => 3,
        'size' => 'xl',
        'domID' => 'basicBlock'
    ),
    array(
        'id'    => 4,
        'size'  => 'smWide',
        'domID' => 'historyBlock',
    )
);
jsVar('blocks', $blocks);

dashboard
(
    setID('projectDashBoard'),
    set::blocks($blocks),
    set::blockMenu(false)
);

/* Dynamic list. */
$dynamicDom = array();
foreach($dynamics as $action)
{
    $dynamicDom[] = li
    (
        setClass($action->major ? 'active': ''),
        div
        (
            span(
                setClass('timeline-tag'),
                $action->date
            ),
            span(
                setClass('timeline-text clip'),
                zget($users, $action->actor),
                span
                (
                    setClass('text-gray'),
                    " {$action->actionLabel} "
                ),
                span(" {$action->objectLabel} "),
                a
                (
                    setClass('clip'),
                    set::href($action->objectLink),
                    set::title($action->objectName),
                    $action->objectName
                )
            )
        )
    );
}

div
(
    setID('dynamicBlock'),
    setClass('hidden'),
    panel
    (
        to::heading
        (
            div
            (
                set('class', 'panel-title'),
                $lang->execution->latestDynamic,
            )
        ),
        to::headingActions
        (
            common::hasPriv('project', 'dynamic') ? btn
            (
                setClass('ghost text-gray'),
                set::url(createLink('project', 'dynamic', "projectID={$projectID}&type=all")),
                $lang->more
            ) : null
        ),
        set::bodyClass('pt-0 overflow-x-hidden'),
        ul
        (
            setClass('timeline timeline-tag-left no-margin'),
            $dynamicDom
        )
    )
);

/* Related members. */
$membersDom = array();
foreach(array('PM', 'PO', 'QD', 'RD') as $field)
{
    if(empty($project->$field)) continue;

    $membersDom[] = div
    (
        setClass('flex-initial w-1/2 py-1'),
        icon('person', setClass('mr-2')),
        zget($users, $project->$field),
        span
        (
            setClass('text-gray ml-2'),
            "( {$lang->project->$field} )"
        )
    );

    unset($teamMembers[$project->$field]);
}
$memberCount = count($membersDom);
foreach($teamMembers as $teamMember)
{
    if($memberCount >= 10) break;

    $membersDom[] = div
    (
        setClass('flex-initial w-1/2 py-1'),
        icon('person', setClass('mr-2')),
        zget($users, $teamMember->account),
    );
    $memberCount ++;
}

if(common::hasPriv('project', 'manageMembers'))
{
    $membersDom[] = div
    (
        setClass('flex-initial w-1/2 py-1'),
        a
        (
            setClass('ghost text-gray'),
            icon('plus', setClass('bg-primary-50 text-primary mr-2')),
            span($lang->project->manageMembers),
            set::href(createLink('project', 'manageMembers', "projectID={$projectID}"))
        )
    );
}

div
(
    setID('memberBlock'),
    setClass('hidden'),
    panel
    (
        to::heading
        (
            div
            (
                set('class', 'panel-title'),
                $lang->execution->relatedMember
            )
        ),
        to::headingActions
        (
            common::hasPriv('project', 'team') ? btn
            (
                setClass('ghost text-gray'),
                set::url(createLink('project', 'team', "projectID={$projectID}")),
                $lang->more
            ) : null
        ),
        set::bodyClass('flex flex-wrap pt-0'),
        $membersDom
    )
);

div
(
    setID('historyBlock'),
    setClass('hidden'),
    div
    (
        setClass('overflow-y-auto h-full'),
        history()
    )
);
render();
