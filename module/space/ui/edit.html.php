<?php
declare(strict_types=1);
/**
 * The edit view file of space module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;

$width = common::checkNotCN() ? '3/4' : '1/2';
formPanel
(
    set::id('editForm'),
    set::title($title),
    set::titleClass('text-lg gap-0'),
    set::submitBtnText($lang->save),
    set::labelWidth(common::checkNotCN() ? '160px' : '100px'),
    formGroup
    (
        set::name('name'),
        set::width($width),
        set::label($lang->space->name),
        set::required(true),
        set::value($space->name)
    ),
    formGroup
    (
        set::name('code'),
        set::width($width),
        set::label($lang->space->code),
        set::required(true),
        set::disabled(true),
        set::value($space->code)
    ),
    formGroup
    (
        set::name('manager'),
        set::width($width),
        set::label($lang->space->manager),
        set::items($users),
        set::multiple(true),
        set::value($space->manager)
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->space->desc),
        set::control(array('control' => 'editor', 'placeholder' => $lang->space->placeholder->desc, 'upload-url' => 'disabled')),
        set::value($space->desc)
    ),
    formGroup
    (
        set::name('acl'),
        set::label($lang->space->acl),
        set::control('radioList'),
        set::items($lang->space->aclNoticeList),
        set::value($space->acl)
    ),
    formGroup
    (
        set::name('auth'),
        set::label($lang->space->auth),
        set::control('radioList'),
        set::items($lang->space->authNoticeList),
        set::value($space->auth)
    )
);
