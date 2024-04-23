<?php
declare(strict_types=1);
/**
 * The header view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

$badgesOptions = array();
$badgesOptions['mode']            = $mode;
$badgesOptions['todoCount']       = $todoCount;
$badgesOptions['isIPD']           = $isIPD;
$badgesOptions['isMax']           = $isMax;
$badgesOptions['isBiz']           = $isBiz;
$badgesOptions['isOpenedURAndSR'] = $isOpenedURAndSR;
$badgesOptions['rawMethod']       = $app->rawMethod;

query('#featureBar')->append(on::init()->call('updateMainNavbarBadges', $badgesOptions));
