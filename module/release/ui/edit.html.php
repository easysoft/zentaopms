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

formPanel
(
    set::title($lang->release->edit),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('name'),
            set::label($lang->release->name),
            set::value($release->name),
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
                set::text($lang->release->marker),
            ),
        ) : '',
    ),
    formRow
    (
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
                set::multiple(true),
            ),
        ),
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
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::name('status'),
            set::label($lang->release->status),
            set::value($release->status),
            set::items($lang->release->statusList),
        ),
    ),
    formGroup
    (
        set::label($lang->release->desc),
        editor
        (
            set::name('desc'),
            html(htmlspecialchars_decode($release->desc)),
            set::rows('10'),
        )
    ),
    formGroup
    (
        set::label($lang->release->mailto),
        picker
        (
            set::name('mailto[]'),
            set::value($release->mailto),
            set::items($users),
            set::multiple(true),
        )
    ),
    formGroup
    (
        set::label($lang->release->files),
        upload()
    ),
    formHidden('product', $release->product),
);

/* ====== Render page ====== */
render();
