<?php
/**
 * The link bug view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: linkbug.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmUnlinkBug', $lang->productplan->confirmUnlinkBug)?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['plan']);?> <strong><?php echo $plan->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('productplan', 'view', 'planID=' . $plan->id), $plan->title);?></strong>
    <small class='text-muted'> <?php echo $lang->productplan->linkBug;?> <?php echo html::icon($lang->icons['link']);?></small>
  </div>
  <div class='actions'><?php echo html::a(inlink('view', "planID=$plan->id"), '<i class="icon-level-up icon-large icon-rotate-270"></i> ' . $lang->goback, '', "class='btn'")?></div>
</div>
<div id='querybox' class='show'></div>
<div id='bugList'>
  <form method='post' id='unlinkedBugsForm'>
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
          <?php if(count($allBugs)) echo "<div class='table-actions clearfix'><div class='btn-group'>" .  html::selectAll('unlinkedBugsForm') . html::selectReverse('unlinkedBugsForm') . '</div>' . html::submitButton($lang->productplan->linkBug) . '</div>';?>
        </td>
      </tr>
      </tfoot>
    </table>
  </form>
  <hr class='mg-0'>
  <form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug');?>" id='linkedBugsForm'>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed'> 
      <caption class='text-left text-important'><?php echo html::icon('unlink');?> &nbsp;<strong><?php echo $lang->productplan->linkedBugs;?></strong> (<?php echo count($planBugs);?>)</caption>
      <thead>
      <tr class='colhead'>
        <th class='w-id {sorter:"currency"}'><?php echo $lang->idAB;?></th>
        <th class='w-pri'>   <?php echo $lang->priAB;?></th>
        <th>                 <?php echo $lang->bug->title;?></th>
        <th class='w-user'>  <?php echo $lang->openedByAB;?></th>
        <th class='w-user'>  <?php echo $lang->assignedToAB;?></th>
        <th class='w-status'><?php echo $lang->statusAB;?></th>
        <th class='w-50px {sorter:false}'><?php echo $lang->actions?></th>
      </tr>
      </thead>
      <tbody>
      <?php $canBatchUnlink = common::hasPriv('productPlan', 'batchUnlinkBug');?>
      <?php foreach($planBugs as $bug):?>
      <tr>
        <td class='text-center'>
          <?php if($canBatchUnlink):?>
          <input class='ml-10px' type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/> 
          <?php endif;?>
          <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), sprintf("%03d", $bug->id));?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug->pri, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri);?></span></td>
        <td class='text-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
        <td><?php echo $users[$bug->openedBy];?></td>
        <td><?php echo $users[$bug->assignedTo];?></td>
        <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
        <td class='text-center'>
          <?php
          if(common::hasPriv('productplan', 'unlinkBug'))
          {
              $unlinkURL = $this->createLink('productplan', 'unlinkBug', "bugID=$bug->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"bugList\",confirmUnlinkBug)", "<i class='icon-remove'></i>", '', "title='{$lang->productplan->unlinkBug}' class='btn-icon'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      <?php if(count($planBugs) and $canBatchUnlink):?>
      <tfoot>
      <tr>
        <td colspan='7' class='text-left'>
        <?php 
        echo  "<div class='table-actions clearfix'><div class='btn-group'>" . html::selectAll('linkedBugsForm') . html::selectReverse('linkedBugsForm') . '</div>';
        echo html::submitButton($lang->productplan->batchUnlink) . '</div>';
        ?>
        </td>
      </tr>
      </tfoot>
      <?php endif;?>
      </tbody>
    </table>
  </form>
</div>
<script>$(function(){ajaxGetSearchForm()})</script>
<?php include '../../common/view/footer.html.php';?>
