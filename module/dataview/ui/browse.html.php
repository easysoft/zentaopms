<?php
declare(strict_types=1);
/**
 * The browse view file of dataview module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     dataview
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('type', $type);
jsVar('groupTree',   json_encode($groupTree));
jsVar('originTable', json_encode($originTable));

featureBar();

toolbar
(
    item
    (
        set
        (
            array
            (
                'icon'  => 'export',
                'text'  => $lang->dataview->export,
                'class' => 'ghost pull-right',
                'url'   => createLink('dataview', 'export', "type=$type&tale=$selectedTable"),
                'data-toggle' => 'modal'
            )
        )
    ),
    item
    (
        set
        (
            array
            (
                'icon'  => 'plus',
                'text'  => $lang->dataview->create,
                'class' => 'primary pull-right',
                'url'   => createLink('dataview', 'create'),
            )
        )
    )
);

$settingLink = hasPriv('tree', 'browsegroup') ? createLink('tree', 'browsegroup', "dimensionID=0&groupID=0&type=dataview") : '';
sidebar
(
    tabs
    (
        tabpane
        (
            set::key('view'),
            set::title($lang->dataview->typeList['view']),
            set::active($type == 'view' ? true : false),
            moduleMenu(set(array
            (
                'titleShow'   => false,
                'showDisplay' => false,
                'modules'     => array(),
                'activeKey'   => $table
            )))
        ),
        tabpane
        (
            set::key('table'),
            set::title($lang->dataview->typeList['table']),
            set::active($type == 'table' ? true : false),
            moduleMenu(set(array
            (
                'titleShow'   => false,
                'showDisplay' => false,
                'modules'     => array(),
                'activeKey'   => $table
            )))
        )
    )
);

dtable
(
    set::cols(array()),
    set::data(array())
);

render();
