<?php
/**
 * The browse data view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable' id='bugList'>
      <thead>
      <tr>
        <th class='w-id'>       <?php common::printOrderLink('id',          $orderBy, $vars, $lang->idAB);?></th>
        <th class='w-severity'> <?php common::printOrderLink('severity',    $orderBy, $vars, $lang->bug->severityAB);?></th>
        <th class='w-pri'>      <?php common::printOrderLink('pri',         $orderBy, $vars, $lang->priAB);?></th>
        <th>                    <?php common::printOrderLink('title',       $orderBy, $vars, $lang->bug->title);?></th>
        <th class='w-100px'>    <?php common::printOrderLink('status',      $orderBy, $vars, $lang->bug->statusAB);?></th>
        <th class='w-80px'>     <?php common::printOrderLink('deadline',    $orderBy, $vars, $lang->bug->deadline);?></th>

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
      <?php if($bugs):?>
      <tbody>
      <?php foreach($bugs as $bug):?>
      <?php $bugLink = inlink('view', "bugID=$bug->id");?>
      <tr class='text-center'>
        <td class='cell-id bug-<?php echo $bug->status;?> strong text-left'>
          <input type='checkbox' name='bugIDList[]'  value='<?php echo $bug->id;?>'/> 
          <?php echo html::a($bugLink, sprintf('%03d', $bug->id));?>
        </td>
        <td><span class='<?php echo 'severity' . zget($lang->bug->severityList, $bug->severity, $bug->severity);?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?></span></td>
        <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug->pri, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri);?></span></td>

        <?php $class = 'confirm' . $bug->confirmed;?>
        <td class='text-left' title="<?php echo $bug->title?>">
          <?php
          echo "<span class='$class'>[{$lang->bug->confirmedList[$bug->confirmed]}]</span> ";
          if($bug->branch)echo "<span title='{$lang->product->branchName[$product->type]}' class='label label-branch label-badge'>{$branches[$bug->branch]}</span> ";
          if($modulePairs and $bug->module)echo "<span title='{$lang->bug->module}' class='label label-info label-badge'>{$modulePairs[$bug->module]}</span> ";
          echo html::a($bugLink, $bug->title, null, "style='color: $bug->color'");
          ?>
        </td>
        <td class='bug-<?php echo $bug->status?>'>
          <?php
          if($bug->needconfirm)
          {
              echo "(<span class='warning'>{$lang->story->changed}</span> ";
              echo html::a($this->createLink('bug', 'confirmStoryChange', "bugID=$bug->id"), $lang->confirm, 'hiddenwin');
              echo ")";
          }
          else
          {
              echo $lang->bug->statusList[$bug->status];
          }
          ?>
        </td>
        <td class="<?php if(isset($bug->delay)) echo 'delayed';?>"><?php if(substr($bug->deadline, 0, 4) > 0) echo substr($bug->deadline, 5, 6)?></td>
        <?php if($browseType == 'needconfirm'):?>
        <td class='text-left' title="<?php echo $bug->storyTitle?>"><?php echo html::a($this->createLink('story', 'view', "stoyID=$bug->story"), $bug->storyTitle, '_blank');?></td>
        <td><?php $lang->bug->confirmStoryChange = $lang->confirm; common::printIcon('bug', 'confirmStoryChange', "bugID=$bug->id", '', 'list', '', 'hiddenwin')?></td>
        <?php else:?>
        <td><?php echo zget($users, $bug->openedBy, $bug->openedBy);?></td>

        <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
        <td><?php echo substr($bug->openedDate, 5, 11)?></td>
        <?php endif;?>

        <td <?php if($bug->assignedTo == $this->app->user->account) echo 'class="red"';?>><?php echo zget($users, $bug->assignedTo, $bug->assignedTo);?></td>
        <td><?php echo zget($users, $bug->resolvedBy, $bug->resolvedBy)?></td>
        <td><?php echo zget($lang->bug->resolutionList, $bug->resolution);?></td>

        <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
        <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
        <?php endif;?>

        <td class='text-right'>
          <?php
          $params = "bugID=$bug->id";
          common::printIcon('bug', 'confirmBug', $params, $bug, 'list', 'search', '', 'iframe', true);
          common::printIcon('bug', 'assignTo',   $params, '',   'list', '', '', 'iframe', true);
          common::printIcon('bug', 'resolve',    $params, $bug, 'list', '', '', 'iframe', true);
          common::printIcon('bug', 'close',      $params, $bug, 'list', '', '', 'iframe', true);
          common::printIcon('bug', 'edit',       $params, $bug, 'list');
          common::printIcon('bug', 'create',     "product=$bug->product&branch=$bug->branch&extra=bugID=$bug->id", $bug, 'list', 'copy');
          ?>
        </td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
      </tbody>
      <?php endif;?>
