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
    <span id='bymoduleTab' onclick='browseByModule()'><?php echo html::a($this->inlink('browse',"productID=$productID"), $lang->product->moduleStory);?></span>
    <span id='allstoryTab'>     <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=allStory"),     $lang->product->allStory);?></span>
    <span id='assignedtomeTab'> <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=assignedtome"), $lang->product->assignedToMe);?></span>
    <span id='openedbymeTab'>   <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=openedByMe"),   $lang->product->openedByMe);?></span>
    <span id='reviewedbymeTab'> <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=reviewedByMe"), $lang->product->reviewedByMe);?></span>
    <span id='closedbymeTab'>   <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=closedByMe"),   $lang->product->closedByMe);?></span>
    <span id='draftstoryTab'>   <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=draftStory"),   $lang->product->draftStory);?></span>
    <span id='activestoryTab'>  <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=activeStory"),  $lang->product->activeStory);?></span>
    <span id='changedstoryTab'> <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=changedStory"), $lang->product->changedStory);?></span>
    <span id='closedstoryTab'>  <?php echo html::a($this->inlink('browse', "productID=$productID&browseType=closedStory"),  $lang->product->closedStory);?></span>
    <span id='bysearchTab' ><a href='#'><span class='icon-search'></span><?php echo $lang->product->searchStory;?></a></span>
  </div>
  <div class='f-right'>
    <?php common::printIcon('story', 'export', "productID=$productID&orderBy=$orderBy");?>
    <?php common::printIcon('story', 'report', "productID=$productID&browseType=$browseType&moduleID=$moduleID");?>
    <?php common::printIcon('story', 'batchCreate', "productID=$productID&moduleID=$moduleID");?>
    <?php common::printIcon('story', 'create', "productID=$productID&moduleID=$moduleID"); ?>
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
            <?php common::printLink('tree', 'browse', "rootID=$productID&view=story", $lang->tree->manage);?>
            <?php common::printLink('tree', 'fix',    "root=$productID&type=story", $lang->tree->fix, 'hiddenwin');?>
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
            <th class='w-100px {sorter:false}'><?php echo $lang->actions;?></th>
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
            <td><span class='<?php echo 'pri' . $lang->story->priList[$story->pri];?>'><?php echo $lang->story->priList[$story->pri]?></span></td>
            <td class='a-left nobr'><nobr><?php echo html::a($viewLink, $story->title);?></nobr></td>
            <td class='nobr'><?php echo $story->planTitle;?></td>
            <td><?php echo $lang->story->sourceList[$story->source];?></td>
            <td><?php echo $users[$story->openedBy];?></td>
            <td><?php echo $users[$story->assignedTo];?></td>
            <td><?php echo $story->estimate;?></td>
            <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
            <td><?php echo $lang->story->stageList[$story->stage];?></td>
            <td class='a-right'>
              <?php 
              $vars = "story={$story->id}";
              common::printIcon('story', 'change', $vars, $story, 'list');
              common::printIcon('story', 'review', $vars, $story, 'list');
              common::printIcon('story', 'edit', "storyID=$story->id", $story, 'list');
              common::printIcon('story', 'createCase', "productID=$story->product&module=0&from=&param=0&$vars", $story, 'list', 'createCase');
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
