<?php
declare(strict_types=1);
/**
 * The edit view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;
$fields = useFields('execution.edit');

jsVar('weekend', $config->execution->weekend);
jsVar('errorSameProducts', $lang->execution->errorSameProducts);
jsVar('unmodifiableProducts',$unmodifiableProducts);
jsVar('unmodifiableBranches', $unmodifiableBranches);
jsVar('linkedStoryIDList', $linkedStoryIDList);
jsVar('confirmSync', $lang->execution->confirmSync);
jsVar('unLinkProductTip', $lang->project->unLinkProductTip);
jsVar('typeTip', $lang->execution->typeTip);
jsVar('projectID', $execution->project);
jsVar('allProducts', $allProducts);
jsVar('branchGroups', $branchGroups);
jsVar('isWaterfall', isset($project) && ($project->model == 'waterfall' || $project->model == 'waterfallplus'));
jsVar('executionAttr', $execution->attribute);
jsVar('window.lastProjectID', $execution->project);

formGridPanel
(
    on::change('[name=begin]', 'computeWorkDays(NaN)'),
    on::change('[name=end]', 'computeWorkDays(NaN)'),
    set::fullModeOrders('project', !empty($config->setCode) ? 'type,name,code' : 'method,name,type', 'planDate,days,productsBox,PO,QD,PM,RD,teamMembers,desc,acl'),
    set::title($lang->execution->edit),
    set::modeSwitcher(false),
    set::defaultMode('full'),
    set::fields($fields)
);
