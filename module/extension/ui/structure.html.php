<?php
declare(strict_types=1);
/**
 * The structure view file of extension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
namespace zin;

$appRoot = $this->app->getAppRoot();
$files   = json_decode($extension->files);
$fileItems = array();
foreach($files as $file => $md5) $fileItems[] = $appRoot . $file . "</br>";

set::title($extension->name . '[' . $extension->code . ']' . $lang->extension->structure . ':');

div
(
    setClass('border bg-surface p-2'),
    html($fileItems)
);

render();
