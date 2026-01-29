<?php
declare(strict_types=1);
/**
 * The review view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->ppm->review),
    set::labelWidth(common::checkNotCN() ? '150px' : '100px'),
    formGroup
    (
        set::label($lang->ppm->decision),
        set::name('decision'),
        set::required(true),
        set::value(empty($reviewer) || zget($reviewer, 'decision') == 'pending' ? 'approved' : zget($reviewer, 'decision')),
        set::control('radioListInline'),
        set::items($lang->ppm->approvalResultList)
    ),
    formGroup
    (
        set::label($lang->ppm->opinion),
        set::name('opinion'),
        set::control(array('control' => 'editor', 'upload-url' => 'disabled', 'placeholder' => $lang->ppm->opinionPlaceholder)),
        set::value(zget($reviewer, 'opinion', ''))
    )
);
