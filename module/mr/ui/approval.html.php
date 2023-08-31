<?php
declare(strict_types=1);
/**
 * The approval view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title(''),
    set::entityText($MR->title . ' - ' . zget($lang->mr->approvalResultList, $action)),
    set::entityID($MR->id),
);

formPanel
(
    ($MR->needCI && $showCompileResult) ? formGroup
    (
        set::label($lang->compile->result),
        a
        (
            set::href($compileUrl),
            set::target('_blank'),
            $lang->compile->statusList[$MR->compileStatus],
        ),
    ) : null,
    formGroup
    (
        set::width('1/2'),
        set::label($lang->mr->assignee),
        set::name('assignedTo'),
        set::items($users),
        set::value($MR->createdBy),
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('textarea'),
        set::rows('6'),
    ),
);
history();

render();

