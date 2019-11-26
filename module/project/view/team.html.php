<?php
/**
 * The team view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: team.html.php 4143 2013-01-18 07:01:06Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkMember', $lang->project->confirmUnlinkMember)?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->project->team;?></span></span>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    if(commonModel::isTutorialMode())
    {
        $wizardParams = helper::safe64Encode("projectID=$project->id");
        echo html::a($this->createLink('tutorial', 'wizard', "module=project&method=manageMembers&params=$wizardParams"), "<i class='icon icon-persons'></i> " . $lang->project->manageMembers, '', "class='btn btn-primary manage-team-btn'");
    }
    else
    {
        if(!empty($app->user->admin) or empty($app->user->rights['rights']['my']['limited'])) common::printLink('project', 'manageMembers', "projectID=$project->id", "<i class='icon icon-persons'></i> " . $lang->project->manageMembers, '', "class='btn btn-primary manage-team-btn'");
    }
    ?>
  </div>
</div>
<div id='mainContent'>
  <?php if(empty($teamMembers)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->project->noMembers;?></span>
      <?php if((!empty($app->user->admin) or empty($app->user->rights['rights']['my']['limited'])) && common::hasPriv('project', 'manageMembers')):?>
      <?php echo html::a($this->createLink('project', 'manageMembers', "projectID=$project->id"), "<i class='icon icon-persons'></i> " . $lang->project->manageMembers, '', "class='btn btn-info'");?>
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
          <th class='w-100px text-center'><?php echo $lang->team->limited;?></th>
          <th class='c-actions-1 w-80px text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $totalHours = 0;?>
        <?php foreach($teamMembers as $member):?>
        <tr>
          <td>
          <?php
          if(!common::printLink('user', 'view', "account=$member->account", $member->realname)) print $member->realname;
          $memberHours = $member->days * $member->hours;
          $totalHours  += $memberHours;
          ?>
          </td>
          <td title='<?php echo $member->role;?>'><?php echo $member->role;?></td>
          <td><?php echo $member->join;?></td>
          <td><?php echo $member->days . $lang->project->day;?></td>
          <td><?php echo $member->hours . $lang->project->workHour;?></td>
          <td><?php echo $memberHours . $lang->project->workHour;?></td>
          <td class="text-center"><?php echo $lang->team->limitedList[$member->limited];?></td>
          <td class='c-actions text-center'>
            <?php
            if (common::hasPriv('project', 'unlinkMember', $member))
            {
                $unlinkURL = $this->createLink('project', 'unlinkMember', "projectID=$project->id&account=$member->account&confirm=yes");
                echo html::a("javascript:ajaxDelete(\"$unlinkURL\", \"mainContent\", confirmUnlinkMember)", '<i class="icon-green-project-unlinkMember icon-unlink"></i>', '', "class='btn' title='{$lang->project->unlinkMember}'");
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class='table-statistic'><?php echo $lang->team->totalHours . '：' .  "<strong>$totalHours{$lang->project->workHour}</strong>";?></div>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
