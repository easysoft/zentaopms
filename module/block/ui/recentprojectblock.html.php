<?php
declare(strict_types=1);
/**
* The project block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wangyuting <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$cards = array();
foreach($projects as $projectID => $project)
{
    $viewLink = $this->createLink('project', 'index', "projectID=$project->id");
    $cards[] = div
    (
        set('class', 'card flex-1'), 
        div
        (
            set('class', 'card-header'),
            span($lang->project->{$project->model}),
            a
            (
                set('href', $viewLink),
                $project->name
            )
        ),
        div
        (
            set('class', 'card-body'),
            div
            (
                set('class', 'project-infos'),
                span(sprintf($lang->project->membersUnit, $project->teamCount)),
                span(sprintf($lang->project->hoursUnit, $project->estimate))
            )
        )
    );
}

panel
(
    set('class', 'recentproject-block'),
    div
    (
        set('class', 'flex cards'),
        $cards
    )
);

render();
