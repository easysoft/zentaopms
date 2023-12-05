<?php
/**
 * The team view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie
 * @package     project
 * @version     $Id: team.html.php 4143 2021-08-11 11:01:06Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkMember', $lang->project->confirmUnlinkMember)?>
<?php js::set('noAccess', $lang->user->error->noAccess)?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'>
      <span class='text'><?php echo $lang->project->teamMember;?></span>
      <span class="label label-light label-badge"><?php echo count($teamMembers);?></span>
    </span>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    if($canBeChanged)
    {
        if(commonModel::isTutorialMode())
        {
            $wizardParams = helper::safe64Encode("projectID=$projectID");
            echo html::a($this->createLink('tutorial', 'wizard', "module=project&method=manageMembers&params=$wizardParams"), "<i class='icon icon-persons'></i> " . $lang->project->manageMembers, '', "class='btn btn-primary manage-team-btn'");
        }
        else
        {
            if(!empty($app->user->admin) or empty($app->user->rights['rights']['my']['limited'])) common::printLink('project', 'manageMembers', "projectID=$projectID", "<i class='icon icon-persons'></i> " . $lang->project->manageMembers, '', "class='btn btn-primary manage-team-btn'");
        }
    }
    ?>
  </div>
</div>
<div id='mainContent'>
  <?php if(empty($teamMembers)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->execution->noMembers;?></span>
      <?php if((!empty($app->user->admin) or empty($app->user->rights['rights']['my']['limited'])) && common::hasPriv('project', 'manageMembers')):?>
      <?php echo html::a($this->createLink('project', 'manageMembers', "projectID=$projectID"), "<i class='icon icon-persons'></i> " . $lang->project->manageMembers, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table'>
    <table class='table' id='memberList'>
      <thead>
        <tr>
          <th><?php echo $lang->team->account;?></th>
          <th><?php echo $lang->team->role;?></th>
          <th><?php echo $lang->team->join;?></th>
          <th><?php echo $lang->team->days;?></th>
          <th><?php echo $lang->team->hours;?></th>
          <th><?php echo $lang->team->totalHours;?></th>
          <th class='c-limited text-center'><?php echo $lang->team->limited;?></th>
          <?php if($canBeChanged):?>
          <th class='c-actions-1 text-center'><?php echo $lang->actions;?></th>
          <?php endif;?>
        </tr>
      </thead>
      <tbody>
        <?php $totalHours = 0;?>
        <?php foreach($teamMembers as $member):?>
        <tr>
          <td>
          <?php
          if(common::hasPriv('user', 'view') and common::hasPriv('my', 'team'))
          {
              $link = isset($deptUsers[$member->userID]) ? $this->createLink('user', 'view', "userID={$member->userID}") : "javascript: alert(noAccess);";
              echo html::a($link, $member->realname, '', 'data-app="system"');
          }
          else
          {
              echo $member->realname;
          }
          $memberHours = $member->days * $member->hours;
          $totalHours += $memberHours;
          ?>
          </td>
          <td title='<?php echo $member->role;?>'><?php echo $member->role;?></td>
          <td><?php echo $member->join;?></td>
          <td><?php echo $member->days . $lang->execution->day;?></td>
          <td><?php echo $member->hours . $lang->execution->workHour;?></td>
          <td><?php echo $memberHours . $lang->execution->workHour;?></td>
          <td class="text-center"><?php echo $lang->team->limitedList[$member->limited];?></td>
          <?php if($canBeChanged):?>
          <td class='c-actions text-center'>
            <?php
            if (common::hasPriv('project', 'unlinkMember', $member))
            {
                echo html::a("javascript:deleteMemeber($projectID, \"{$member->account}\", \"{$member->userID}\")", '<i class="icon-green-project-unlinkMember icon-unlink"></i>', '', "class='btn' title='{$lang->project->unlinkMember}'");
            }
            ?>
          </td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class='table-statistic'><?php echo $lang->team->totalHours . '：' .  "<strong>$totalHours{$lang->execution->workHour}" . sprintf($lang->project->teamMembersCount, count($teamMembers)) . "</strong>";?></div>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
