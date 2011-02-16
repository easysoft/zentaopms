<?php
/**
 * The bug view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='g'><div class='u-1'>
  <div id='featurebar'>
    <div class='f-left'>
      <?php
      echo "<span id='assigntomeTab'>"    . html::a(inlink('bug', "type=assigntome"),    $lang->bug->assignToMe)    . "</span>";
      echo "<span id='openedbymeTab'>"    . html::a(inlink('bug', "type=openedbyme"),    $lang->bug->openedByMe)    . "</span>";
      echo "<span id='resolvedbymeTab'>"  . html::a(inlink('bug', "type=resolvedbyme"),  $lang->bug->resolvedByMe)  . "</span>";
      echo "<span id='closedByMeTab'>"    . html::a(inlink('bug', "type=closedbyme"),    $lang->bug->closedByMe)    . "</span>";
      ?>
    </div>
  </div>
  <?php $vars = "type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
  <table class='table-1 fixed tablesorter'>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-severity'><?php echo $lang->bug->severityAB;?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th class='w-type'><?php echo $lang->typeAB;?></th>
      <th><?php echo $lang->bug->title;?></th>
      <th class='w-user'><?php echo $lang->openedByAB;?></th>
      <th class='w-user'><?php echo $lang->bug->resolvedByAB;?></th>
      <th class='w-resolution'><?php echo $lang->bug->resolutionAB;?></th>
      <th class='w-130px {sorter:false}'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($bugs as $bug):?>
    <tr class='a-center'>
      <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id, '_blank');?></td>
      <td><?php echo $lang->bug->severityList[$bug->severity]?></td>
      <td><?php echo $lang->bug->priList[$bug->pri]?></td>
      <td><?php echo $lang->bug->typeList[$bug->type]?></td>
      <td class='a-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
      <td><?php echo $users[$bug->openedBy];?></td>
      <td><?php echo $users[$bug->resolvedBy];?></td>
      <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
      <td>
        <?php
        $params = "bugID=$bug->id";
        if(!($bug->status == 'active'   and common::printLink('bug', 'resolve', $params, $lang->bug->buttonResolve))) echo $lang->bug->buttonResolve . ' ';
        if(!($bug->status == 'resolved' and common::printLink('bug', 'close', $params, $lang->bug->buttonClose)))     echo $lang->bug->buttonClose . ' ';
        common::printLink('bug', 'edit', $params, $lang->bug->buttonEdit);
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <?php $pager->show();?>
</div>
<script language='javascript'>$("#<?php echo $type;?>Tab").addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
