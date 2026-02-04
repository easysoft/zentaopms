<?php
declare(strict_types=1);
/**
 * The browse view file of artifact module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     artifact
 * @link        https://www.zentao.net
 */
namespace zin;
$canCreate  = hasPriv('artifact', 'create');
$createItem = array
(
    'text' => $lang->artifact->create,
    'url' => inLink('create', "spaceID={$spaceID}&repoID={$repoID}&type={$type}"),
    'class' => 'primary',
    'icon' => 'plus',
    'data-size' => 'sm',
    'data-toggle' => 'modal'
);

featureBar();
toolBar($canCreate ? item(set($createItem)) : null);

panel
(

);
