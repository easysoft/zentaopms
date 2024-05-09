<?php
declare(strict_types=1);
/**
 * The createsnapshot view file of zanode module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     zanode
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->zanode->createSnapshot),
    formGroup
    (
        set::name('name'),
        set::required(true),
        set::label($lang->zanode->snapshotName)
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->zanode->desc),
        set::control('textarea')
    )
);

render();

