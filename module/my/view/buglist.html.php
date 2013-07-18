<table class='table-1 fixed tablesorter colored' id='bugList'>
  <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
  <thead>
  <tr class='colhead'>
    <th class='w-id'>        <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
    <th class='w-severity'>  <?php common::printOrderLink('severity',   $orderBy, $vars, $lang->bug->severityAB);?></th>
    <th class='w-pri'>       <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
    <th class='w-type'>      <?php common::printOrderLink('type',       $orderBy, $vars, $lang->typeAB);?></th>
    <th>                     <?php common::printOrderLink('title',      $orderBy, $vars, $lang->bug->title);?></th>
    <th class='w-user'>      <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
    <th class='w-user'>      <?php common::printOrderLink('resolvedBy', $orderBy, $vars, $lang->bug->resolvedByAB);?></th>
    <th class='w-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolutionAB);?></th>
    <th class='w-140px'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php $canBatchEdit  = common::hasPriv('bug', 'batchEdit');?>
  <?php foreach($bugs as $bug):?>
  <tr class='a-center'>
    <td class='a-left'>
      <?php if($canBatchEdit):?><input type='checkbox' name='bugIDList[]' value='<?php echo $bug->id;?>' /><?php endif;?>
      <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), sprintf('%03d', $bug->id), '_blank');?>
    </td>
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
      common::printIcon('bug', 'confirmBug', $params, $bug, 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'assignTo',   $params, '', 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'resolve',    $params, $bug, 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'close',      $params, $bug, 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'edit',       $params, '', 'list');
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr><td colspan='9'>
    <?php if($bugs and $canBatchEdit):?>
    <div class='f-left'>
    <?php echo html::selectAll() . html::selectReverse() . html::submitButton($lang->edit);?>
    </div>
    <?php endif;?>
    <?php $pager->show();?>
    </td></tr>
  </tfoot>
</table>
