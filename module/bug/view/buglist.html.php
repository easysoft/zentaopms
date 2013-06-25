<?php $_GET['onlybody'] = 'no';?>
<table class='table-1 fixed colored tablesorter datatable' id='bugList'>
  <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
  <thead>
  <tr class='colhead'>
    <th class='w-id'>       <?php common::printOrderLink('id',          $orderBy, $vars, $lang->idAB);?></th>
    <th class='w-severity'> <?php common::printOrderLink('severity',    $orderBy, $vars, $lang->bug->severityAB);?></th>
    <th class='w-pri'>      <?php common::printOrderLink('pri',         $orderBy, $vars, $lang->priAB);?></th>

    <th>                    <?php common::printOrderLink('title',       $orderBy, $vars, $lang->bug->title);?></th>

    <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
    <th class='w-80px'><?php common::printOrderLink('status',           $orderBy, $vars, $lang->bug->statusAB);?></th>
    <?php endif;?>

    <?php if($browseType == 'needconfirm'):?>
    <th class='w-200px'><?php common::printOrderLink('story',           $orderBy, $vars, $lang->bug->story);?></th>
    <th class='w-50px'><?php echo $lang->actions;?></th>
    <?php else:?>
    <th class='w-user'><?php common::printOrderLink('openedBy',         $orderBy, $vars, $lang->openedByAB);?></th>

    <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
    <th class='w-date'><?php common::printOrderLink('openedDate',       $orderBy, $vars, $lang->bug->openedDateAB);?></th>
    <?php endif;?>

    <th class='w-user'><?php common::printOrderLink('assignedTo',       $orderBy, $vars, $lang->assignedToAB);?></th>
    <th class='w-user'><?php common::printOrderLink('resolvedBy',       $orderBy, $vars, $lang->bug->resolvedByAB);?></th>
    <th class='w-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolutionAB);?></th>

    <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
    <th class='w-date'><?php common::printOrderLink('resolvedDate',     $orderBy, $vars, $lang->bug->resolvedDateAB);?></th>
    <?php endif;?>

    <th class='w-140px {sorter:false}'><?php echo $lang->actions;?></th>
    <?php endif;?>
  </tr>
  </thead>
  <tbody>
  <?php foreach($bugs as $bug):?>
  <?php $bugLink = inlink('view', "bugID=$bug->id");?>
  <tr class='a-center'>
    <td class='<?php echo $bug->status;?>' style="font-weight:bold">
      <input type='checkbox' name='bugIDList[]'  value='<?php echo $bug->id;?>'/> 
      <?php echo html::a($bugLink, sprintf('%03d', $bug->id));?>
    </td>
    <td><span class='<?php echo 'severity' . $bug->severity;?>'><?php echo $bug->severity;?></span></td>
    <td><span class='<?php echo 'pri' . $lang->bug->priList[$bug->pri];?>'><?php echo $lang->bug->priList[$bug->pri];?></span></td>

    <?php $class = 'confirm' . $bug->confirmed;?>
    <td class='a-left' title="<?php echo $bug->title?>"><?php echo "<span class='$class'>[{$lang->bug->confirmedList[$bug->confirmed]}] </span>" . html::a($bugLink, $bug->title);?></td>

    <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
    <td><?php echo $lang->bug->statusList[$bug->status];?></td>
    <?php endif;?>

    <?php if($browseType == 'needconfirm'):?>
    <td class='a-left' title="<?php echo $bug->storyTitle?>"><?php echo html::a($this->createLink('story', 'view', "stoyID=$bug->story"), $bug->storyTitle, '_blank');?></td>
    <td><?php $lang->bug->confirmStoryChange = $lang->confirm; common::printIcon('bug', 'confirmStoryChange', "bugID=$bug->id", '', 'list', '', 'hiddenwin')?></td>
    <?php else:?>
    <td><?php echo $users[$bug->openedBy];?></td>

    <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
    <td><?php echo substr($bug->openedDate, 5, 11)?></td>
    <?php endif;?>

    <td <?php if($bug->assignedTo == $this->app->user->account) echo 'class="red"';?>><?php echo $users[$bug->assignedTo];?></td>
    <td><?php echo $users[$bug->resolvedBy];?></td>
    <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>

    <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
    <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
    <?php endif;?>

    <td class='a-right'>
      <?php
      $params = "bugID=$bug->id";
      common::printIcon('bug', 'confirmBug', $params, $bug, 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'assignTo',   $params, '',   'list', '', '', 'iframe', true);
      common::printIcon('bug', 'resolve',    $params, $bug, 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'close',      $params, $bug, 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'edit',       $params, $bug, 'list');
      common::printIcon('bug', 'create',     "product=$bug->product&extra=bugID=$bug->id", $bug, 'list', 'copy');
      ?>
    </td>
    <?php endif;?>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <?php
      $columns = $this->cookie->windowWidth >= $this->config->wideSize ? 12 : 9;
      if($browseType == 'needconfirm') $columns = $this->cookie->windowWidth >= $this->config->wideSize ? 7 : 6; 
      ?>
      <td colspan='<?php echo $columns;?>'>
        <?php if(!empty($bugs)):?>
        <div class='f-left'>
          <?php 
          echo html::selectAll() . html::selectReverse(); 
          if(common::hasPriv('bug', 'batchEdit') and $bugs) echo html::submitButton($lang->edit);
         ?>
        </div>
        <?php endif?>
        <div class='f-right'><?php $pager->show();?></div>
      </td>
    </tr>
  </tfoot>
</table>
