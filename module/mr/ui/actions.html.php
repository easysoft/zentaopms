<?php
declare(strict_types=1);
/**
 * The actions view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$hasNoChange       = $MR->synced && empty($rawMR->changes_count) ? true : false;
$hasConflict       = $MR->synced === '1' ? $rawMR->has_conflicts : !$MR->hasNoConflict;
$mergeStatus       = !empty($rawMR->merge_status) ? $rawMR->merge_status : $MR->mergeStatus;
$compileNotSuccess = !empty($compile->id) && $compile->status != 'success';

$mainActions   = array();
$suffixActions = array();
foreach($config->mr->view->operateList as $operate)
{
    if(!common::hasPriv($app->rawModule, $operate == 'reject' ? 'approval' : $operate)) continue;
    if($operate == 'reopen' && (!$MR->synced || $rawMR->state != 'closed')) continue;
    if(in_array($rawMR->state, array('closed', 'merged')) && $operate == 'accept') continue;

    $action = $config->mr->actionList[$operate];
    if($operate === 'edit' || $operate === 'delete')
    {
        $suffixActions[] = $action;
        continue;
    }

    if($operate == 'accept' && ($MR->approvalStatus != 'approved' || $compileNotSuccess))
    {
        $action['disabled'] = true;
        $action['hint']     = $lang->mr->acceptTip;
    }
    if($operate == 'accept' && (!$MR->synced || $rawMR->state != 'opened' || $hasConflict))
    {
        $action['disabled'] = true;
        $action['hint']     = $lang->mr->conflictsTip;
    }

    if(in_array($operate, array('approval', 'reject', 'close', 'edit')))
    {
        if(!$MR->synced || $rawMR->state != 'opened') continue;
        if($operate == 'reject' && $MR->approvalStatus == 'rejected') $action['disabled'] = true;

        if($operate == 'approval')
        {
            if($hasNoChange || $hasConflict || $compileNotSuccess || $MR->approvalStatus == 'approved' || $mergeStatus == 'cannot_be_merged')
            {
                $action['disabled'] = true;
                if($compileNotSuccess) $action['hint'] = $lang->mr->compileTip;
                if($hasNoChange)       $action['hint'] = $lang->mr->noChangesTip;
                if($hasConflict)       $action['hint'] = $lang->mr->conflictsTip;

                if(in_array(strtolower($repo->SCM), array('gogs', 'gitea'))) $action['hint'] = $lang->mr->notifyTip;
            }
        }
    }

    if($operate == 'delete' && !$projectOwner && !$this->app->user->admin) $action['disabled'] = true;
    if($operate == 'edit' && !$projectEdit && !$this->app->user->admin) $action['disabled'] = true;

    $mainActions[] = $action;
}

div
(
    setClass('detail-actions center sticky mt-4 bottom-4 z-10'),
    floatToolbar
    (
        set::object($MR),
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($mainActions),
        set::suffix($suffixActions)
    )
);
