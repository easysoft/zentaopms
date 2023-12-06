<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4909 2013-06-26 07:23:50Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include './caseheader.html.php';?>
<?php js::set('resetActive', false);?>
<?php js::set('tab', $app->tab);?>
<div id='mainContent' class='main-control'>
  <?php if(empty($stories)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->story->noStory;?></span></p>
  </div>
  <?php else:?>
  <form method='post' id='productStoryForm' class='main-table table-story' data-ride='table'>
    <table class='table has-sort-head table-fixed' id='storyList'>
      <thead>
      <tr>
        <?php
        $this->app->rawModule = 'testcase';
        $vars = "productID=$productID&branchID=$branch&orderBy=%s&project=$projectID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";
        ?>
        <th class='c-id'>
          <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
            <label></label>
          </div>
          <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
        </th>
        <th class='c-pri' title=<?php echo $lang->story->pri;?>><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
        <th class='w-p30'>     <?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
        <th>                   <?php common::printOrderLink('plan',       $orderBy, $vars, $lang->story->planAB);?></th>
        <th class='thWidth'>   <?php common::printOrderLink('source',     $orderBy, $vars, $lang->story->source);?></th>
        <th class='c-user'>    <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
        <th class='c-user'>    <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
        <th class='c-estiamte'><?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
        <th class='c-status'>  <?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
        <th class='c-stage'>   <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
        <th class='c-actions-5 text-center'><?php echo $lang->actions;?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach($stories as $key => $story):?>
      <?php
      $param = 0;
      if($this->app->tab == 'project')   $param = $this->session->project;
      if($this->app->tab == 'execution') $param = $this->session->execution;
      $viewLink = $this->createLink('story', 'view', "storyID=$story->id&version=0&param=$param");
      $canView  = common::hasPriv('story', 'view');
      ?>
      <tr>
        <td class='c-id'>
          <div class="checkbox-primary">
            <input type='checkbox' name='storyIdList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' />
            <label></label>
          </div>
          <?php printf('%03d', $story->id);?>
        </td>
        <td><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri)?></span></td>
        <td class='text-left' title="<?php echo $story->title?>">
          <nobr>
            <?php echo $canView ? html::a($viewLink, $story->title, '', "data-app='product'") : $story->title;?>
          </nobr>
        </td>
        <td title="<?php echo $story->planTitle?>"><?php echo $story->planTitle;?></td>
        <td><?php echo $lang->story->sourceList[$story->source];?></td>
        <td><?php echo zget($users, $story->openedBy);?></td>
        <td><?php echo zget($users, $story->assignedTo);?></td>
        <td><?php echo $story->estimate;?></td>
        <td><span class='status-story status-<?php echo $story->status;?>'><?php echo $this->processStatus('story', $story);?></span></td>
        <td><?php echo zget($lang->story->stageList, $story->stage);?></td>
        <td class='c-actions'>
          <?php
          $vars = "storyID={$story->id}";
          $this->app->tab = 'product';
          common::printIcon('story', 'change', $vars, $story, 'list', 'fork');
          common::printIcon('story', 'review', $vars, $story, 'list', 'glasses');
          common::printIcon('story', 'close',  $vars, $story, 'list', 'off', '', 'iframe', 'yes');
          common::printIcon('story', 'edit',   $vars, $story, 'list');
          $this->app->tab = 'qa';
          common::printIcon('story', 'createCase', "productID=$story->product&branch=0&module=0&from=&param=0&$vars", $story, 'list', 'sitemap');
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php
        $canBatchEdit  = common::hasPriv('story', 'batchEdit');
        $disabled   = $canBatchEdit ? '' : "disabled='disabled'";
        $actionLink = $this->createLink('story', 'batchEdit', "productID=$productID&projectID=$projectID&branch=$branch");
        ?>
        <?php echo html::commonButton($lang->edit, "data-form-action='$actionLink' $disabled");?>
        <?php
        if(common::hasPriv('story', 'batchReview'))
        {
            echo "<div class='btn-group dropup'>";
            echo html::commonButton($lang->story->review . "<span class='caret'></span>", "data-toggle='dropdown'");
            echo "<ul class='dropdown-menu'>";
            unset($lang->story->reviewResultList['']);
            unset($lang->story->reviewResultList['revert']);
            foreach($lang->story->reviewResultList as $key => $result)
            {
                $actionLink = $this->createLink('story', 'batchReview', "result=$key");
                if($key == 'reject')
                {
                    echo "<li class='dropdown-submenu'>";
                    echo html::a('#', $result, '', "id='rejectItem'");
                    echo "<ul class='dropdown-menu'>";
                    unset($lang->story->reasonList['']);
                    unset($lang->story->reasonList['subdivided']);
                    unset($lang->story->reasonList['duplicate']);

                    foreach($lang->story->reasonList as $key => $reason)
                    {
                        $actionLink = $this->createLink('story', 'batchReview', "result=reject&reason=$key");
                        echo "<li>";
                        echo html::a('#', $reason, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"");
                        echo "</li>";
                    }
                    echo '</ul></li>';
                }
                else
                {
                  echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"") . '</li>';
                }
            }
            echo '</ul></div>';
        }

        if(common::hasPriv('story', 'batchChangeStage'))
        {
            $class = count($stories) <= 3 ? 'dropdown' : 'dropup';
            echo "<div class='btn-group $class'>";
            echo html::commonButton($lang->story->stageAB . "<span class='caret'></span>", "data-toggle='dropdown'");
            echo "<ul class='dropdown-menu'>";
            $lang->story->stageList[''] = $lang->null;
            foreach($lang->story->stageList as $key => $stage)
            {
                $actionLink = $this->createLink('story', 'batchChangeStage', "stage=$key");
                echo "<li>" . html::a('#', $stage, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"") . "</li>";
            }
            echo '</ul></div>';
        }
        ?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
