<?php
/**
 * The bug view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <div class='f-left'>
    <?php
    echo "<span id='assigntomeTab'>"    . html::a(inlink('bug', "type=assigntome"),    $lang->bug->assignToMe)    . "</span>";
    echo "<span id='openedbymeTab'>"    . html::a(inlink('bug', "type=openedbyme"),    $lang->bug->openedByMe)    . "</span>";
    echo "<span id='resolvedbymeTab'>"  . html::a(inlink('bug', "type=resolvedbyme"),  $lang->bug->resolvedByMe)  . "</span>";
    echo "<span id='closedbymeTab'>"    . html::a(inlink('bug', "type=closedbyme"),    $lang->bug->closedByMe)    . "</span>";
    ?>
  </div>
</div>
<?php $vars = "type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
<table class='table-1 fixed tablesorter'>
  <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
  <thead>
  <tr class='colhead'>
    <th class='w-id'>        <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
    <th class='w-severity'>  <?php common::printOrderLink('severity', $orderBy, $vars, $lang->bug->severityAB);?></th>
    <th class='w-pri'>       <?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
    <th class='w-type'>      <?php common::printOrderLink('type', $orderBy, $vars, $lang->typeAB);?></th>
    <th>                     <?php common::printOrderLink('title', $orderBy, $vars, $lang->bug->title);?></th>
    <th class='w-user'>      <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
    <th class='w-user'>      <?php common::printOrderLink('resolvedBy', $orderBy, $vars, $lang->bug->resolvedByAB);?></th>
    <th class='w-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars,  $lang->bug->resolutionAB);?></th>
    <th class='w-80px'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($bugs as $bug):?>
  <tr class='a-center'>
    <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id, '_blank');?></td>
    <td><span class='<?php echo 'severity' . $lang->bug->severityList[$bug->severity]?>'><?php echo isset($lang->bug->severityList[$bug->severity]) ? $lang->bug->severityList[$bug->severity] : $bug->severity;?></span></td>
    <td><span class='<?php echo 'pri' . $lang->bug->priList[$bug->pri]?>'><?php echo isset($lang->bug->priList[$bug->pri]) ? $lang->bug->priList[$bug->pri] : $bug->pri?></span></td>
    <td><?php echo $lang->bug->typeList[$bug->type]?></td>
    <td class='a-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
    <td><?php echo $users[$bug->openedBy];?></td>
    <td><?php echo $users[$bug->resolvedBy];?></td>
    <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
    <td class='a-right'>
      <?php
      $params = "bugID=$bug->id";
      common::printIcon('bug', 'resolve', $params, $bug, 'list');
      common::printIcon('bug', 'close', $params, $bug, 'list');
      common::printIcon('bug', 'edit', $params, '', 'list');
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='9'><?php $pager->show();?></td></tr></tfoot>
</table>
<script language='javascript'>$("#<?php echo $type;?>Tab").addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
