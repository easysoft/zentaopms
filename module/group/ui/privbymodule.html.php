<?php
declare(strict_types=1);
/**
 * The privByModule view file of group module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */
namespace zin;

to::header(
    span
    (
        set::class('article-h2'),
        $lang->group->managePriv,
    ),
    span
    (
        set::class('text-gray'),
        $lang->group->byModuleTips
    )
);

$packageBox  = null;
$hiddenClass = '';
foreach($packageGroup as $module => $modulePackages)
{
    $packageBox[] = select
        (
            set::name('packages[]'),
            set::class($hiddenClass),
            set::items($modulePackages),
            set::multiple(true),
            set::required(true),
            set('data-module', $module),
            on::change('setActions'),
        );

    $hiddenClass = 'hidden';
}

form
(
    set::submitBtnText($lang->save),
    set::id('privByModuleForm'),
    set::class('border-b-0'),
    formRow
    (
        cell
        (
            set::width('1/4'),
            set::class('mr-4'),
            div
            (
                set::class('text-center pb-2'),
                h::strong($lang->group->module),
            ),
            div
            (
                select
                (
                    set::name('module'),
                    set::items($modulePairs),
                    set::size(10),
                    set::required(true),
                    on::change('window.setModulePackages'),
                )
            )
        ),
        cell
        (
            set::width('1/4'),
            set::class('mr-4'),
            div
            (
                set::class('text-center pb-2'),
                h::strong($lang->privpackage->common)
            ),
            div
            (
                set::id('packageBox'),
                $packageBox,
            )
        ),
        cell
        (
            set::width('1/4'),
            set::class('mr-4'),
            div
            (
                set::class('text-center pb-2'),
                h::strong($lang->group->method)
            ),
            div
            (
                set::id('actionBox'),
                select
                (
                    set::name('actions[]'),
                    set::items($indexPrivs),
                    set::multiple(true),
                    set::required(true)
                )
            )
        ),
        cell
        (
            set::width('1/4'),
            div
            (
                set::class('text-center pb-2'),
                h::strong($lang->group->common)
            ),
            div
            (
                select
                (
                    set::name('groups[]'),
                    set::items($groups),
                    set::multiple(true),
                    set::required(true)
                )
            )
        ),
        formHidden('foo', ''),
    )
);

/* ====== Render page ====== */
