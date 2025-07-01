<?php
declare(strict_types=1);
/**
 * The plan modal view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jialiang Zhang <zhangjialiang@chandao.com>
 * @package     admin
 * @link        https://www.zentao.net
 */

namespace zin;

modalHeader(set::title($this->lang->admin->community->uxPlanTitle));

$uxPlan     = '';
$uxPlanFile = $this->app->getAppRoot() . 'www/uxplan.html';
if(file_exists($uxPlanFile))
{
    $uxPlan = file_get_contents($uxPlanFile);
}

echo $uxPlan;
