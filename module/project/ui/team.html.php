<?php
declare(strict_types=1);
/**
 * The team view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

foreach($teamMembers as $member)
{
    $member->days    = $member->days . $this->lang->execution->day;
    $member->hours   = $member->hours . $this->lang->execution->workHour;
    $member->total   = $member->totalHours . $this->lang->execution->workHour;
    $member->actions = array();
    if(common::hasPriv('project', 'unlinkMember', $member) && $canBeChanged) $member->actions = array('unlink');
}

/* zin: Define the set::module('team') feature bar on main menu. */
featureBar
(
    set::current('all'),
    set::linkParams("projectID={$projectID}")
);

/* zin: Define the toolbar on main menu. */
$isTutorialMode    = commonModel::isTutorialMode();
$isLimitUser       = empty($app->user->admin) && !empty($app->user->rights['rights']['my']['limited']);
$canManageMembers  = $isTutorialMode ? $canBeChanged : hasPriv('project', 'manageMembers') && $canBeChanged && !$isLimitUser;
$wizardParams      = helper::safe64Encode("projectID={$projectID}");
$manageMembersLink = !$isTutorialMode ? helper::createLink('project', 'manageMembers', "projectID={$projectID}") : helper::createLink('tutorial', 'wizard', "module=project&method=manageMembers&params={$wizardParams}");
if($canManageMembers) $manageMembersItem = array('icon' => 'persons', 'class' => 'primary', 'text' => $lang->project->manageMembers, 'url' => $manageMembersLink);

toolbar
(
    $canManageMembers ? item(set($manageMembersItem)) : null
);

jsVar('confirmUnlinkMember', $lang->project->confirmUnlinkMember);
jsVar('pageSummary', $lang->team->totalHours . '：' .  "<strong>%totalHours%{$lang->execution->workHour}" . sprintf($lang->project->teamMembersCount, count($teamMembers)) . "</strong>");
jsVar('deptUsers', $deptUsers);
jsVar('noAccess', $lang->user->error->noAccess);

dtable
(
    set::cols($config->project->dtable->team->fieldList),
    set::data($teamMembers),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footer(jsRaw('function(){return window.setStatistics.call(this);}'))
);

/* ====== Render page ====== */
render();
