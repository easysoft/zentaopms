<?php
/**
 * The bug view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: bug.html.php 4771 2013-05-05 07:41:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
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
      echo "<li class='$active'>" . html::a(inlink('bug', "account=$account&type=assignedTo"), sprintf($lang->user->assignedTo, $that)) . "</li>";

      $active = $type == 'openedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('bug', "account=$account&type=openedBy"),   sprintf($lang->user->openedBy, $that))   . "</li>";

      $active = $type == 'resolvedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('bug', "account=$account&type=resolvedBy"), sprintf($lang->user->resolvedBy, $that)) . "</li>";

      $active = $type == 'closedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('bug', "account=$account&type=closedBy"),   sprintf($lang->user->closedBy, $that)) . "</li>";
      ?>
    </ul>
  </nav>

  <div class='main-table'>
    <table class='table has-sort-head tablesorter'>
      <thead>
        <tr class='text-center'>
          <th class='w-id'><?php echo $lang->idAB;?></th>
          <th class='w-severity'><?php echo $lang->bug->severityAB;?></th>
          <th class='w-pri'><?php echo $lang->priAB;?></th>
          <th class='w-type'><?php echo $lang->typeAB;?></th>
          <th class='text-left'><?php echo $lang->bug->title;?></th>
          <th class='w-90px'><?php echo $lang->openedByAB;?></th>
          <th class='w-100px'><?php echo $lang->bug->resolvedBy;?></th>
          <th class='w-100px'><?php echo $lang->bug->resolutionAB;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($bugs as $bug):?>
        <tr class='text-center'>
          <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id, '_blank');?></td>
          <td><span class='<?php echo 'severity' . zget($lang->bug->severityList, $bug->severity, $bug->severity)?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity)?></span></td>
          <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug->pri, $bug->pri)?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
          <td><?php echo $lang->bug->typeList[$bug->type]?></td>
          <td class='text-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
          <td><?php echo zget($users, $bug->openedBy);?></td>
          <td><?php echo zget($users, $bug->resolvedBy);?></td>
          <td><?php echo zget($lang->bug->resolutionList, $bug->resolution);?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($bugs):?>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
