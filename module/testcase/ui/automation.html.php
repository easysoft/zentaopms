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

to::header
(
   span
   (
       $lang->testcase->automation,
       setClass('text-lg font-bold')
   ),
   icon
   (
       'help',
       setClass('text-gray'),
       toggle::tooltip
       (
           array
           (
               'title'     => $lang->zanode->automationTips,
               'placement' => 'right',
               'type'      => 'white',
               'className' => 'text-dark border border-light'
           )
       )
   )
);

formPanel
(
    $productID ? formHidden('product', $productID) : formGroup
    (
        set::label($lang->testcase->product),
        set::required(true),
        picker
        (
            set::name('product'),
            set::items($products),
            on::change('#product', 'loadProduct')
        )
    ),
    formGroup
    (
        set::label($lang->zanode->common),
        set::required(true),
        inputGroup
        (
            picker
            (
                setID('node'),
                set::name('node'),
                set::items($nodeList),
                set::value(isset($automation->node) ? $automation->node : '')
            ),
            div
            (
                setClass('input-group-btn'),
                a
                (
                    setClass('btn'),
                    $lang->zanode->create,
                    set::href(createLink('zanode', 'create')),
                    set::target('_blank')
                )
            )
        )
    ),
    formGroup
    (
        set::label($lang->zanode->scriptPath),
        set::required(true),
        set::name('scriptPath'),
        set::value(isset($automation->scriptPath) ? $automation->scriptPath : ''),
        set::placeholder($lang->zanode->scriptTips)
    ),
    formGroup
    (
        set::label($lang->zanode->shell),
        textarea
        (
            set::name('shell'),
            set::value(isset($automation->shell) ? $automation->shell : ''),
            set::rows(6),
            set::placeholder($lang->zanode->shellTips)
        )
    ),
    formHidden('id', isset($automation->id) ? $automation->id : 0),
    set::actions(array('submit')),
    set::submitBtnText($lang->save)
);
