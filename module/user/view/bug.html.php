<?php
/**
 * The bug view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
      echo "<li class='$active'>" . html::a(inlink('bug', "userID={$user->id}&type=assignedTo"), sprintf($lang->user->assignedTo, $that)) . "</li>";

      $active = $type == 'openedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('bug', "userID={$user->id}&type=openedBy"),   sprintf($lang->user->openedBy, $that))   . "</li>";

      $active = $type == 'resolvedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('bug', "userID={$user->id}&type=resolvedBy"), sprintf($lang->user->resolvedBy, $that)) . "</li>";

      $active = $type == 'closedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('bug', "userID={$user->id}&type=closedBy"),   sprintf($lang->user->closedBy, $that)) . "</li>";
      ?>
    </ul>
  </nav>

  <div class='main-table'>
    <table class='table has-sort-head'>
      <?php $vars = "userID={$user->id}&type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
      <thead>
        <tr class='text-center'>
          <th class='c-id'>        <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
          <th class='text-left'>   <?php common::printOrderLink('title', $orderBy, $vars, $lang->bug->title);?></th>
          <th class='c-severity' title=<?php echo $lang->bug->severity;?>><?php common::printOrderLink('severity', $orderBy, $vars, $lang->bug->abbr->severity);?></th>
          <th class='c-pri' title='<?php echo $lang->pri;?>'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
          <th class='c-type'>      <?php common::printOrderLink('type', $orderBy, $vars, $lang->bug->type);?></th>
          <th class='c-user'>      <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='c-user'>      <?php common::printOrderLink('resolvedBy', $orderBy, $vars, $lang->bug->resolvedBy);?></th>
          <th class='c-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->abbr->resolution);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($bugs as $bug):?>
        <tr class='text-center'>
          <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bug->id, '', "class='iframe'");?></td>
          <td class='text-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bug->title, '', "class='iframe'");?></td>
          <td class='c-severity'><span class='label-severity <?php echo 'severity' . zget($lang->bug->severityList, $bug->severity, $bug->severity)?>' data-severity='<?php echo $bug->severity;?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity)?></span></td>
          <td class='c-pri'><span class='label-pri <?php echo 'label-pri-' . zget($lang->bug->priList, $bug->pri, $bug->pri)?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
          <td><?php echo zget($lang->bug->typeList, $bug->type, '');?></td>
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
