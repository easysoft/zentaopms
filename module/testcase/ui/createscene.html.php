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

    on::change('#product', 'loadProductRelated'),
    on::change('#branch', 'loadBranchRelated'),
    on::change('#module', 'loadModuleRelated'),
    on::click('.refresh', 'refreshModules'),
    formRow
    (
        formGroup
        (
            set::label($lang->testcase->product),
            set::width('1/2'),
            inputGroup
            (
                picker
                (
                    setID('product'),
                    set::name('product'),
                    set::items($products),
                    set::value($product->id),
                    set::required(true)
                ),
                isset($product->type) && $product->type != 'normal' ? picker
                (
                    setID('branch'),
                    zui::width('120px'),
                    set::name('branch'),
                    set::items($branches),
                    set::value($branch),
                    set::required(true)
                ) : null
            )
        ),
        formGroup
        (
            inputGroup
            (
                $lang->testcase->module,
                setID('moduleIdBox'),
                picker
                (
                    setID('module'),
                    set::name('module'),
                    set::items($modules),
                    set::value($moduleID),
                    set::required(true)
                ),
                count($modules) == 1 ? div
                (
                    setClass('input-group-btn flex'),
                    a
                    (
                        $lang->tree->manage,
                        set('href', createLink('tree', 'browse', "rootId=$productID&view=case&currentModuleID=0&branch=$branch")),
                        setClass('btn'),
                        setData(array('toggle', 'mdoal'))
                    ),
                    a
                    (
                        $lang->refresh,
                        set('href', 'javascript:;'),
                        setClass('btn refresh')
                    )
                ) : null
            )
        )
    ),
    formGroup
    (
        set::label($lang->testcase->parentScene),
        setID('sceneIdBox'),
        picker
        (
            setID('parent'),
            set::name('parent'),
            set::items($scenes),
            set::value($parent),
            set::required(true)
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
