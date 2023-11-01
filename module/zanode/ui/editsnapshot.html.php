<?php
declare(strict_types=1);
/**
 * The editsnapshot view file of zanode module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     zanode
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->zanode->editSnapshot),
    formGroup
    (
        set::name('name'),
        set::label($lang->zanode->snapshotName),
        set::value($snapshot->localName ? $snapshot->localName : $snapshot->name)
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->zanode->desc),
        set::control('textarea'),
        set::value($snapshot->desc)
    ),
);

render();

