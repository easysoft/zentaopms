<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<script language='Javascript'>
var browseType = '<?php echo $browseType;?>';
</script>
<div id='featurebar'>
  <div class='f-left'>
    <span id='bymoduleTab' onclick='browseByModule()'><a href='#'><?php echo $lang->product->moduleStory;?></a></span>
    <span id='assignedtomeTab'> <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=assignedtome"), $lang->product->assignedToMe);?></span>
    <span id='openedbymeTab'>   <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=openedByMe"),   $lang->product->openedByMe);?></span>
    <span id='reviewedbymeTab'> <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=reviewedByMe"), $lang->product->reviewedByMe);?></span>
    <span id='closedbymeTab'>   <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=closedByMe"),   $lang->product->closedByMe);?></span>
    <span id='draftstoryTab'>   <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=draftStory"),   $lang->product->draftStory);?></span>
    <span id='activestoryTab'>  <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=activeStory"),  $lang->product->activeStory);?></span>
    <span id='changedstoryTab'> <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=changedStory"), $lang->product->changedStory);?></span>
    <span id='closedstoryTab'>  <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=closedStory"),  $lang->product->closedStory);?></span>
    <span id='allstoryTab'>     <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=allStory"),     $lang->product->allStory);?></span>
    <span id='bysearchTab' ><a href='#'><span class='icon-search'></span><?php echo $lang->product->searchStory;?></a></span>
  </div>
  <div class='f-right'>
    <?php common::printLink('story', 'export', "productID=$productID&orderBy=$orderBy", $lang->export, '', 'class="export"'); ?>
    <?php common::printLink('story', 'report', "productID=$productID&browseType=$browseType&moduleID=$moduleID", $lang->story->report->common); ?>
    <?php if(common::hasPriv('story', 'create')) echo html::a($this->createLink('story', 'batchCreate', "productID=$productID&moduleID=$moduleID"), $lang->story->batchCreate); ?>
    <?php if(common::hasPriv('story', 'create')) echo html::a($this->createLink('story', 'create', "productID=$productID&moduleID=$moduleID"), $lang->story->create); ?>
  </div>
</div>
<div id='querybox' class='<?php if($browseType !='bysearch') echo 'hidden';?>'><?php echo $searchForm;?></div>
<form method='post' id='productStoryForm'>
  <table class='cont-lt1'>
    <tr valign='top'>
      <td class='side <?php echo $treeClass;?>' id='treebox'>
        <div class='box-title'><?php echo $productName;?></div>
        <div class='box-content'>
          <?php echo $moduleTree;?>
          <div class='a-right'>
            <?php if(common::hasPriv('product', 'edit'))   echo html::a($this->createLink('product', 'edit',   "productID=$productID"), $lang->edit);?>
            <?php if(common::hasPriv('product', 'delete')) echo html::a($this->createLink('product', 'delete', "productID=$productID&confirm=no"),   $lang->delete, 'hiddenwin');?>
            <?php if(common::hasPriv('tree', 'browse'))    echo html::a($this->createLink('tree',    'browse', "rootID=$productID&view=story"), $lang->tree->manage);?>
          </div>
        </div>
      </td>
      <td class='divider <?php echo $treeClass;?>'></td>
      <td>
        <table class='table-1 fixed colored tablesorter datatable'>
          <thead>
          <tr class='colhead'>
            <?php $vars = "productID=$productID&browseType=$browseType&param=$moduleID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
            <th class='w-id'> <?php common::printOrderLink('id',    $orderBy, $vars, $lang->idAB);?></th>
            <th class='w-pri'><?php common::printOrderLink('pri',   $orderBy, $vars, $lang->priAB);?></th>
            <th class='w-p30'><?php common::printOrderLink('title', $orderBy, $vars, $lang->story->title);?></th>
            <th><?php common::printOrderLink('plan',       $orderBy, $vars, $lang->story->planAB);?></th>
            <th><?php common::printOrderLink('source',     $orderBy, $vars, $lang->story->source);?></th>
            <th><?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
            <th><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
            <th class='w-hour'><?php common::printOrderLink('estimate', $orderBy, $vars, $lang->story->estimateAB);?></th>
            <th><?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB);?></th>
            <th><?php common::printOrderLink('stage',  $orderBy, $vars, $lang->story->stageAB);?></th>
            <th class='w-150px {sorter:false}'><?php echo $lang->actions;?></th>
          </tr>
          </thead>
          <tbody>
          <?php $totalEstimate = 0.0;?>
          <?php foreach($stories as $key => $story):?>
          <?php
          $viewLink = $this->createLink('story', 'view', "storyID=$story->id");
          $totalEstimate += $story->estimate; 
          $canView  = common::hasPriv('story', 'view');
          ?>
          <tr class='a-center'>
            <td>
              <input type='checkbox' name='storyIDList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' /> 
              <?php if($canView) echo html::a($viewLink, sprintf('%03d', $story->id)); else printf('%03d', $story->id);?>
            </td>
            <td><?php echo $lang->story->priList[$story->pri];?></td>
            <td class='a-left nobr'><nobr><?php echo html::a($viewLink, $story->title);?></nobr></td>
            <td class='nobr'><?php echo $story->planTitle;?></td>
            <td><?php echo $lang->story->sourceList[$story->source];?></td>
            <td><?php echo $users[$story->openedBy];?></td>
            <td><?php echo $users[$story->assignedTo];?></td>
            <td><?php echo $story->estimate;?></td>
            <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
            <td><?php echo $lang->story->stageList[$story->stage];?></td>
            <td>
              <?php 
              $vars = "story={$story->id}";
              if(!common::printLink('testcase', 'create', "productID=$story->product&module=0&from=&param=0&$vars", $lang->story->createCase)) echo $lang->story->createCase . ' ';
              if(!($story->status != 'closed' and common::printLink('story', 'change', $vars, $lang->story->change))) echo $lang->story->change . ' ';
              if(!(($story->status == 'draft' or $story->status == 'changed') and common::printLink('story', 'review', $vars, $lang->story->review))) echo $lang->story->review . ' ';
              if(!common::printLink('story', 'edit',   $vars, $lang->edit)) echo $lang->edit;
              ?>
            </td>
          </tr>
          <?php endforeach;?>
          </tbody>
          <tfoot>
          <tr>
            <td colspan='11' class='a-right'>
              <div class='f-left'>
              <?php
              if(count($stories))
              {
                  echo html::selectAll() . html::selectReverse();

                  if(common::hasPriv('story', 'batchClose') and strtolower($browseType) != 'closedbyme' and strtolower($browseType) != 'closedstory')
                  {
                      $actionLink = $this->createLink('story', 'batchClose', "from=productBrowse&productID=$productID&projectID=0&orderBy=$orderBy");
                      echo html::commonButton($lang->story->batchClose, "onclick=\"changeAction('productStoryForm', 'batchClose', '$actionLink')\"");
                  }
              }
              printf($lang->product->storySummary, count($stories), $totalEstimate);
              ?>
              </div>
              <?php $pager->show();?>
            </td>
          </tr>
          </tfoot>
        </table>
      </td>              
    </tr>
  </table>
</form>
<script language='javascript'>
$('#module<?php echo $moduleID;?>').addClass('active')
$('#<?php echo $browseType;?>Tab').addClass('active')
</script>
<?php include '../../common/view/footer.html.php';?>
