<?php
declare(strict_types=1);
/**
* The recentproject block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$cards = array();
foreach($projects as $projectID => $project)
{
    $viewLink = $this->createLink('project', 'index', "projectID=$project->id");
    $cards[] = cell
    (
        set('width', '32%'),
        set('class', 'border p-4'),
        div
        (
            set('class', 'pb-2'),
            a
            (
                set('class', 'text-black'),
                set('href', $viewLink),
                $project->name
            )
        ),
        div
        (
            set('class', 'card-body'),
            div
            (
                set('class', 'py-1.5'),
                span('近期执行 : '),
                a(set('href', ''), '执行名称'),
                label
                (
                    set('class', 'warning-pale circle ml-2'),
                    '进行中'
                )
            ),
            div
            (
                set('class', 'py-1.5'),
                span('项目成员 : 共 5 人')
            ),
            div
            (
                set('class', 'py-1.5'),
                span('计划完成 : 2022-09-30')
            )
        )
    );
}

panel
(
    set('class', 'recentproject-block'),
    to::heading
    (
        div
        (
            set('class', 'panel-title'),
            $lang->block->titleList['recentproject']
        )
    ),
    div
    (
        set('class', 'flex cards justify-between'),
        $cards
    )
);

render('|fragment');
