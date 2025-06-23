<?php
declare(strict_types=1);
/**
 * The ajaxGetMoreActions view file of action module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     action
 * @link        https://www.zentao.net
 */
namespace zin;

global $app;
helper::import($app->getCoreLibRoot() . 'zin/wg/dynamic/dynamicitem.php');

$lastAction = end($actions);

foreach($actions as $action) dynamicItem::build($action,$users);
div(setID('hasMore'), setClass('hidden'), $hasMore ? 1 : 0);
div(setID('lastid'), setClass('hidden'), empty($lastAction) ? '' : $lastAction->id);
