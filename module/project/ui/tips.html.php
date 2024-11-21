<?php
declare(strict_types=1);
/**
 * The tips view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$productID = key($products);
$objectID  = !empty($executionID) ? $executionID : $projectID;

$executionLang = $lang->execution->typeList['sprint'];
if($project->model == 'kanban') $executionLang = $lang->execution->typeList['kanban'];
if($project->model == 'waterfall' || $project->model == 'waterfallplus') $executionLang = $lang->execution->typeList['stage'];
