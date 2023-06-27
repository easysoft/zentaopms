<?php
declare(strict_types=1);
/**
 * The create scene view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('tab', $this->app->tab);
jsVar('caseModule', $lang->testcase->module);

formPanel
(
    set::title($lang->testcase->createScene),

    on::change('#product', 'loadProductBranch'),
    on::change('#branch', 'loadProductModule'),
    on::change('#module', 'loadModuleRelatedNew'),
    on::click('.refresh', 'loadProductModule'),
    formRow
    (
        formGroup
        (
            set::label($lang->testcase->product),
            set::width('1/2'),
            inputGroup
            (
                select
                (
                    set::name('product'),
                    set::items($products),
                    set::value($productID),
                ),
                isset($product->type) && $product->type != 'normal' ? select
                (
                    zui::width('120px'),
                    set::name('branch'),
                    set::items($branches),
                    set::value($branch)
                ) : null
            )
        ),
        formGroup
        (
            inputGroup
            (
                $lang->testcase->module,
                set::id('moduleIdBox'),
                select
                (
                    set::name('module'),
                    set::items($moduleOptionMenu),
                    set::value($currentModuleID),
                ),
                count($moduleOptionMenu) == 1 ? div
                (
                    set::class('input-group-btn flex'),
                    a
                    (
                        $lang->tree->manage,
                        set('href', createLink('tree', 'browse', "rootId=$productID&view=case&currentModuleID=0&branch=$branch")),
                        set('class', 'btn'),
                        set('data-toggle', 'mdoal'),
                    ),
                    a
                    (
                        $lang->refresh,
                        set('href', 'javascript:;'),
                        set('class', 'btn refresh')
                    )
                ) : null
            )
        )
    ),
    formGroup
    (
        set::label($lang->testcase->parentScene),
        set::id('sceneIdBox'),
        select
        (
            set::name('parent'),
            set::items($sceneOptionMenu),
            set::value($currentParentID),
        )
    ),
    formGroup
    (
        set::label($lang->testcase->sceneTitle),
        set::required(true),
        set::name('title')
    )
);

render();
