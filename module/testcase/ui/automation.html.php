<?php
declare(strict_types=1);
/**
 * The automation view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('automationTip', $lang->zanode->automationTips);
js
(
    <<<JAVASCRIPT
    new zui.Tooltip('#automationTip', {title: automationTip, trigger: 'hover', placement: 'bottom', type: 'black', 'className': 'border border-light'});
    JAVASCRIPT
);

to::header
(
   span
   (
       $lang->testcase->automation,
       set::class('article-h1'),
   ),
   icon
   (
       'help',
       set::class('text-gray'),
       set::id('automationTip'),
       set('data-toggle', 'tooltip'),
       set('title', 'tooltip'),
   )
);

form
(
    !$productID ?  formGroup
    (
        set::label($lang->testcase->product),
        set::required(true),
        select
        (
            set::name('product'),
            set::items($products),
            on::change('#product', 'loadProduct'),
        )
    ) : null,
    formGroup
    (
        set::label($lang->zanode->common),
        set::required(true),
        inputGroup
        (
            select
            (
                set::name('node'),
                set::items($nodeList),
                set::value(zget($automation, 'node', '')),
            ),
            div
            (
                set::class('input-group-btn'),
                a
                (
                    set::class('btn'),
                    $lang->zanode->create,
                    set::href(createLink('zanode', 'create')),
                    set::target('_blank'),
                )
            )
        )
    ),
    formGroup
    (
        set::label($lang->zanode->scriptPath),
        set::required(true),
        set::name('scriptPath'),
        set::value(zget($automation, 'scriptPath', '')),
        set::placeholder($lang->zanode->scriptTips),
    ),
    formGroup
    (
        set::label($lang->zanode->shell),
        editor
        (
            set::name('shell'),
            set::value(zget($automation, 'shell', '')),
            set::rows(6),
            set::placeholder($lang->zanode->shellTips),
        )
    ),
    $productID ? input
    (
        set::type('hidden'),
        set::name('product'),
        set::value($productID),
    ) : null,
    $automation ? input
    (
        set::type('hidden'),
        set::name('id'),
        set::value($automation->id),
    ) : null,
    set::actions(array('submit')),
    set::submitBtnText($lang->save),
);

render('modalDialog');
