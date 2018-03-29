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
<style>
.table-actions.btn-toolbar{overflow:visible;}
</style>
<main id="main">
  <div class="container">
    <div id="mainMenu" class="clearfix">
      <div class="btn-toolbar pull-left">
        <?php
        echo html::a(inlink('story', "type=assignedTo"),  "<span class='text'>{$lang->my->storyMenu->assignedToMe}</span>", '', "class='btn btn-link" . ($type == 'assignedTo' ? ' btn-active-text' : '') . "'");
        echo html::a(inlink('story', "type=openedBy"),    "<span class='text'>{$lang->my->storyMenu->openedByMe}</span>",   '', "class='btn btn-link" . ($type == 'openedBy'   ? ' btn-active-text' : '') . "'");
        echo html::a(inlink('story', "type=reviewedBy"),  "<span class='text'>{$lang->my->storyMenu->reviewedByMe}</span>", '', "class='btn btn-link" . ($type == 'reviewedBy' ? ' btn-active-text' : '') . "'");
        echo html::a(inlink('story', "type=closedBy"),    "<span class='text'>{$lang->my->storyMenu->closedByMe}</span>",   '', "class='btn btn-link" . ($type == 'closedBy'   ? ' btn-active-text' : '') . "'");
        ?>
      </div>
    </div>
    <div id="mainContent">
      <form id='myStoryForm' class="main-table table-story" data-ride="table" method="post">
        <table class="table has-sort-head table-lg table-fixed">
          <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
          <?php
          $canBatchEdit  = common::hasPriv('story', 'batchEdit');
          $canBatchClose = (common::hasPriv('story', 'batchClose') && strtolower($type) != 'closedbyme');
          ?>
          <thead>
            <tr>
              <th class="w-100px">
                <?php if($canBatchEdit or $canBatchClose):?>
                <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                  <label></label>
                </div>
                <?php endif;?>
                <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
              </th>
              <th class='w-pri'>   <?php common::printOrderLink('pri',          $orderBy, $vars, $lang->priAB);?></th>
              <th class='w-200px'> <?php common::printOrderLink('productTitle', $orderBy, $vars, $lang->story->product);?></th>
              <th>                 <?php common::printOrderLink('title',        $orderBy, $vars, $lang->story->title);?></th>
              <th class='w-150px'> <?php common::printOrderLink('plan',         $orderBy, $vars, $lang->story->plan);?></th>
              <th class='w-user'>  <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
              <th class='w-hour'>  <?php common::printOrderLink('estimate',     $orderBy, $vars, $lang->story->estimateAB);?></th>
              <th class='w-status'><?php common::printOrderLink('status',       $orderBy, $vars, $lang->statusAB);?></th>
              <th class='w-100px'> <?php common::printOrderLink('stage',        $orderBy, $vars, $lang->story->stageAB);?></th>
              <th class='w-200px'> <?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($stories as $story):?>
            <?php $storyLink = $this->createLink('story', 'view', "id=$story->id");?>
            <tr>
              <td class="c-id">
                <div class="checkbox-primary">
                  <?php if($canBatchEdit or $canBatchClose):?>
                  <input type='checkbox' name='storyIDList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' /> 
                  <label></label>
                  <?php endif;?>
                  <?php printf('%03d', $story->id);?>
                </div>
              </td>
              <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
              <td><?php echo $story->productTitle;?></td>
              <td class='text-left nobr'><?php echo html::a($storyLink, $story->title, null, "style='color: $story->color'");?></td>
              <td><?php echo $story->planTitle;?></td>
              <td><?php echo $users[$story->openedBy];?></td>
              <td><?php echo $story->estimate;?></td>
              <td class='story-<?php echo $story->status;?>'><?php echo zget($lang->story->statusList, $story->status);?></td>
              <td><?php echo zget($lang->story->stageList, $story->stage);?></td>
              <td class='c-actions'>
                <?php
                common::printIcon('story', 'change',     "storyID=$story->id", $story, 'list', 'random');
                common::printIcon('story', 'review',     "storyID=$story->id", $story, 'list', 'search');
                common::printIcon('story', 'close',      "storyID=$story->id", $story, 'list', 'off', '', 'iframe', true);
                common::printIcon('story', 'edit',       "storyID=$story->id", $story, 'list');
                common::printIcon('story', 'createCase', "productID=$story->product&moduleID=0&from=&param=0&storyID=$story->id", '', 'list', 'sitemap');
                ?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <?php if($stories):?>
        <div class="table-footer">
          <?php if($canBatchEdit or $canBatchClose):?>
          <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
          <?php endif;?>
          <div class="table-actions btn-toolbar">
            <div class='btn-group dropup'>
              <?php
              $actionLink = $this->createLink('story', 'batchEdit');
              $misc       = $canBatchEdit ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
              echo html::commonButton($lang->edit, $misc);
              ?>
              <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
              <ul class='dropdown-menu'>
                <?php
                $class = "class='disabled'";
                $actionLink = $this->createLink('story', 'batchClose');
                $misc = ($canBatchClose and $type != 'closedBy') ? "onclick=\"setFormAction('$actionLink')\"" : $class;
                if($misc) echo "<li>" . html::a('javascript:;', $lang->close, '', $misc) . "</li>";

                if(common::hasPriv('story', 'batchReview'))
                {
                    echo "<li class='dropdown-submenu'>";
                    echo html::a('javascript:;', $lang->story->review, '', "id='reviewItem'");
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
                                echo html::a('#', $reason, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
                                echo "</li>";
                            }
                            echo '</ul></li>';
                        }
                        else
                        {
                          echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . '</li>';
                        }
                    }
                    echo '</ul></li>';
                }
                else
                {
                    echo '<li>' . html::a('javascript:;', $lang->story->review,  '', $class) . '</li>';
                }

                if(common::hasPriv('story', 'batchAssignTo'))
                {
                      $withSearch = count($users) > 10;
                      $actionLink = $this->createLink('story', 'batchAssignTo');
                      echo html::select('assignedTo', $users, '', 'class="hidden"');
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript::', $lang->story->assignedTo, '', 'id="assignItem"');
                      echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                      echo '<ul class="dropdown-list">';
                      foreach ($users as $key => $value)
                      {
                          if(empty($key) or $key == 'closed') continue;
                          echo "<li class='option' data-key='$key'>" . html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\")", $value, '', '') . '</li>';
                      }
                      echo "</ul>";
                      if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                      echo "</div></li>";
                }
                else
                {
                    echo '<li>' . html::a('javascript:;', $lang->story->assignedTo, '', $class) . '</li>';
                }
                ?>
              </ul>
            </div>
          </div>
          <?php $pager->show('right', 'pagerjs');?>
        </div>
        <?php endif;?>
      </form>
    </div>
  </div>
</main>
<?php include '../../common/view/footer.html.php';?>
