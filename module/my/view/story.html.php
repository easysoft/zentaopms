<?php
/**
 * The story view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: story.html.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    echo html::a(inlink('story', "type=assignedTo"),  "<span class='text'>{$lang->my->storyMenu->assignedToMe}</span>" . ($type == 'assignedTo' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'assignedTo' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('story', "type=openedBy"),    "<span class='text'>{$lang->my->storyMenu->openedByMe}</span>"   . ($type == 'openedBy'   ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'openedBy'   ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('story', "type=reviewedBy"),  "<span class='text'>{$lang->my->storyMenu->reviewedByMe}</span>" . ($type == 'reviewedBy' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'reviewedBy' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('story', "type=closedBy"),    "<span class='text'>{$lang->my->storyMenu->closedByMe}</span>"   . ($type == 'closedBy'   ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'closedBy'   ? ' btn-active-text' : '') . "'");
    ?>
  </div>
</div>
<div id="mainContent">
  <?php if(!$stories):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->story->noStory;?></span></p>
  </div>
  <?php else:?>
  <form id='myStoryForm' class="main-table table-story" data-ride="table" method="post">
    <table class="table has-sort-head table-fixed">
      <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
      <?php
      $canBatchEdit  = common::hasPriv('story', 'batchEdit');
      $canBatchClose = (common::hasPriv('story', 'batchClose') && strtolower($type) != 'closedbyme');
      ?>
      <thead>
        <tr>
          <th class="c-id">
            <?php if($canBatchEdit or $canBatchClose):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='c-pri'>      <?php common::printOrderLink('pri',          $orderBy, $vars, $lang->priAB);?></th>
          <th class='c-product'>  <?php common::printOrderLink('productTitle', $orderBy, $vars, $lang->story->product);?></th>
          <th class='c-name'>     <?php common::printOrderLink('title',        $orderBy, $vars, $lang->story->title);?></th>
          <th class='c-plan'>     <?php common::printOrderLink('plan',         $orderBy, $vars, $lang->story->plan);?></th>
          <th class='c-user'>     <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='c-hours'>    <?php common::printOrderLink('estimate',     $orderBy, $vars, $lang->story->estimateAB);?></th>
          <th class='c-status'>   <?php common::printOrderLink('status',       $orderBy, $vars, $lang->statusAB);?></th>
          <th class='c-stage'>    <?php common::printOrderLink('stage',        $orderBy, $vars, $lang->story->stageAB);?></th>
          <th class='c-actions-5'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($stories as $story):?>
        <?php $storyLink = $this->createLink('story', 'view', "id=$story->id");?>
        <tr>
          <td class="c-id">
            <?php if($canBatchEdit or $canBatchClose):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='storyIdList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' />
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $story->id);?>
          </td>
          <td class='c-pri'><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
          <td class='c-product'><?php echo $story->productTitle;?></td>
          <td class='c-name nobr'><?php echo html::a($storyLink, $story->title, null, "style='color: $story->color'");?></td>
          <td class='c-plan'><?php echo $story->planTitle;?></td>
          <td class='c-user'><?php echo zget($users, $story->openedBy);?></td>
          <td class='c-hours'><?php echo $story->estimate;?></td>
          <td class='c-status'><span class='status-story status-<?php echo $story->status;?>'> <?php echo $this->processStatus('story', $story);?></span></td>
          <td class='c-stage'><?php echo zget($lang->story->stageList, $story->stage);?></td>
          <td class='c-actions'>
            <?php
            $vars = "story={$story->id}";
            common::printIcon('story', 'change',     $vars, $story, 'list', 'fork');
            common::printIcon('story', 'review',     $vars, $story, 'list', 'glasses');
            common::printIcon('story', 'close',      $vars, $story, 'list', '', '', 'iframe', true);
            common::printIcon('story', 'edit',       $vars, $story, 'list');
            if($config->global->flow != 'onlyStory') common::printIcon('story', 'createCase', "productID=$story->product&branch=$story->branch&module=0&from=&param=0&$vars", $story, 'list', 'sitemap');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if($canBatchEdit or $canBatchClose):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
        <?php
        if($canBatchEdit)
        {
            $actionLink = $this->createLink('story', 'batchEdit');
            $misc       = "data-form-action='$actionLink'";
            echo html::commonButton($lang->edit, $misc);
        }
        ?>
        <?php if(common::hasPriv('story', 'batchReview')):?>
        <div class="btn-group dropup">
          <button type='button' class='btn' data-toggle='dropdown'><?php echo $lang->story->review;?> <span class='caret'></span></button>
          <ul class='dropdown-menu'>
            <?php
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
            ?>
          </ul>
        </div>
        <?php endif;?>
        <?php if(common::hasPriv('story', 'batchAssignTo')):?>
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->story->assignedTo?> <span class="caret"></span></button>
          <?php
          $withSearch = count($users) > 10;
          $actionLink = $this->createLink('story', 'batchAssignTo');
          echo html::select('assignedTo', $users, '', 'class="hidden"');
          if($withSearch)
          {
              echo "<div class='dropdown-menu search-list search-box-sink' data-ride='searchList'>";
              echo '<div class="input-control search-box has-icon-left has-icon-right search-example">';
              echo '<input id="userSearchBox" type="search" class="form-control search-input" autocomplete="off" />';
              echo '<label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>';
              echo '<a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>';
              echo '</div>';
              $usersPinYin = common::convert2Pinyin($users);
          }
          else
          {
              echo "<div class='dropdown-menu search-list'>";
          }
          echo '<div class="list-group">';
          foreach($users as $key => $value)
          {
              if(empty($key) or $key == 'closed') continue;
              $searchKey = $withSearch ? ('data-key="' . zget($usersPinYin, $value, '') . " @$key\"") : "data-key='@$key'";
              echo html::a('javascript:$(".table-actions #assignedTo").val("' . $key . '");setFormAction("' . $actionLink . '")', '<i class="icon icon-person icon-sm"></i> ' . $value, '', $searchKey);
          }
          echo "</div>";
          echo "</div>";
          ?>
        </div>
        <?php endif;?>
        <?php if($canBatchClose and $type != 'closedBy'):?>
        <?php
        $actionLink = $this->createLink('story', 'batchClose');
        $misc = "data-form-action=\"$actionLink\"";
        echo html::commonButton($lang->close, $misc);
        ?>
        <?php endif;?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
