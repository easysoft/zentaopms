<?php
declare(strict_types=1);
/**
 * The browse view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

$canCreate  = hasPriv('system', 'create');
$createLink = $this->createLink('system', 'create');
$createItem = array('text' => $lang->system->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus', 'data-toggle' => 'modal');

$tableData = initTableData($appList, $config->system->dtable->fieldList, $this->system);

featureBar
(
    backBtn
    (
        set::icon('back'),
        set::type('secondary'),
        $lang->goback
    )
);

toolbar
(
    $canCreate ? item(set($createItem)) : null,
);
