<?php
declare(strict_types=1);
/**
 * The managePriv view file of space module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;

dropmenu
(
    set::module('space'),
    set::tab('space'),
    set::objectID($spaceID),
    set::url(createLink('space', 'ajaxGetDropMenu', "spaceID=$spaceID&module={$app->rawModule}&method={$app->rawMethod}"))
);

if($type == 'byGroup')   include 'privbygroup.html.php';
if($type == 'byPackage') include 'privbypackage.html.php';
