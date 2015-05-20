<?php
/**
 * The link bug view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: linkbug.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<div id='querybox' class='show'></div>
<div id='unlinkBugList'>
  <form method='post' id='unlinkedBugsForm' target='hiddenwin' action='<?php echo $this->createLink('productplan', 'linkBug', "planID=$plan->id&browseType=$browseType&param=$param&orderBy=$orderBy")?>'>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed'> 
      <caption class='text-left text-special'><?php echo html::icon('unlink');?> &nbsp;<strong><?php echo $lang->productplan->unlinkedBugs;?></strong></caption>
      <thead>
      <tr class='colhead'>
        <th class='w-id {sorter:"currency"}'><?php echo $lang->idAB;?></th>
        <th class='w-pri'>   <?php echo $lang->priAB;?></th>
        <th>                 <?php echo $lang->bug->title;?></th>
        <th class='w-user'>  <?php echo $lang->openedByAB;?></th>
        <th class='w-user'>  <?php echo $lang->assignedToAB;?></th>
        <th class='w-status'><?php echo $lang->statusAB;?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach($allBugs as $bug):?>
      <?php
      if(isset($planBugs[$bug->id])) continue;
      if($bug->plan and helper::diffDate($plans[$bug->plan], helper::today()) > 0) continue;
      ?>
      <tr>
        <td class='text-left'>
          <input class='ml-10px' type='checkbox' name='bugs[]'  value='<?php echo $bug->id;?>'/> 
          <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id);?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug->pri, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
        <td class='text-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
        <td><?php echo $users[$bug->openedBy];?></td>
        <td><?php echo $users[$bug->assignedTo];?></td>
        <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
      <tr>
        <td colspan='6' class='text-left'>
          <?php if(count($allBugs))
          {
              echo "<div class='table-actions clearfix'>";
              echo "<div class='btn-group'>" .  html::selectAll('unlinkedBugsForm') . html::selectReverse('unlinkedBugsForm') . '</div>';
              echo html::submitButton($lang->productplan->linkBug) . html::a(inlink('view', "planID=$plan->id&type=bug&orderBy=$orderBy"), $lang->goback, '', "class='btn'") . '</div>';
          }
          ?>
        </td>
      </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>$(function(){ajaxGetSearchForm('#bugs .linkBox #querybox')})</script>
