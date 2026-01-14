<?php
declare(strict_types=1);
/**
 * The review view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->mr->review),
    formGroup
    (
        set::label($lang->mr->decision),
        set::name('decision'),
        set::required(true),
        set::value(zget($reviewer, 'decision') == 'pending' ? 'approve' : zget($reviewer, 'decision')),
        set::control('radioListInline'),
        set::items($lang->mr->approvalResultList)
    ),
    formGroup
    (
        set::label($lang->mr->opinion),
        set::name('opinion'),
        set::control(array('control' => 'editor', 'upload-url' => 'disabled')),
        set::value(zget($reviewer, 'opinion', ''))
    )
);
