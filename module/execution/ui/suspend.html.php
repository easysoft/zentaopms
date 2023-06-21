<?php
declare(strict_types=1);
/**
 * The suspend view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

$space = common::checkNotCN() ? ' ' : '';
formPanel
(
    set::title($lang->execution->suspend . $space . $lang->executionCommon),
    set::headingClass('status-heading'),
    set::titleClass('form-label .form-grid'),
    set::shadow(false),
    set::actions(array('submit')),
    set::submitBtnText($lang->execution->suspend . $space . $lang->executionCommon),
    to::headingActions
    (
        entityLabel
        (
            setClass('my-3 gap-x-3'),
            set::level(1),
            set::text($execution->name),
            set::entityID($execution->id),
            set::reverse(true),
        )
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('6'),
        )
    ),
);

h::hr(set::class('mt-6'));

history();

/* ====== Render page ====== */
render();
