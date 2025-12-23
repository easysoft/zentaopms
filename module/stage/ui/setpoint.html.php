<?php
declare(strict_types=1);
/**
 * Set point view of stage module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）集团有限公司
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xieqiyu <xieqiyu@chandao.com>
 * @package     stage
 * @link        https://www.zentao.net
 */

namespace zin;

modalHeader
(
    set::title($type == 'TR' ? $lang->stage->setTRpoint : $lang->stage->setDCPpoint),
    set::titleClass('text-md font-semibold')
);

formBatchPanel
(
    setID('setPointForm'),
    set::minRows(1),
    set::sortable(true),
    set::data(array_values($stagePoints)),
    set::idKey('index'),
    formBatchItem
    (
        set::name('id'),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('title'),
        set::required(true),
        set::label($type == 'TR' ? $lang->stage->TRname : $lang->stage->DCPname)
    ),
    formBatchItem
    (
        set::name('flow'),
        set::label($lang->stage->pointFlow),
        set::required(true),
        set::control('inputGroup'),
        inputGroup
        (
            picker
            (
                set::name('flow'),
                set::required(true),
                set::items($approvals),
                set::value(1)
            ),
            hasPriv('approvalflow', 'create') ? div
            (
                a(set::className('btn secondary ml-1'), set::href(createLink('approvalflow', 'create', 'workflow=&callback=refreshApproval')), setData(array('toggle' => 'modal')), $lang->review->createApproval)
            ) : null
        )
    )
);
