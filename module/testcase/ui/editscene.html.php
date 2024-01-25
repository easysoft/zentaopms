<?php
declare(strict_types=1);
/**
 * The editscene view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('tab', $this->app->tab);
jsVar('caseModule', $lang->testcase->module);

formPanel
(
    entityLabel
    (
        to::prefix($lang->testcase->editScene),
        set::entityID($scene->id),
        set::level(1),
        set::text($scene->title),
        set::reverse(true),
    ),
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
            set::required(true),
            inputGroup
            (
                picker
                (
                    setID('product'),
                    set::name('product'),
                    set::items($products),
                    set::value($scene->product),
                    set::required(true)
                ),
                isset($product->type) && $product->type != 'normal' ? picker
                (
                    setID('branch'),
                    zui::width('120px'),
                    set::name('branch'),
                    set::items($branches),
                    set::value($scene->branch),
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
                    set::value($scene->module),
                    set::required(true)
                ),
                count($modules) == 1 ? div
                (
                    setClass('input-group-btn flex'),
                    btn
                    (
                        setData(array('toggle' => 'modal', 'size' => 'lg')),
                        set::text($lang->tree->manage),
                        set::url(createLink('tree', 'browse', "rootId={$scene->product}&view=case&currentModuleID=0&branch={$scene->branch}"))
                    ),
                    a
                    (
                        setClass('btn refresh'),
                        $lang->refresh,
                        set('href', 'javascript:;')
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
            set::name('parent'),
            set::items($scenes),
            set::value($scene->parent)
        )
    ),
    formGroup
    (
        set::label($lang->testcase->sceneTitle),
        set::required(true),
        set::name('title'),
        set::value($scene->title)
    )
);

render();
