<?php
/**
 * The link bug view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     bug
 * @version     $Id: linkbugs.html.php 4129 2016-03-08 09:00:12Z chenfei $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['bug']);?> <strong><?php echo $bug->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, '_blank');?></strong>
      <small class='text-muted'> <?php echo $lang->bug->linkBugs;?> <?php echo html::icon($lang->icons['link']);?></small>
    </div>
    <div id='querybox' class='show'></div>
  </div>
  <form method='post' class='form-condensed' target='hiddenwin' id='linkBugsForm'>
    <table class='table table-condensed table-hover table-striped tablesorter' id='bugList'>
      <?php if($allBugs):?>
      <thead>
      <tr>
        <th class='w-id'><?php echo $lang->idAB;?></th>
        <th class='w-pri'><?php echo $lang->priAB;?></th>
        <th><?php echo $lang->bug->product;?></th>
        <th><?php echo $lang->bug->title;?></th>
        <th><?php echo $lang->bug->statusAB;?></th>
        <th class='w-user'><?php echo $lang->openedByAB;?></th>
        <th class='w-user'><?php echo $lang->assignedToAB;?></th>
      </tr>
      </thead>
      <tbody>
      <?php $bugCount = 0;?>
      <?php foreach($allBugs as $bugDetail):?>
      <?php if(in_array($bugDetail->id, explode(',', $bug->linkBug))) continue;?>
      <?php if($bugDetail->id == $bug->id) continue;?>
      <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bugDetail->id");?>
      <tr class='text-center'>
        <td class='text-left'>
          <input type='checkbox' name='bugs[]'  value='<?php echo $bugDetail->id;?>'/> 
          <?php echo html::a($bugLink, sprintf('%03d', $bugDetail->id));?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bugDetail->pri, $bugDetail->pri)?>'><?php echo zget($lang->bug->priList, $bugDetail->pri, $bugDetail->pri);?></span></td>
        <td><?php echo html::a($this->createLink('product', 'browse', "productID=$bugDetail->product&branch=$bugDetail->branch"), $products[$bugDetail->product], '_blank');?></td>
        <td class='text-left nobr' title="<?php echo $bugDetail->title?>"><?php echo html::a($bugLink, $bugDetail->title);?></td>
        <td><?php echo $lang->bug->statusList[$bug->status];?></td>
        <td><?php echo $users[$bugDetail->openedBy];?></td>
        <td><?php echo $users[$bugDetail->assignedTo];?></td>
      </tr>
      <?php $bugCount ++;?>
      <?php endforeach;?>
      </tbody>
      <tfoot>
      <tr>
        <td colspan='7' class='text-left'>
          <div class='table-actions clearfix'>
          <?php if($bugCount) echo html::selectButton() . html::submitButton();?>
          </div>
        </td>
      </tr>
      </tfoot>
      <?php endif;?>
    </table>
  </form>
</div>
<script type='text/javascript'>
$(function(){ajaxGetSearchForm();});
</script>
<?php include '../../common/view/footer.html.php';?>
