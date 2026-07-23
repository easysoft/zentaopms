<?php
declare(strict_types=1);
/**
 * The show import progress view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;
set::zui(true);

div
(
    setID('main'),
    div
    (
        setID('mainContainer'),
        setClass('container'),
        div
        (
            setID('mainContent'),
            setStyle(array('display' => 'flex', 'justify-content' => 'center', 'padding-top' => '40px')),
            panel
            (
                setID('tipsModal'),
                setClass('m-auto w-full'),
                set::title($lang->repo->tips),
                set::headingActions(array
                (
                    array('url' => createLink('repo', 'maintain'), 'icon' => 'close', 'class' => 'ghost', 'data-app' => 'devops')
                )),
                div
                (
                    set::className('flex items-center mt-2'),
                    $message ? icon('close text-danger icon-2x mr-2') :
                    icon('check-circle text-success icon-2x mr-2'),
                    span
                    (
                        set::className('text-md font-bold tip-title'),
                        $message ? $message : $lang->repo->importProgress->successTips
                    )
                ),
                div
                (
                    setClass('mt-5 mb-5 flex gap-y-2 flex-wrap'),
                    btn
                    (
                        set::className('mr-2 tipBtn'),
                        $message ? $lang->repo->importProgress->tryAgain : $lang->repo->importProgress->toRepoBrowse,
                        $message ? set::url(createLink('repo', 'import', "spaceID={$spaceID}&type=GitLab&providerID=0&groupID=&isTryAgain=1")) : set::url(createLink('repo', 'browse', "repoID={$repoID}"))
                    ),
                    btn
                    (
                        set::className('mr-2 tipBtn'),
                        $lang->repo->importProgress->toRepoList,
                        set::url(createLink('repo', 'maintain'))
                    )
                )
            )
        )
    )
);

render('pagebase');
