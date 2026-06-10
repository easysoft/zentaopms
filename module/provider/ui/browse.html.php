<?php
declare(strict_types=1);
/**
 * The browse view file of provider module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     provider
 * @link        https://www.zentao.net
 */
namespace zin;

$createItem = array
(
    'text'  => $lang->provider->create,
    'url'   => createLink('provider', 'create', 'type=GitLab'),
    'icon'  => 'plus',
    'class' => 'primary'
);

featureBar();
toolbar
(
    hasPriv('provider', 'create') ? item(set($createItem)) : null
);
