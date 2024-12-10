<?php
declare(strict_types=1);
/**
 * The edit view file of release module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('appList',       $appList);
jsVar('oldStatus',     $release->status);
jsVar('linkedRelease', $release->releases);
jsVar('productID',     zget($product, 'id', 0));
jsVar('releaseBuilds', $release->build);
jsVar('releaseID',     $release->id);

formPanel
(
    set::title($lang->release->edit),
    on::change('[name=status]')->call('changeStatus'),
    on::change('[name=system]')->call('loadSystemBlock'),
    common::checkNotCN() ? set::labelWidth('160px') : null,
    formGroup
    (
        set::width('1/2'),
        set::label($lang->release->selectSystem),
        set::name('system'),
        set::control(array('type' => 'picker', 'required' => true)),
        set::items(array_column($appList, 'name', 'id')),
        set::required(!$release->isInclude),
        set::disabled($release->isInclude),
        set::value($release->system)
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('name'),
            set::label($lang->release->name),
            set::value($release->name)
        ),
        $app->tab != 'project' || empty($product->shadow) ? formGroup
        (
            set::width('1/4'),
            setClass('items-center'),
            checkbox(
                set::name('marker'),
                set::rootClass('ml-4'),
                set::value(1),
                set::checked(!empty($release->marker)),
                set::text($lang->release->marker)
            )
        ) : ''
    ),
    formGroup
    (
        setClass('hidden'),
        setID('systemBlock'),
        set::required(true),
        set::label($lang->release->includedSystem),
        div(setID('systemItems'), setClass('w-full'))
    ),
    formRow
    (
        setID('buildBox'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->release->includedBuild),
            picker
            (
                set::name('build[]'),
                set::placeholder($lang->build->placeholder->multipleSelect),
                set::items($builds),
                set::value($release->build),
                set::multiple(true)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::name('status'),
            set::label($lang->release->status),
            set::control(array('control' => 'picker', 'required' => true)),
            set::value($release->status),
            set::items($lang->release->statusList)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::name('date'),
            set::label($lang->release->date),
            set::value($release->date),
            set::control('date')
        )
    ),
    formRow
    (
        $release->status == 'wait' ? setClass('hidden') : null,
        formGroup
        (
            set::width('1/4'),
            set::name('releasedDate'),
            set::label($lang->release->releasedDate),
            set::value($release->releasedDate),
            set::control('date')
        )
    ),
    formGroup
    (
        set::label($lang->release->desc),
        set::required(strpos(",{$this->config->release->edit->requiredFields},", ",desc,") !== false),
        editor
        (
            set::name('desc'),
            html($release->desc),
            set::rows('10')
        )
    ),
    formGroup
    (
        set::label($lang->release->mailto),
        mailto(set::items($users), set::value($release->mailto))
    ),
    formGroup
    (
        set::label($lang->release->files),
        fileSelector($release->files ? set::defaultFiles(array_values($release->files)) : null)
    ),
    formHidden('product', $release->product)
);
