<?php
declare(strict_types=1);
/**
 * The product view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;
include 'sidebar.html.php';

div
(
    setClass('flex'),
    $sidebarMenu,
    formPanel
    (
        setID('closedProductForm'),
        set::actions(array('submit')),
        set::actionsClass('w-2/3'),
        setClass('flex-auto ml-4'),
        span
        (
            setClass('text-md font-bold'),
            $lang->custom->$module->fields['product']
        ),
        formGroup
        (
            set::width('2/3'),
            setClass('closed-product-box'),
            set::label($lang->custom->closedProduct),
            radioList
            (
                set::name('product'),
                set::items($lang->custom->CRProduct),
                set::value(isset($config->CRProduct) ? $config->CRProduct : 0),
                set::inline(true)
            )
        ),
        formGroup
        (
            set::label(''),
            span
            (
                setClass('flex items-center'),
                icon('info text-warning mr-1'),
                $lang->custom->notice->readOnlyOfProduct
            )
        )
    )
);

/* ====== Render page ====== */
render();
