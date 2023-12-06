<?php
/**
 * The risk view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: risk.html.php 4771 2021-01-13 14:18:02Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<div id='mainContent'>
  <nav id='contentNav'>
    <ul class='nav nav-default'>
      <?php
      $that   = zget($lang->user->thirdPerson, $user->gender);
      $active = $type == 'assignedTo' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('issue', "userID={$user->id}&type=assignedTo"), sprintf($lang->user->assignedTo, $that)) . "</li>";

      $active = $type == 'createdBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('issue', "userID={$user->id}&type=createdBy"),   sprintf($lang->user->openedBy, $that))   . "</li>";

      $active = $type == 'closedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('issue', "userID={$user->id}&type=closedBy"),   sprintf($lang->user->closedBy, $that)) . "</li>";
      ?>
    </ul>
  </nav>

  <div class='main-table'>
    <table class="table has-sort-head table-fixed" id='issuetable'>
      <?php $vars = "userID={$user->id}&type=$type&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
      <thead>
        <tr>
          <th class="c-id w-50px"><?php echo common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
          <th class="w-80px"><?php echo $lang->issue->type;?></th>
          <th style="width:auto"><?php echo $lang->issue->title;?></th>
          <th class="w-70px"><?php echo $lang->issue->severity;?></th>
          <th class="w-60px"><?php echo $lang->issue->pri;?></th>
          <th class="w-80px"><?php echo $lang->issue->owner;?></th>
          <th class="w-70px"><?php echo $lang->issue->status;?></th>
          <th class="w-100px"><?php echo $lang->issue->createdDate;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($issues as $issue):?>
        <tr>
          <td class="c-id"><?php printf('%03d', $issue->id);?></td>
          <td title="<?php echo zget($lang->issue->typeList, $issue->type);?>"><?php echo zget($lang->issue->typeList, $issue->type);?></td>
          <td class="text-ellipsis" title="<?php echo $issue->title;?>"><?php echo html::a($this->createLink('issue', 'view', "id=$issue->id", '', '', $issue->project), $issue->title, '', "data-group='project'");?></td>
          <td class='severity-issue severity-<?php echo $issue->severity;?>' title="<?php echo zget($lang->issue->severityList, $issue->severity);?>"><?php echo zget($lang->issue->severityList, $issue->severity);?></td>
          <td title="<?php echo $issue->pri;?>" class="c-pri text-center"><span class="label-pri <?php echo 'label-pri-' . $issue->pri;?>"><?php echo $issue->pri;?></span></td>
          <td title="<?php echo zget($users, $issue->owner);?>"><?php echo zget($users, $issue->owner);?></td>
          <td class="status-issue status-<?php echo $issue->status;?>" title="<?php echo zget($lang->issue->statusList, $issue->status);?>"><?php echo zget($lang->issue->statusList, $issue->status);?></td>
          <?php $issue->createdDate = substr($issue->createdDate, 0, 10)?>
          <td title="<?php echo $issue->createdDate;?>"><?php echo $issue->createdDate;?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($issues):?>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
