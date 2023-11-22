<?php
declare(strict_types=1);
/**
 * The renameobject view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$items[] = array
(
    'name'  => 'id',
    'label' => $lang->$type->id,
    'control' => 'index',
    'width' => '200px'
);

$items[] = array
(
    'name'  => 'name',
    'label' => $lang->$type->name,
    'readonly' => 'readonly',
    'width' => '200px'
);

$items[] = array
(
    'name'  => 'project',
    'label' => $lang->upgrade->editedName,
    'width' => '200px'
);

div
(
    setID('main'),
    div
    (
        setID('mainContent'),
        setClass('bg-white'),
        set::style(array('margin' => '50px auto 0', 'width' => '1200px')),
        formBatchPanel
        (
            $type == 'project' ? set::title($lang->upgrade->duplicateProject) : null,
            set::items($items),
            set::data(array_values($objectGroup)),
            set::actions(array('submit')),
            set::mode('edit'),
            set::actionsText(false)
        )
    )
);

render('pagebase');
