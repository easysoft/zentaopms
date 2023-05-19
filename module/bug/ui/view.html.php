<?php
declare(strict_types=1);
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        http://www.zentao.net
 */
namespace zin;
panel
(
    div
    (
        set('class', 'flex'),
        cell
        (
            set('width', '70%'),
            set('class', 'border-r'),
            history(),
            center
            (
                floatToolbar
                (
                    set::prefix
                    (
                        array(array('icon' => 'back', 'text' => $lang->goback))
                    ),
                    set::main($actionList),
                    set::suffix
                    (
                        array
                        (
                            array('icon' => 'edit', 'url' => helper::createLink('bug', 'edit', "bugID={$bug->id}")),
                            array('icon' => 'copy', 'url' => helper::createLink('bug', 'create', "productID={$bug->product}&branch={$bug->branch}&extras=bugID={$bug->id}")),
                            array('icon' => 'trash', 'url' => helper::createLink('bug', 'delete', "bugID={$bug->id}")),
                        )
                    )
                )
            )
        ),
        cell
        (
            set('width', '30%'),
            set('class', 'px-4'),
            tabs
            (
                set::items
                (
                    array
                    (
                        array('id' => 'legendBasicInfo', 'label' => $lang->bug->legendBasicInfo, 'data' => '123', 'active' => true),
                        array('id' => 'legendLife', 'label' => $lang->bug->legendMisc, 'data' => '456'),
                    )
                )
            ),
            tabs
            (
                set::items
                (
                    array
                    (
                        array('id' => 'legendExecStoryTask', 'label' => (!empty($project->multiple) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask), 'data' => '123', 'active' => true),
                        array('id' => 'legendMisc', 'label' => $lang->bug->legendMisc, 'data' => '456'),
                    )
                )
            )
        )
    )
);
render();
