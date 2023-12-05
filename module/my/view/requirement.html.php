<?php
/**
 * The requirement view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (https://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>

<?php js::set('mode', $mode);?>
<?php js::set('total', $pager->recTotal);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    foreach($lang->my->featureBar[$app->rawMethod]['requirement'] as $typeKey => $name)
    {
        echo html::a(inlink($app->rawMethod, "mode=requirement&type=$typeKey&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pagerID=$pageID"), "<span class='text'>{$name}</span>" . ($type == $typeKey   ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == $typeKey   ? ' btn-active-text' : '') . "'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->search->common;?></a>
  </div>
</div>
<div id="mainContent">
<div class="cell<?php if($type == 'bysearch') echo ' show';?>" id="queryBox" data-module=<?php echo ($app->rawMethod == 'contribute' ? 'contributeRequirement' : 'workRequirement');?>></div>
  <?php if(!$stories):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo sprintf($lang->my->noData, $lang->URCommon);?></span></p>
  </div>
  <?php else:?>
  <form id='myStoryForm' class="main-table table-story" data-ride="table" method="post">
    <table id='storyList' class="table has-sort-head table-fixed">
      <?php $vars = "mode=$mode&type=$type&param=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
      <?php
      $canBatchEdit     = common::hasPriv('requirement', 'batchEdit');
      $canBatchClose    = (common::hasPriv('requirement', 'batchClose') and strtolower($type) != 'closedby');
      $canBatchReview   = common::hasPriv('requirement', 'batchReview');
      $canBatchAssignTo = common::hasPriv('requirement', 'batchAssignTo');
      $canBatchAction   = ($canBatchEdit or $canBatchClose or $canBatchReview or $canBatchAssignTo);
      $URTitle          = common::checkNotCN() ? $lang->URCommon . ' ' . $lang->my->name : $lang->URCommon . $lang->my->name;
      ?>
      <thead>
        <tr>
          <th class="c-id">
            <?php if($canBatchAction):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='c-name'>      <?php common::printOrderLink('title',        $orderBy, $vars, $URTitle);?></th>
          <th class='c-pri w-40px' title=<?php echo $lang->pri;?>><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
          <th class='c-status'>    <?php common::printOrderLink('status',       $orderBy, $vars, $lang->statusAB);?></th>
          <th class='c-product'>   <?php common::printOrderLink('productTitle', $orderBy, $vars, $lang->story->product);?></th>
          <th class='c-user'>      <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->story->openedByAB);?></th>
          <th class='c-hours'>     <?php common::printOrderLink('estimate',     $orderBy, $vars, $lang->story->estimateAB);?></th>
          <th class='c-stage'>     <?php common::printOrderLink('stage',        $orderBy, $vars, $lang->story->stageAB);?></th>
          <th class='c-actions-6 text-center'> <?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($stories as $story):?>
        <?php
        $storyLink    = $this->createLink('story', 'view', "id=$story->id&version=0&param=0&storyType=requirement");
        $canBeChanged = common::canBeChanged('story', $story);
        $spanClass    = $canBatchAction ? 'c-span' : '';
        ?>
        <tr data-id='<?php echo $story->id;?>' >
          <td class="c-id">
            <?php if($canBatchAction):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='storyIdList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' <?php if(!$canBeChanged) echo 'disabled';?>/>
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $story->id);?>
          </td>
          <td class='c-name nobr <?php if(!empty($story->children)) echo "has-child" ?>'>
            <?php echo common::hasPriv('requirement', 'view') ? html::a($storyLink, $story->title, null, "style='color: $story->color' data-group='product' title='$story->title'") : "<span title='$story->title'>$story->title</span>";?>
            <?php if(!empty($story->children)) echo '<a class="story-toggle" data-id="' . $story->id . '"><i class="icon icon-angle-right"></i></a>';?>
          </td>
          <td class='c-pri'><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
          <td class='c-status'><span class='status-story status-<?php echo $story->status;?>'><?php echo $this->processStatus('story', $story);?></span></td>
          <td class='c-product' title="<?php echo $story->productTitle;?>"><?php echo $story->productTitle;?></td>
          <td class='c-user'><?php echo zget($users, $story->openedBy);?></td>
          <td class='c-hours' title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
          <td class='c-stage'><?php echo zget($lang->story->stageList, $story->stage);?></td>
          <td class='c-actions'>
            <?php
            if($canBeChanged)
            {
                $vars = "story={$story->id}";
                echo common::buildIconButton('story', 'change', "$vars&from=&storyType=requirement", $story, 'list', 'alter', '', 'iframe', true);

                if(strpos('draft,changing', $story->status) !== false)
                {
                    echo common::buildIconButton('story', 'submitReview', "$vars&storyType=requirement", $story, 'list', 'confirm', '', 'iframe', true);
                }
                else
                {
                    echo common::buildIconButton('story', 'review', "$vars&from=product&storyType=requirement", $story, 'list', 'search', '', 'iframe', true);
                }

                $title = $story->status == 'changing' ? $this->lang->story->recallChange : $this->lang->story->recall;
                echo common::buildIconButton('story', 'recall', "$vars&from=list&confirm=no&storyType=requirement", $story, 'list', 'undo', 'hiddenwin', '', '', '', $title);
                echo common::buildIconButton('story', 'edit',   "$vars&from=default&storyType=requirement", $story, 'list', '', '', 'iframe', true, "data-width='95%'");

                $storyType       = 'storyType=requirement';
                $canChange       = common::hasPriv('story', 'change', '', $storyType);
                $canSubmitReview = (strpos('draft,changing', $story->status) !== false and common::hasPriv('story', 'submitReview', '', $storyType));
                $canReview       = (strpos('draft,changing', $story->status) === false and common::hasPriv('story', 'review', '', $storyType));
                $canRecall       = common::hasPriv('story', 'recall', '', $storyType);
                $canEdit         = common::hasPriv('story', 'edit', '', $storyType);
                $canClose        = common::hasPriv('story', 'close', '', $storyType);
                if(($canChange or $canSubmitReview or $canReview or $canRecall or $canEdit) and $canClose)
                {
                    echo "<div class='dividing-line'></div>";
                }

                echo common::buildIconButton('story', 'close',  "$vars&from=&storyType=requirement", $story, 'list', '', '', 'iframe', true);
            }
            ?>
          </td>
        </tr>
        <?php if(!empty($story->children)):?>
        <?php $i = 0;?>
        <?php foreach($story->children as $key => $child):?>
        <?php $storyLink = $this->createLink('story', 'view', "id=$child->id");?>
        <?php $class  = $i == 0 ? ' table-child-top' : '';?>
        <?php $class .= ($i + 1 == count($story->children)) ? ' table-child-bottom' : '';?>
        <tr class='table-children<?php echo $class;?> parent-<?php echo $story->id;?>' data-id='<?php echo $child->id?>' data-status='<?php echo $child->status?>' data-estimate='<?php echo $child->estimate?>'>
          <td class="c-id">
            <span class="<?php echo $spanClass?>"></span>
            <?php printf('%03d', $child->id);?>
          </td>
          <td class='c-name nobr'>
            <?php echo '<span class="label label-badge label-light" title="' . $this->lang->story->children .'">SR</span> ' . (common::hasPriv('story', 'view') ? html::a($storyLink, $child->title, null, "style='color: $child->color' data-group='product' title='$child->title'") : $child->title);?>
          </td>
          <td class='c-pri'><span class='label-pri <?php echo 'label-pri-' . $child->pri;?>' title='<?php echo zget($lang->story->priList, $child->pri, $child->pri);?>'><?php echo zget($lang->story->priList, $child->pri, $child->pri);?></span></td>
          <td class='c-status'><?php echo $child->URChanged ? "<span class='status-story status-changed'>{$this->lang->story->URChanged}</span>" : "<span class='status-story status-$child->status'>" . $this->processStatus('story', $child) . '</span>'?></td>
          <td class='c-product' title="<?php echo $child->productTitle;?>"><?php echo $child->productTitle;?></td>
          <td class='c-user'><?php echo zget($users, $child->openedBy);?></td>
          <td class='c-hours'><?php echo $child->estimate . $config->hourUnit;?></td>
          <td class='c-stage'><?php echo zget($lang->story->stageList, $child->stage);?></td>
          <td class='c-actions'>
            <?php
            if($canBeChanged)
            {
                $vars = "story={$child->id}";
                if($child->URChanged)
                {
                    common::printIcon('story', 'processStoryChange', $vars, $child, 'list', 'ok');
                }
                else
                {
                    common::printIcon('story', 'change', "$vars&from=&storyType=story", $child, 'list', 'alter', '', 'iframe', true);

                    if(strpos('draft,changing', $child->status) !== false)
                    {
                        common::printIcon('story', 'submitReview', "$vars&storyType=story", $child, 'list', 'confirm', '', 'iframe', true);
                    }
                    else
                    {
                        common::printIcon('story', 'review', "$vars&from=product&storyType=story", $child, 'list', 'search', '', 'iframe', true);
                    }

                    $title = $child->status == 'changing' ? $this->lang->story->recallChange : $this->lang->story->recall;
                    common::printIcon('story', 'recall', "$vars&from=list&confirm=no&storyType=story", $child, 'list', 'undo', 'hiddenwin', '', '', '', $title);
                    common::printIcon('story', 'edit',   "$vars&from=default&storyType=story", $child, 'list');
                    $storyType       = 'storyType=story';
                    $canChange       = common::hasPriv('story', 'change', '', $storyType);
                    $canSubmitReview = (strpos('draft,changing', $child->status) !== false and common::hasPriv('story', 'submitReview', '', $storyType));
                    $canReview       = (strpos('draft,draft', $child->status) === false and common::hasPriv('story', 'review', '', $storyType));
                    $canRecall       = common::hasPriv('story', 'recall', '', $storyType);
                    $canEdit         = common::hasPriv('story', 'edit', '', $storyType);
                    $canClose        = common::hasPriv('story', 'close', '', $storyType);
                    if(($canChange or $canSubmitReview or $canReview or $canRecall or $canEdit) and $canClose)
                    {
                        echo "<div class='dividing-line'></div>";
                    }
                    common::printIcon('story', 'close',  "$vars&from=&storyType=story", $child, 'list', '', '', 'iframe', true);
                }
            }
            ?>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if($canBatchAction):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
        <?php
        if($canBatchEdit)
        {
            $actionLink = $this->createLink('story', 'batchEdit', "productID=0&executionID=0&branch=0&storyType=requirement&from={$app->rawMethod}");
            $misc       = "data-form-action='$actionLink'";
            echo html::commonButton($lang->edit, $misc);
        }
        ?>
        <?php if($canBatchReview):?>
        <div class="btn-group dropup">
          <button type='button' class='btn' data-toggle='dropdown'><?php echo $lang->story->review;?> <span class='caret'></span></button>
          <ul class='dropdown-menu'>
            <?php
            unset($lang->story->reviewResultList['']);
            unset($lang->story->reviewResultList['revert']);
            foreach($lang->story->reviewResultList as $key => $result)
            {
                $actionLink = $this->createLink('story', 'batchReview', "result=$key&reason=&storyType=requirement");
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
                        $actionLink = $this->createLink('story', 'batchReview', "result=reject&reason=$key&storyType=requirement");
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
        <?php if($canBatchAssignTo):?>
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->story->assignedTo?> <span class="caret"></span></button>
          <?php
          $withSearch = count($users) > 10;
          $actionLink = $this->createLink('story', 'batchAssignTo', 'storyType=requirement');
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
        <?php if($canBatchClose):?>
        <?php
        $actionLink = $this->createLink('story', 'batchClose', "productID=0&executionID=0&storyType=requirement&from={$app->rawMethod}");
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
