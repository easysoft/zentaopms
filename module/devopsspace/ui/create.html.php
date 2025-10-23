<?php
declare(strict_types=1);
/**
 * The create view file of devopsspace module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     devopsspace
 * @link        https://www.zentao.net
 */
namespace zin;

$width = common::checkNotCN() ? '3/4' : '1/2';
formPanel
(
    set::id('createForm'),
    set::title($title),
    set::titleClass('text-lg gap-0'),
    set::submitBtnText($lang->save),
    set::labelWidth(common::checkNotCN() ? '160px' : '100px'),
    formGroup
    (
        set::name('name'),
        set::width($width),
        set::label($lang->devopsspace->name),
        set::required(true)
    ),
    formGroup
    (
        set::name('owner'),
        set::width($width),
        set::label($lang->devopsspace->owner),
        set::items($users),
        set::required(true)
    ),
    formGroup
    (
        set::name('team'),
        set::width($width),
        set::label($lang->devopsspace->team),
        set::items($users),
        set::multiple(true)
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->devopsspace->desc),
        set::control(array('control' => 'editor', 'upload' => 'false'))
    ),
    formGroup
    (
        set::name('acl'),
        set::label($lang->devopsspace->acl),
        set::control('radioList'),
        set::items($lang->devopsspace->aclList),
        set::value('open')
    )
);
