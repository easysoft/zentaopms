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
    <table class='table table-condensed table-hover table-striped tablesorter table-selectable' id='bugList'>
      <?php if($bugs2Link):?>
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
      <?php foreach($bugs2Link as $bug2Link):?>
      <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug2Link->id");?>
      <tr class='text-center'>
        <td class='cell-id'>
          <input type='checkbox' name='bugs[]'  value='<?php echo $bug2Link->id;?>'/> 
          <?php echo html::a($bugLink, sprintf('%03d', $bug2Link->id));?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug2Link->pri, $bug2Link->pri)?>'><?php echo zget($lang->bug->priList, $bug2Link->pri, $bug2Link->pri);?></span></td>
        <td><?php echo html::a($this->createLink('product', 'browse', "productID=$bug2Link->product&branch=$bug2Link->branch"), $products[$bug2Link->product], '_blank');?></td>
        <td class='text-left nobr' title="<?php echo $bug2Link->title?>"><?php echo html::a($bugLink, $bug2Link->title);?></td>
        <td><?php echo $lang->bug->statusList[$bug->status];?></td>
        <td><?php echo $users[$bug2Link->openedBy];?></td>
        <td><?php echo $users[$bug2Link->assignedTo];?></td>
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
      <tr>
        <td class='hidden'><?php echo html::input('bug', $bug->id);?></td>
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
