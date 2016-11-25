<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4909 2013-06-26 07:23:50Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../testcase/view/caseheader.html.php';?>
<div class='main'>
  <form method='post' id='productStoryForm'>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable' id='storyList'>
      <thead>
      <tr>
        <?php $vars = "productID=$productID&orderBy=%s";?>
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
      <tr class='text-center'>
        <td class='cell-id'>
          <input type='checkbox' name='storyIDList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' /> 
          <?php if($canView) echo html::a($viewLink, sprintf('%03d', $story->id)); else printf('%03d', $story->id);?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
        <td class='text-left' title="<?php echo $story->title?>"><nobr><?php echo html::a($viewLink, $story->title);?></nobr></td>
        <td title="<?php echo $story->planTitle?>"><?php echo $story->planTitle;?></td>
        <td><?php echo $lang->story->sourceList[$story->source];?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $users[$story->assignedTo];?></td>
        <td><?php echo $story->estimate;?></td>
        <td class='status-<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
        <td><?php echo $lang->story->stageList[$story->stage];?></td>
        <td class='text-right'>
          <?php 
          $vars = "story={$story->id}";
          common::printIcon('story', 'change',     $vars, $story, 'list', 'random');
          common::printIcon('story', 'review',     $vars, $story, 'list', 'search');
          common::printIcon('story', 'close',      $vars, $story, 'list', 'off');
          common::printIcon('story', 'edit',       $vars, $story, 'list', 'pencil');
          common::printIcon('story', 'createCase', "productID=$story->product&module=0&from=&param=0&$vars", $story, 'list', 'sitemap');
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
      <tr>
        <td colspan='11'>
          <div class='table-actions clearfix' style='padding-left: 5px'>
            <?php if(count($stories)):?>
            <?php echo html::selectButton();?>
            <?php
            $canBatchEdit  = common::hasPriv('story', 'batchEdit');
            $disabled   = $canBatchEdit ? '' : "disabled='disabled'";
            $actionLink = $this->createLink('story', 'batchEdit', "productID=$productID&projectID=0");
            ?>
            <div class='btn-group dropup'>
              <?php echo html::commonButton($lang->edit, "onclick=\"setFormAction('$actionLink')\" $disabled");?>
              <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
              <ul class='dropdown-menu'>
                <?php 
                $class = "class='disabled'";

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

                if(common::hasPriv('story', 'batchChangeStage'))
                {
                    echo "<li class='dropdown-submenu'>";
                    echo html::a('javascript:;', $lang->story->stageAB, '', "id='stageItem'");
                    echo "<ul class='dropdown-menu'>";
                    $lang->story->stageList[''] = $lang->null;
                    foreach($lang->story->stageList as $key => $stage)
                    {
                        $actionLink = $this->createLink('story', 'batchChangeStage', "stage=$key");
                        echo "<li>" . html::a('#', $stage, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
                    }
                    echo '</ul></li>';
                }
                else
                {
                    echo '<li>' . html::a('javascript:;', $lang->story->stageAB, '', $class) . '</li>';
                }
                ?>
              </ul>
            </div>
            <?php endif; ?>
          </div>
        </td>
      </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
