<?php
declare(strict_types=1);
/**
 * The create view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::id('codescanCreateForm'),
    set::title($lang->codescan->createRuleset),
    formGroup
    (
        set::name('name'),
        set::required(true),
        set::label($lang->codescan->name)
    ),
    formGroup
    (
        set::name('lang'),
        set::label($lang->codescan->language),
        set::required(true),
        set::items($langList)
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->codescan->desc)
    )
);
