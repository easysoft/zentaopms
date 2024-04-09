<?php
declare(strict_types=1);
/**
 * The query view file of dataview module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou <zhouxin@easycorp.ltd>
 * @package     dataview
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar();

toolbar
(
    item
    (
        set
        (
            array
            (
                'icon'        => 'save',
                'text'        => $lang->save,
                'class'       => 'primary pull-right',
                'url'         => createLink('dataview', 'create', "view=create"),
                'data-toggle' => 'modal'
            )
        )
    )
);

panel
(
    h1
    (
        setClass('border-bottom margin-top24'),
        span
        (
            $lang->dataview->sqlQuery,
            setClass('gray-pale text-md font-bold')
        )
    ),
    formGroup
    (
        set::name('sql'),
        set::control('textarea')
    ),
    set::footerActions
    (
        array
        (
            array
            (
                'type' => 'primary',
                'class' => 'btn-query',
                'text' => $lang->dataview->query
            )
        )
    )
);

panel
(
    h1
    (
        setClass('border-bottom margin-top24'),
        span
        (
            $lang->dataview->result,
            setClass('gray-pale text-md font-bold')
        )
    ),
    div
    (
        setClass('table-empty-tip'),
        span
        (
            setClass('text-md'),
            $lang->dataview->noQueryData
        )
    )
);

render();
