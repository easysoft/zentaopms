<?php
declare(strict_types=1);
/**
 * The create branch view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

to::header(false);
to::main(false);

jsVar('linkParams', "taskID={$taskID}&executionID={$executionID}&repoID=%s");
formPanel
(
    on::change('#repoID', 'onRepoChange'),
    set::title($lang->repo->createBranchAction),
    formGroup
    (
        setID('repoID'),
        set::name('repoID'),
        set::label($lang->repo->codeRepo),
        set::control('picker'),
        set::required(true),
        set::items($repoPairs),
        set::value($repoID),
        set::disabled(count($repoPairs) == 1)
    ),
    formGroup
    (
        set::id('from'),
        set::name('from'),
        set::label($lang->repo->branchFrom),
        set::required(true),
        set::control('picker'),
        set::items($branches)
    ),
    formGroup
    (
        set::name('name'),
        set::label($lang->repo->branchName),
        set::required(true),
    ),
    set::actions(array('submit'))
);

render();
