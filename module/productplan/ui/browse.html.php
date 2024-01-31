<?php
declare(strict_types=1);
/**
 * The browse view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;
$privs = array
(
    'canViewPlan'     => common::hasPriv($app->rawModule, 'view'),
    'canStartPlan'    => common::hasPriv($app->rawModule, 'start'),
    'canClosePlan'    => common::hasPriv($app->rawModule, 'close'),
    'canFinishPlan'   => common::hasPriv($app->rawModule, 'finish'),
    'canActivatePlan' => common::hasPriv($app->rawModule, 'activate')
);

jsVar('productID',       $productID);
jsVar('productplanLang', $lang->productplan);
jsVar('privs',           $privs);
jsVar('rawModule',       $app->rawModule);
jsVar('currentTab',      $app->tab);
include("browseby{$viewType}.html.php");
