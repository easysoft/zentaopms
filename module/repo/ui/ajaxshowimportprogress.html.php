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

jsVar('importProgressLeaveTip', $lang->repo->importProgress->leaveTip);
jsVar('importProgressAcknowledge', $lang->repo->importProgress->acknowledge);
jsVar('importProgressRepoID', $repoID);
jsVar('importProgressPollingLink', $this->createLink('repo', 'ajaxGetImportProgress', "repoID={$repoID}"));
jsVar('importProgressListLink', $this->createLink('repo', 'ajaxShowImportResult', "repoID={$repoID}&spaceID={$spaceID}"));

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
                setStyle(array('width' => '100%', 'max-width' => '800px', 'margin-top' => '10%')),
                set::title($lang->repo->importProgress->title),
                set::titleClass('text-xl font-bold text-secondary'),
                div
                (
                    h::p($lang->repo->importProgress->desc),
                    h::p($lang->repo->importProgress->notice)
                ),
                div
                (
                    setClass('repo-import-progress'),
                    set('role', 'progressbar'),
                    set('aria-busy', 'true'),
                    set('aria-valuetext', $lang->repo->importProgress->title),
                    div(setClass('repo-import-progress-bar'))
                )
            )
        )
    )
);

render('pagebase');
