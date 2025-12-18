<?php
declare(strict_types=1);
/**
 * The create review flow view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($title),
    set::labelWidth('240px'),
    formGroup
    (
        set::name(''),
        set::control('static'),
        set::label($lang->repo->basicInfo),
        set::labelClass('font-black')
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->repo->name),
        set::name('name'),
        set::required(true)
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->repo->applicableBranchTypes),
        set::required(true),
        inputGroup
        (
            div
            (
                setClass('w-full'),
                setID('branchTypesBox'),
                picker
                (
                    set::name('branchType'),
                    set::items(array()),
                    set::multiple(true),
                    set::required(true)
                )
            ),
            div
            (
                setClass('input-group-addon flex'),
                checkbox
                (
                    set::name('isAllBranchTypes'),
                    set::text($lang->repo->allBranchTypes)
                )
            )
        )
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->repo->desc),
        set::name('desc'),
        set::control(array('type' => 'textarea', 'rows' => 5)),
    ),
    formGroup
    (
        set::name(''),
        set::control('static'),
        set::label($lang->repo->aiReview),
        set::labelClass('font-black')
    ),
    formGroup
    (
        set::id('aiReview'),
        set::width('2/3'),
        set::name('aiReview'),
        set::required(true),
        set::label($lang->repo->aiAssistedReview),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->repo->aiReviewList),
        set::value('disable'),
        on::change('loadAiReviewScores')
    ),
    formGroup
    (
        set::id('aiReviewScores'),
        set::width('2/3'),
        set::required(true),
        set::name('aiReviewScores'),
        set::label($lang->repo->aiReviewScores),
        set::control(array('type' => 'number', 'min' => 0, 'max' => 10)),
        set::value(0)
    )
);
