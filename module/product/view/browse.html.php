<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4909 2013-06-26 07:23:50Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php include '../../common/view/dropmenu.html.php';?>
<?php js::set('browseType', $browseType);?>
<div id='featurebar'>
  <div class='f-left'>
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
    <?php 
    echo '<span class="link-button dropButton">';
    echo html::a("#", "&nbsp;", '', "id='exportAction' class='icon-green-common-export' onclick=toggleSubMenu(this.id,'bottom',0) title='{$lang->export}'");
    echo html::a("#", $lang->export, '', "id='exportAction' onclick=toggleSubMenu(this.id,'bottom',0) title='{$lang->export}'");
    echo '</span>';

    common::printIcon('story', 'report', "productID=$productID&browseType=$browseType&moduleID=$moduleID");
    common::printIcon('story', 'batchCreate', "productID=$productID&moduleID=$moduleID");
    common::printIcon('story', 'create', "productID=$productID&moduleID=$moduleID"); 
    ?>
  </div>
</div>
<div id='exportActionMenu' class='listMenu hidden'>
  <ul>
  <?php 
  $misc = common::hasPriv('story', 'export') ? "class='export'" : "class=disabled";
  $link = common::hasPriv('story', 'export') ?  $this->createLink('story', 'export', "productID=$productID&orderBy=$orderBy") : '#';
  echo "<li>" . html::a($link, $lang->story->export, '', $misc) . "</li>";
  ?>
  </ul>
</div>

<div id='querybox' class='<?php if($browseType !='bysearch') echo 'hidden';?>'></div>
<div class='treeSlider' id='storyTree'><span>&nbsp;</span></div>
<form method='post' id='productStoryForm'>
  <table class='cont-lt1'>
    <tr valign='top'>
      <td class='side' id='treebox'>
        <div class='box-title'><?php echo $productName;?></div>
        <div class='box-content'>
          <?php echo $moduleTree;?>
          <div class='a-right'>
            <?php common::printLink('tree', 'browse', "rootID=$productID&view=story", $lang->tree->manage);?>
            <?php common::printLink('tree', 'fix',    "root=$productID&type=story", $lang->tree->fix, 'hiddenwin');?>
          </div>
        </div>
      </td>
      <td class='divider'></td>
      <td>
        <table class='table-1 fixed colored tablesorter datatable' id='storyList'>
          <thead>
          <tr class='colhead'>
            <?php $vars = "productID=$productID&browseType=$browseType&param=$moduleID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
            <th class='w-id'>  <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
            <th class='w-pri'> <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
            <th class='w-p30'> <?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
            <th>               <?php common::printOrderLink('plan',       $orderBy, $vars, $lang->story->planAB);?></th>
            <th>               <?php common::printOrderLink('source',     $orderBy, $vars, $lang->story->source);?></th>
            <th>               <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
            <th>               <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
            <th class='w-hour'><?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
            <th>               <?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
            <th>               <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
            <th class='w-140px {sorter:false}'><?php echo $lang->actions;?></th>
          </tr>
          </thead>
          <tbody>
          <?php foreach($stories as $key => $story):?>
          <?php
          $viewLink = $this->createLink('story', 'view', "storyID=$story->id");
          $canView  = common::hasPriv('story', 'view');
          ?>
          <tr class='a-center'>
            <td>
              <input type='checkbox' name='storyIDList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' /> 
              <?php if($canView) echo html::a($viewLink, sprintf('%03d', $story->id)); else printf('%03d', $story->id);?>
            </td>
            <td><span class='<?php echo 'pri' . $lang->story->priList[$story->pri];?>'><?php echo $lang->story->priList[$story->pri]?></span></td>
            <td class='a-left' title="<?php echo $story->title?>"><nobr><?php echo html::a($viewLink, $story->title);?></nobr></td>
            <td title="<?php echo $story->planTitle?>"><?php echo $story->planTitle;?></td>
            <td><?php echo $lang->story->sourceList[$story->source];?></td>
            <td><?php echo $users[$story->openedBy];?></td>
            <td><?php echo $users[$story->assignedTo];?></td>
            <td><?php echo $story->estimate;?></td>
            <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
            <td><?php echo $lang->story->stageList[$story->stage];?></td>
            <td class='a-right'>
              <?php 
              $vars = "story={$story->id}";
              common::printIcon('story', 'change',     $vars, $story, 'list');
              common::printIcon('story', 'review',     $vars, $story, 'list');
              common::printIcon('story', 'close',      $vars, $story, 'list');
              common::printIcon('story', 'edit',       $vars, $story, 'list');
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
                  echo "<div class='groupButton'>";
                  echo html::selectAll() . html::selectReverse();
                  echo "</div>";

                  $canBatchEdit  = common::hasPriv('story', 'batchEdit');
                  $disabled   = $canBatchEdit ? '' : "disabled='disabled'";
                  $actionLink = $this->createLink('story', 'batchEdit', "productID=$productID&projectID=0");

                  echo "<div class='groupButton dropButton'>";
                  echo html::commonButton($lang->edit, "onclick=\"setFormAction('$actionLink')\" $disabled");
                  echo "<button id='moreAction' type='button' onclick=\"toggleSubMenu(this.id, 'top', 0)\"><span class='caret'></span></button>";
                  echo "</div>";
              }

              echo $summary;
              ?>
              </div>
              <?php $pager->show();?>
            </td>
          </tr>
          </tfoot>
        </table>

        <div id='moreActionMenu' class='listMenu hidden'>
          <ul>
          <?php 
          $class = "class='disabled'";

          $canBatchClose = common::hasPriv('story', 'batchClose') && strtolower($browseType) != 'closedbyme' && strtolower($browseType) != 'closedstory';
          $actionLink    = $this->createLink('story', 'batchClose', "productID=$productID&projectID=0");
          $misc = $canBatchClose ? "onclick=setFormAction('$actionLink')" : $class;
          echo "<li>" . html::a('#', $lang->close, '', $misc) . "</li>";

          $misc = common::hasPriv('story', 'batchReview') ? "onmouseover='toggleSubMenu(this.id)' onmouseout='toggleSubMenu(this.id)' id='reviewItem'" : $class;
          echo "<li>" . html::a('#', $lang->story->review,  '', $misc) . "</li>";

          $misc = common::hasPriv('story', 'batchChangePlan') ? "onmouseover='toggleSubMenu(this.id)' onmouseout='toggleSubMenu(this.id)' id='planItem'" : $class;
          echo "<li>" . html::a('#', $lang->story->planAB,  '', $misc) . "</li>";

          $misc = common::hasPriv('story', 'batchChangeStage') ? "onmouseover='toggleSubMenu(this.id)' onmouseout='toggleSubMenu(this.id)' id='stageItem'" : $class;
          echo "<li>" . html::a('#', $lang->story->stageAB, '', $misc) . "</li>";
          ?>
          </ul>
        </div>

        <div id='reviewItemMenu' class='hidden listMenu'>
          <ul>
          <?php
          unset($lang->story->reviewResultList['']);
          unset($lang->story->reviewResultList['revert']);
          foreach($lang->story->reviewResultList as $key => $result)
          {
              $actionLink = $this->createLink('story', 'batchReview', "result=$key");
              echo "<li>";
              if($key == 'reject')
              {
                  echo html::a('#', $result, '', "onmouseover='toggleSubMenu(this.id, \"right\", 2)' id='rejectItem'");
              }
              else
              {
                  echo html::a('#', $result, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
              }
              echo "</li>";
          }
          ?>
          </ul>
        </div>

        <div id='rejectItemMenu' class='hidden listMenu'>
          <ul>
          <?php
          unset($lang->story->reasonList['']);
          unset($lang->story->reasonList['subdivided']);
          unset($lang->story->reasonList['duplicate']);

          foreach($lang->story->reasonList as $key => $reason)
          {
              $actionLink = $this->createLink('story', 'batchReview', "result=reject&reason=$key");
              echo "<li>";
              echo html::a('#', $reason, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
              echo "</li>";
          }
          ?>
          </ul>
        </div>

        <div id='planItemMenu' class='hidden listMenu'>
          <ul>
          <?php
          unset($plans['']);
          $plans = array(0 => $lang->null) + $plans;
          foreach($plans as $planID => $plan)
          {
              $actionLink = $this->createLink('story', 'batchChangePlan', "planID=$planID");
              echo "<li>" . html::a('#', $plan, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
          }
          ?>
          </ul>
        </div>

        <div id='stageItemMenu' class='hidden listMenu'>
          <ul>
          <?php
          $lang->story->stageList[''] = $lang->null;
          foreach($lang->story->stageList as $key => $stage)
          {
              $actionLink = $this->createLink('story', 'batchChangeStage', "stage=$key");
              echo "<li>" . html::a('#', $stage, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
          }
          ?>
          </ul>
        </div>
      </td>              
    </tr>
  </table>
</form>
<script language='javascript'>
$('#module<?php echo $moduleID;?>').addClass('active')
$('#<?php echo $browseType;?>Tab').addClass('active')
</script>
<?php include '../../common/view/footer.html.php';?>
