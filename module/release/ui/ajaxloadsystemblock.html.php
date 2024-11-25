<?php
declare(strict_types=1);
/**
 * The ajaxLoadSystemBlock view file of release module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('releases', $releases);
jsVar('appList',  $appList);

$systemTR = array();
$i        = 0;
