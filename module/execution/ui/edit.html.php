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

jsVar('confirmSync', $lang->execution->confirmSync);
jsVar('isWaterfall', isset($project) && ($project->model == 'waterfall' || $project->model == 'waterfallplus'));
jsVar('executionAttr', $execution->attribute);
jsVar('window.lastProjectID', $execution->project);
jsVar('weekend', $config->execution->weekend);

$confirmTip   = !empty($unclosedTasks) ? sprintf($this->lang->execution->confirmCloseExecution, implode($this->lang->comma, $unclosedTasks)) : '';
$beforeSubmit = jsRaw("() =>
{
    if($('[name=status]').val() != 'closed') return true;
    zui.Modal.confirm('{$confirmTip}').then((res) =>
    {
        if(res)
        {
            const formData = new FormData($('#zin_execution_edit_{$execution->id}_formGridPanel form')[0]);
            const confirmURL = $('#zin_execution_edit_{$execution->id}_formGridPanel form').attr('action');
            $.ajaxSubmit({url: confirmURL, data: formData});
        }
    });
    return false;
}");

formGridPanel
(
    on::change('[name=begin]', 'computeWorkDays(NaN)'),
    on::change('[name=end]', 'computeWorkDays(NaN)'),
    on::change('[name=project]', 'changeProject()'),
    set::formID('zin_execution_edit_' . $execution->id . '_formGridPanel'),
    !empty($unclosedTasks) ? set::ajax(array('beforeSubmit' => $beforeSubmit)) : null,
    set::fullModeOrders('project', !empty($config->setCode) ? 'lifetime,attribute,name,code,status' : 'lifetime,attribute,name,status', 'planDate,days,productsBox,PO,QD,PM,RD,teamMembers,desc,acl'),
    set::title($lang->execution->edit),
    set::modeSwitcher(false),
    set::defaultMode('full'),
    set::fields($fields)
);
