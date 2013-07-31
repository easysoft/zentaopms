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
<div id='querybox'></div>
<div id='bugList'>
  <form method='post' id='unlinkedBugsForm'>
    <table class='table-1 tablesorter a-center fixed'> 
      <caption class='caption-tl'><?php echo $plan->title .$lang->colon . $lang->productplan->unlinkedBugs;?></caption>
      <thead>
      <tr class='colhead'>
        <th class='w-id'>    <?php echo $lang->idAB;?></th>
        <th class='w-pri'>   <?php echo $lang->priAB;?></th>
        <th class='w-200px'> <?php echo $lang->bug->plan;?></th>
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
        <td class='a-left'>
          <input class='ml-10px' type='checkbox' name='bugs[]'  value='<?php echo $bug->id;?>'/> 
          <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id);?>
        </td>
        <td><span class='<?php echo 'pri' . $bug->pri;?>'><?php echo $bug->pri?></span></td>
        <td><?php echo $bug->planTitle;?></td>
        <td class='a-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
        <td><?php echo $users[$bug->openedBy];?></td>
        <td><?php echo $users[$bug->assignedTo];?></td>
        <td><?php echo $lang->bug->statusList[$bug->status];?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
      <tr>
        <td colspan='7' class='a-left'>
          <?php if(count($allBugs)) echo html::selectAll('unlinkedBugsForm') . html::selectReverse('unlinkedBugsForm') .  html::submitButton($lang->productplan->linkBug);?>
        </td>
      </tr>
      </tfoot>
    </table>
  </form>
   
  <form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug');?>" id='linkedBugsForm'>
    <table class='table-1 tablesorter a-center fixed'> 
      <caption class='caption-tl'><?php echo $plan->title .$lang->colon . $lang->productplan->linkedBugs;?></caption>
      <thead>
      <tr class='colhead'>
        <th class='w-id'>    <?php echo $lang->idAB;?></th>
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
        <td class='a-center'>
          <?php if($canBatchUnlink):?>
          <input class='ml-10px' type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/> 
          <?php endif;?>
          <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), sprintf("%03d", $bug->id));?>
        </td>
        <td><span class='<?php echo 'pri' . $bug->pri;?>'><?php echo $bug->pri?></span></td>
        <td class='a-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
        <td><?php echo $users[$bug->openedBy];?></td>
        <td><?php echo $users[$bug->assignedTo];?></td>
        <td><?php echo $lang->bug->statusList[$bug->status];?></td>
        <td>
          <?php
          $unlinkURL = $this->createLink('productplan', 'unlinkBug', "bugID=$bug->id&confirm=yes");
          echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"bugList\",confirmUnlinkBug)", '&nbsp;', '', "class='icon-green-productplan-unlinkBug' title='{$lang->productplan->unlinkBug}'");
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      <?php if(count($planBugs) and $canBatchUnlink):?>
      <tfoot>
      <tr>
        <td colspan='7' class='a-left'>
        <?php 
        echo html::selectAll('linkedBugsForm') . html::selectReverse('linkedBugsForm');
        echo html::submitButton($lang->productplan->batchUnlink);
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
