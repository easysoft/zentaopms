<?php
/**
 * The risk view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: risk.html.php 4771 2021-01-13 14:18:02Z $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<style>
.pri-low {color: #000000;}
.pri-middle {color: #FF9900;}
.pri-high {color: #E53333;}
</style>
<div id='mainContent'>
  <nav id='contentNav'>
    <ul class='nav nav-default'>
      <?php
      $that   = zget($lang->user->thirdPerson, $user->gender);
      $active = $type == 'assignedTo' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('risk', "userID={$user->id}&fromModule=$fromModule&type=assignedTo"), sprintf($lang->user->assignedTo, $that)) . "</li>";

      $active = $type == 'createdBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('risk', "userID={$user->id}&fromModule=$fromModule&type=createdBy"),   sprintf($lang->user->openedBy, $that))   . "</li>";

      $active = $type == 'resolvedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('risk', "userID={$user->id}&fromModule=$fromModule&type=resolvedBy"), sprintf($lang->user->resolvedBy, $that)) . "</li>";

      $active = $type == 'closedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('risk', "userID={$user->id}&fromModule=$fromModule&type=closedBy"),   sprintf($lang->user->closedBy, $that)) . "</li>";
      ?>
    </ul>
  </nav>

  <div class='main-table'>
    <table class="table has-sort-head table-fixed" id='risktable'>
      <?php $vars = "userID={$user->id}&fromModule=$fromModule&type=$type&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
      <thead>
        <tr>
		  <th class='text-left w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->risk->id);?></th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->risk->name);?></th>
          <th class='w-80px'><?php common::printOrderLink('strategy', $orderBy, $vars, $lang->risk->strategy);?></th>
          <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->risk->status);?></th>
          <th class='w-120px'><?php common::printOrderLink('identifiedDate', $orderBy, $vars, $lang->risk->identifiedDate);?></th>
          <th class='w-80px'><?php common::printOrderLink('rate', $orderBy, $vars, $lang->risk->rate);?></th>
          <th class='w-80px'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->risk->pri);?></th>
          <th class='w-120px'><?php common::printOrderLink('category', $orderBy, $vars, $lang->risk->category);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($risks as $risk):?>
		<tr>
          <td><?php echo $risk->id;?></td>
          <td><?php echo html::a($this->createLink('risk', 'view', "riskID=$risk->id"), $risk->name, '', "data-group='project'");?></td>
          <td><?php echo zget($lang->risk->strategyList, $risk->strategy);?></td>
          <td><?php echo zget($lang->risk->statusList, $risk->status);?></td>
          <td><?php echo $risk->identifiedDate == '0000-00-00' ? '' : $risk->identifiedDate;?></td>
          <td><?php echo $risk->rate;?></td>
          <?php
          $priColor = 'pri-low';
          if($risk->pri == 'middle') $priColor = 'pri-middle';
          if($risk->pri == 'high')   $priColor = 'pri-high';
          ?>
          <td><?php echo "<span class='$priColor'>" . zget($lang->risk->priList, $risk->pri) . "</span>";?></td>
          <td><?php echo zget($lang->risk->categoryList, $risk->category);?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($risks):?>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
