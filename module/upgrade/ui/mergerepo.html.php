<?php
declare(strict_types=1);
/**
 * The mergerepo view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$repoItems = array();
foreach($repoes as $repoID => $repo)
{
    $repoItems[] = checkbox
    (
        on::change('checkRepo'),
        set::id("repoes[{$repoID}]"),
        set::name("repoes[{$repoID}]"),
        set::text($repo)
    );
}

div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        setClass('px-1 mt-2 mx-auto'),
        width('1200px'),
        panel
        (
            setClass('p-8'),
            set::title($lang->upgrade->mergeRepo),
            set::actions(array()),
            cell
            (
                setClass('flex gap-y-2 p-2 text-secondary tips'),
                $lang->upgrade->mergeRepoTips
            ),
            form
            (
                set::actions(array('submit')),
                cell
                (
                    setClass('flex flex-nowrap mt-4 gap-x-4'),
                    col
                    (
                        setClass('flex border w-1/2 p-4'),
                        cell
                        (
                            cell
                            (
                                setClass('pb-2'),
                                checkbox
                                (
                                    on::change('checkAllRepoes'),
                                    set::id('checkAllRepoes'),
                                    set::name('checkAllRepoes'),
                                    set::text($lang->upgrade->repo)
                                )
                            ),
                            cell
                            (
                                setClass('check-list repo-list'),
                                $repoItems
                            )
                        )
                    ),
                    col
                    (
                        setClass('flex border w-1/2 p-4'),
                        width('50%'),
                        formGroup
                        (
                            set::label($lang->upgrade->product),
                            picker
                            (
                                set::name('products[]'),
                                set::items($products),
                                set::multiple(true)
                            )
                        )
                    )
                )
            )
        )
    )
);

render('pagebase');
