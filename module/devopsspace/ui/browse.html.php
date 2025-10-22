<?php
declare(strict_types=1);
/**
 * The browse view file of devopsspace module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     devopsspace
 * @link        https://www.zentao.net
 */
namespace zin;

$canCreate  = hasPriv('devopsspace', 'create');
$createLink = $this->createLink('devopsspace', 'create');
$createItem = array('text' => $lang->devopsspace->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus', 'data-toggle' => 'modal');

featureBar();
toolbar
(
    $canCreate ? item(set($createItem)) : null,
);

$tableData = initTableData($spaces, $config->devopsspace->dtable->fieldList, $this->devopsspace);
dtable
(
    set::cols($config->devopsspace->dtable->fieldList),
    set::data($tableData),
    set::footPager(usePager())
);
