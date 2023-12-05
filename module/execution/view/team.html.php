<?php
/**
 * The team view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: team.html.php 4143 2013-01-18 07:01:06Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkMember', $lang->execution->confirmUnlinkMember)?>
<?php js::set('noAccess', $lang->user->error->noAccess)?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'>
      <span class='text'><?php echo $lang->execution->team;?></span>
      <span class="label label-light label-badge"><?php echo count($teamMembers);?></span>
    </span>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    if($canBeChanged)
    {
        if(commonModel::isTutorialMode())
        {
            $wizardParams = helper::safe64Encode("executionID=$execution->id");
            echo html::a($this->createLink('tutorial', 'wizard', "module=execution&method=manageMembers&params=$wizardParams"), "<i class='icon icon-persons'></i> " . $lang->execution->manageMembers, '', "class='btn btn-primary manage-team-btn'");
        }
        else
        {
            if(!empty($app->user->admin) or empty($app->user->rights['rights']['my']['limited'])) common::printLink('execution', 'manageMembers', "executionID=$execution->id", "<i class='icon icon-persons'></i> " . $lang->execution->manageMembers, '', "class='btn btn-primary manage-team-btn'");
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
      <?php if((!empty($app->user->admin) or empty($app->user->rights['rights']['my']['limited'])) && common::hasPriv('execution', 'manageMembers')):?>
      <?php echo html::a($this->createLink('execution', 'manageMembers', "executionID=$execution->id"), "<i class='icon icon-persons'></i> " . $lang->execution->manageMembers, '', "class='btn btn-info'");?>
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
          <th class='c-actions-2 text-center'><?php echo $lang->actions;?></th>
          <?php endif;?>
        </tr>
      </thead>
      <tbody>
        <?php $totalHours = 0;?>
        <?php foreach($teamMembers as $member):?>
        <tr>
          <td>
          <?php
          if(common::hasPriv('user', 'view'))
          {
              $link = isset($deptUsers[$member->userID]) ? $this->createLink('user', 'view', "userID={$member->userID}") : "javascript:checkUserDept();";
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
            if (common::hasPriv('execution', 'unlinkMember', $member))
            {
                echo html::a("javascript:deleteMember($execution->id, $member->userID)", '<i class="icon-green-execution-unlinkMember icon-unlink"></i>', '', "class='btn' title='{$lang->execution->unlinkMember}'");
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
