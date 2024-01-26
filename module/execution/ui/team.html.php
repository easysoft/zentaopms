<?php
declare(strict_types=1);
/**
 * The team view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Define the set::module('team') feature bar on main menu. */
featureBar
(
    set::current('all'),
    set::linkParams("executionID={$execution->id}")
);

/* zin: Define the toolbar on main menu. */
$isTutorialMode    = commonModel::isTutorialMode();
$isLimitUser       = empty($app->user->admin) && !empty($app->user->rights['rights']['my']['limited']);
$canManageMembers  = $isTutorialMode ? $canBeChanged : hasPriv('execution', 'manageMembers') && $canBeChanged && !$isLimitUser;
$wizardParams      = helper::safe64Encode("executionID={$execution->id}");
$manageMembersLink = !$isTutorialMode ? helper::createLink('execution', 'manageMembers', "executionID={$execution->id}") : helper::createLink('tutorial', 'wizard', "module=execution&method=manageMembers&params={$wizardParams}");
if($canManageMembers) $manageMembersItem = array('icon' => 'persons', 'class' => 'primary', 'text' => $lang->execution->manageMembers, 'url' => $manageMembersLink);

toolbar
(
    $canManageMembers ? item(set($manageMembersItem)) : null,
);

jsVar('confirmUnlinkMember', $lang->execution->confirmUnlinkMember);
jsVar('pageSummary', $lang->team->totalHours . '：' .  "<strong>%totalHours%{$lang->execution->workHour}" . sprintf($lang->project->teamMembersCount, count($teamMembers)) . "</strong>");
jsVar('deptUsers', $deptUsers);
jsVar('noAccess', $lang->user->error->noAccess);

dtable
(
    set::cols($config->execution->team->dtable->fieldList),
    set::data($teamMembers),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footer(jsRaw('function(){return window.setTeamStatistics.call(this);}'))
);

/* ====== Render page ====== */
render();
