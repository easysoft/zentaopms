<?php
/**
 * The browse view file of product module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<script language='Javascript'>
/* 切换浏览方式。*/
function browseByModule()
{
    $('#mainbox').addClass('yui-t7');
    $('#treebox').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#allTab').removeClass('active');
    $('#bysearchTab').removeClass('active');
    $('#querybox').addClass('hidden');
}
function search(active)
{
    $('#mainbox').removeClass('yui-t7');
    $('#treebox').addClass('hidden');
    $('#querybox').removeClass('hidden');
    $('#bymoduleTab').removeClass('active');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').addClass('active');
}

</script>

<div class='yui-d0'>
  <div id='featurebar'>
    <div class='f-left'>
      <span id='bymoduleTab' onclick='browseByModule()'><?php echo $lang->product->moduleStory;?></span>
      <span id='bysearchTab' onclick='search("<?php echo $browseType;?>")'><?php echo $lang->product->searchStory;?></span>
      <span id='allTab'><?php echo html::a($this->createLink('product', 'browse', "productID=$productID&browseType=all&param=0&orderBy=$orderBy&recTotal=0&recPerPage=200"), $lang->product->allStory);?></span>
    </div>
    <div class='f-right'>
      <?php if(common::hasPriv('story', 'create')) echo html::a($this->createLink('story', 'create', "productID=$productID&moduleID=$moduleID"), $lang->story->create); ?>
    </div>
  </div>
    <div id='querybox' class='<?php if($browseType !='bysearch') echo 'hidden';?>'><?php echo $searchForm;?></div>
</div>

<div class='yui-d0 <?php if($browseType == 'bymodule') echo 'yui-t7';?>' id='mainbox'>
  <div class='yui-b <?php if($browseType != 'bymodule') echo 'hidden';?>' id='treebox'>
    <div class='box-title'><?php echo $productName;?></div>
    <div class='box-content'>
      <?php echo $moduleTree;?>
      <div class='a-right'>
        <?php if(common::hasPriv('product', 'edit'))   echo html::a($this->createLink('product', 'edit',   "productID=$productID"), $lang->edit);?>
        <?php if(common::hasPriv('product', 'delete')) echo html::a($this->createLink('product', 'delete', "productID=$productID&confirm=no"),   $lang->delete, 'hiddenwin');?>
        <?php if(common::hasPriv('tree', 'browse'))    echo html::a($this->createLink('tree',    'browse', "productID=$productID&view=product"), $lang->tree->manage);?>
      </div>
    </div>
  </div>

  <div class="yui-main">
    <div class="yui-b">
      <table class='table-1 fixed colored tablesorter'>
        <thead>
          <tr class='colhead'>
            <?php $vars = "productID=$productID&browseType=$browseType&param=$moduleID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
            <th><?php common::printOrderLink('id',  $orderBy, $vars, $lang->story->id);?></th>
            <th><?php common::printOrderLink('pri', $orderBy, $vars, $lang->story->pri);?></th>
            <th class='w-p30'><?php common::printOrderLink('title', $orderBy, $vars, $lang->story->title);?></th>
            <th><?php common::printOrderLink('plan',           $orderBy, $vars, $lang->story->plan);?></th>
            <th><?php common::printOrderLink('openedBy',       $orderBy, $vars, $lang->story->openedBy);?></th>
            <th><?php common::printOrderLink('assignedTo',     $orderBy, $vars, $lang->story->assignedTo);?></th>
            <th><?php common::printOrderLink('estimate',       $orderBy, $vars, $lang->story->estimate);?></th>
            <th><?php common::printOrderLink('status',         $orderBy, $vars, $lang->story->status);?></th>
            <th><?php common::printOrderLink('stage',          $orderBy, $vars, $lang->story->stage);?></th>
            <th><?php common::printOrderLink('closedReason',   $orderBy, $vars, $lang->story->closedReason);?></th>
            <th class='w-100px'><?php common::printOrderLink('lastEditedDate', $orderBy, $vars, $lang->story->lastEdited);?></th>
            <th class='w-150px'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($stories as $key => $story):?>
          <?php
          $viewLink = $this->createLink('story', 'view', "storyID=$story->id");
          $canView  = common::hasPriv('story', 'view');
          ?>
          <tr class='a-center'>
            <td><?php if($canView) echo html::a($viewLink, sprintf('%03d', $story->id)); else printf('%03d', $story->id);?></td>
            <td><?php echo $story->pri;?></td>
            <td class='a-left nobr'><nobr><?php echo html::a($viewLink, $story->title);?></nobr></td>
            <td><?php echo $story->planTitle;?></td>
            <td><?php echo $users[$story->openedBy];?></td>
            <td><?php echo $users[$story->assignedTo];?></td>
            <td><?php echo $story->estimate;?></td>
            <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
            <td><?php echo $lang->story->stageList[$story->stage];?></td>
            <td><?php echo $lang->story->reasonList[$story->closedReason];?></td>
            <td><?php echo substr($story->lastEditedDate, 5, 11);?></td>
            <td>
              <?php 
              $vars = "story={$story->id}";
              if(!($story->status != 'closed' and common::printLink('story', 'change', $vars, $lang->story->change))) echo $lang->story->change . ' ';
              if(!(($story->status == 'draft' or $story->status == 'changed') and common::printLink('story', 'review', $vars, $lang->story->review))) echo $lang->story->review . ' ';
              if(!common::printLink('story', 'edit',   $vars, $lang->edit)) echo $lang->edit;
              if(!common::printLink('story', 'delete', $vars, $lang->delete)) echo $lang->delete;
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php $pager->show();?>
    </div>
  </div>
</div>  
<script language='javascript'>
$('#module<?php echo $moduleID;?>').addClass('active')
$('#<?php echo $browseType;?>Tab').addClass('active')
</script>
<?php include '../../common/view/footer.html.php';?>
