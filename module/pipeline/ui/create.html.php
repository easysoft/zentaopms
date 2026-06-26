<?php
declare(strict_types=1);
/**
 * The create view file of job module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     job
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->pipeline->createBtn),
    on::change('[name="createType"]')->call('loadExistPipeline'),
    on::change('[name="existPipeline"]')->call('copyPipeline'),
    formGroup
    (
        set::label($lang->pipeline->createType),
        set::name('createType'),
        set::required(true),
        set::value('new'),
        set::control('radioListInline'),
        set::items($lang->pipeline->createTypeList)
    ),
    formGroup
    (
        setClass('hidden'),
        set::label($lang->pipeline->existPipeline),
        set::name('existPipeline'),
        set::required(true),
        set::control(array('type' => 'picker', 'items' => $existPipelines))
    ),
    formGroup
    (
        set::name('name'),
        set::label($lang->pipeline->name),
        set::required(true)
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->pipeline->desc),
        set::control('editor')
    )
);
