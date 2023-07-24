<?php
declare(strict_types=1);
/**
 * The managepriv view file of group module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */
namespace zin;

if($type == 'byGroup')  include 'privbygroup.html.php';
if($type == 'byPackage')  include 'privbypackage.html.php';
if($type == 'byModule') include 'privbymodule.html.php';
