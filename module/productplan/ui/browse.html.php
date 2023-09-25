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

jsVar('productID', $productID);
include("browseby{$viewType}.html.php");
