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
<?php include '../../common/view/datatable.fix.html.php';?>
<?php js::set('browseType', $browseType);?>
<div id='featurebar'>
  <ul class='nav'>
    <li>
      <div class='label-angle<?php if($moduleID) echo ' with-close';?>'>
        <?php
        echo $moduleName;
        if($moduleID)
        {
            $removeLink = $browseType == 'bymodule' ? inlink('browse', "productID=$productID&branch=$branch&browseType=$browseType&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("storyModule")';
            echo html::a($removeLink, "<i class='icon icon-remove'></i>", '', "class='text-muted'");
        }
        ?>
      </div>
    </li>
    <?php foreach(customModel::getFeatureMenu($this->moduleName, $this->methodName) as $menuItem):?>
    <?php if(isset($menuItem->hidden)) continue;?>
    <?php if(strpos($menuItem->name, 'QUERY') === 0):?>
    <?php $queryID = (int)substr($menuItem->name, 5);?>
    <li id='<?php echo $menuItem->name?>Tab'><?php echo html::a($this->inlink('browse', "productID=$productID&branch=$branch&browseType=bySearch&param=$queryID"), $menuItem->text);?></li>
    <?php else:?>
    <li id='<?php echo $menuItem->name?>Tab'><?php echo html::a($this->inlink('browse', "productID=$productID&branch=$branch&browseType=$menuItem->name"), $menuItem->text);?></li>
    <?php endif;?>
    <?php endforeach;?>
    <li id='bysearchTab'><a href='javascript:;'><i class='icon-search icon'></i> <?php echo $lang->product->searchStory;?></a></li>
  </ul>
  <div class='actions'>
    <div class='btn-group'>
      <div class='btn-group'>
        <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
          <i class='icon-download-alt'></i> <?php echo $lang->export ?>
          <span class='caret'></span>
        </button>
        <ul class='dropdown-menu' id='exportActionMenu'>
        <?php 
        $misc = common::hasPriv('story', 'export') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('story', 'export') ?  $this->createLink('story', 'export', "productID=$productID&orderBy=$orderBy") : '#';
        echo "<li>" . html::a($link, $lang->story->export, '', $misc) . "</li>";
        ?>
        </ul>
      </div>
        <?php common::printIcon('story', 'report', "productID=$productID&browseType=$browseType&branchID=$branch&moduleID=$moduleID", '', 'button', 'bar-chart'); ?>
    </div>
    <div class="btn-group" id='createActionMenu'>
      <?php
      if(commonModel::isTutorialMode())
      {
          $wizardParams = helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID");
          echo html::a($this->createLink('tutorial', 'wizard', "module=story&method=create&params=$wizardParams"), "<i class='con-plus'></i>" . $lang->story->create, '', "class='create-story-btn btn btn-primary'");
      }
      else
      {
          $misc = common::hasPriv('story', 'create') ? "class='btn btn-primary'" : "class='btn btn-primary disabled'";
          $link = common::hasPriv('story', 'create') ?  $this->createLink('story', 'create', "productID=$productID&branch=$branch&moduleID=$moduleID") : '#';
          echo html::a($link, "<i class='icon icon-plus'></i>" . $lang->story->create, '', $misc);
      }
      ?>
      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
      </button>
      <ul class='dropdown-menu pull-right'>
      <?php
      $misc = common::hasPriv('story', 'batchCreate') ? '' : "class=disabled";
      $link = common::hasPriv('story', 'batchCreate') ?  $this->createLink('story', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID") : '#';
      echo "<li>" . html::a($link, $lang->story->batchCreate, '', $misc) . "</li>";
      ?>
      </ul>
    </div>
  </div>
  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>
<div class='side' id='treebox'>
  <a class='side-handle' data-id='productTree'><i class='icon-caret-left'></i></a>
  <div class='side-body'>
    <div class='panel panel-sm'>
      <div class='panel-heading nobr'><?php echo html::icon($lang->icons['product']);?> <strong><?php echo $branch ? $branches[$branch] : $productName;?></strong></div>
      <div class='panel-body'>
        <?php echo $moduleTree;?>
        <div class='text-right'>
          <?php common::printLink('tree', 'browse', "rootID=$productID&view=story", $lang->tree->manage);?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class='main'>
  <script>setTreeBox();</script>
  <form method='post' id='productStoryForm'>
    <?php
    $datatableId  = $this->moduleName . ucfirst($this->methodName);
    $useDatatable = (isset($this->config->datatable->$datatableId->mode) and $this->config->datatable->$datatableId->mode == 'datatable');
    $file2Include = $useDatatable ? dirname(__FILE__) . '/datatabledata.html.php' : dirname(__FILE__) . '/browsedata.html.php';
    $vars         = "productID=$productID&branch=$branch&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
    include $file2Include;
    ?>
      <tfoot>
      <tr>
        <td colspan='13'>
          <div class='table-actions clearfix'>
            <?php if(count($stories)):?>
            <?php echo html::selectButton();?>
            <?php
            $canBatchEdit  = common::hasPriv('story', 'batchEdit');
            $disabled   = $canBatchEdit ? '' : "disabled='disabled'";
            $actionLink = $this->createLink('story', 'batchEdit', "productID=$productID&projectID=0&branch=$branch");
            ?>
            <div class='btn-group dropup'>
              <?php echo html::commonButton($lang->edit, "onclick=\"setFormAction('$actionLink')\" $disabled");?>
              <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
              <ul class='dropdown-menu'>
                <?php 
                $class = "class='disabled'";

                $canBatchClose = common::hasPriv('story', 'batchClose') && strtolower($browseType) != 'closedbyme' && strtolower($browseType) != 'closedstory';
                $actionLink    = $this->createLink('story', 'batchClose', "productID=$productID&projectID=0");
                $misc = $canBatchClose ? "onclick=\"setFormAction('$actionLink')\"" : $class;
                echo "<li>" . html::a('#', $lang->close, '', $misc) . "</li>";

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

                if(common::hasPriv('story', 'batchChangeBranch') and $this->session->currentProductType != 'normal')
                {
                    $withSearch = count($branches) > 8;
                    echo "<li class='dropdown-submenu'>";
                    echo html::a('javascript:;', $lang->product->branchName[$this->session->currentProductType], '', "id='branchItem'");
                    echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                    echo "<ul class='dropdown-list'>";
                    foreach($branches as $branchID => $branchName)
                    {
                        $actionLink = $this->createLink('story', 'batchChangeBranch', "branchID=$branchID");
                        echo "<li class='option' data-key='$branchID'>" . html::a('#', $branchName, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"") . "</li>";
                    }
                    echo '</ul>';
                    if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                    echo '</div></li>';
                }

                if(common::hasPriv('story', 'batchChangeModule'))
                {
                    $withSearch = count($modules) > 8;
                    echo "<li class='dropdown-submenu'>";
                    echo html::a('javascript:;', $lang->story->moduleAB, '', "id='moduleItem'");
                    echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                    echo "<ul class='dropdown-list'>";
                    foreach($modules as $moduleId => $module)
                    {
                        $actionLink = $this->createLink('story', 'batchChangeModule', "moduleID=$moduleId");
                        echo "<li class='option' data-key='$moduleID'>" . html::a('#', $module, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
                    }
                    echo '</ul>';
                    if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                    echo '</div></li>';
                }
                else
                {
                    echo '<li>' . html::a('javascript:;', $lang->story->moduleAB, '', $class) . '</li>';
                }

                if(common::hasPriv('story', 'batchChangePlan'))
                {
                    unset($plans['']);
                    $plans      = array(0 => $lang->null) + $plans;
                    $withSearch = count($plans) > 8;
                    echo "<li class='dropdown-submenu'>";
                    echo html::a('javascript:;', $lang->story->planAB, '', "id='planItem'");
                    echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                    echo "<ul class='dropdown-list'>";
                    foreach($plans as $planID => $plan)
                    {
                        $actionLink = $this->createLink('story', 'batchChangePlan', "planID=$planID");
                        echo "<li class='option' data-key='$planID'>" . html::a('#', $plan, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
                    }
                    echo '</ul>';
                    if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                    echo '</div></li>';
                }
                else
                {
                    echo '<li>' . html::a('javascript:;', $lang->story->planAB, '', $class) . '</li>';
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

                if(common::hasPriv('story', 'batchAssignTo'))
                {
                      $withSearch = count($users) > 10;
                      $actionLink = $this->createLink('story', 'batchAssignTo', "productID=$productID");
                      echo "<li class='dropdown-submenu'>";
                      echo html::select('assignedTo', $users, '', 'class="hidden"');
                      echo html::a('javascript::', $lang->story->assignedTo, '', 'id="assignItem"');
                      echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                      echo '<ul class="dropdown-list">';
                      foreach ($users as $key => $value)
                      {
                          if(empty($key) or $key == 'closed') continue;
                          echo "<li class='option' data-key='$key'>" . html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\")", $value, '', '') . '</li>';
                      }
                      echo "</ul>";
                      if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control'><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                      echo "</div></li>";
                }
                else
                {
                    echo '<li>' . html::a('javascript:;', $lang->story->assignedTo, '', $class) . '</li>';
                }
                ?>
              </ul>
            </div>
            <?php endif; ?>
            <div class='text'><?php echo $summary;?></div>
          </div>
          <?php $pager->show();?>
        </td>
      </tr>
      </tfoot>
    </table>
  </form>
</div>
<script language='javascript'>
var moduleID = <?php echo $moduleID?>;
$('#module<?php echo $moduleID;?>').addClass('active');
$('#<?php echo ($browseType == 'bymodule' and $this->session->storyBrowseType == 'bysearch') ? 'all' : $this->session->storyBrowseType;?>Tab').addClass('active');
<?php if($browseType == 'bysearch'):?>
$shortcut = $('#QUERY<?php echo (int)$param;?>Tab');
if($shortcut.size() > 0)
{
    $shortcut.addClass('active');
    $('#bysearchTab').removeClass('active');
    $('#querybox').removeClass('show');
}
<?php endif;?>
<?php if(isset($this->config->product->homepage) and $this->config->product->homepage != 'browse'):?>
$('#modulemenu .nav li.right:last').after("<li class='right'><a style='font-size:12px' href='javascript:setHomepage(\"product\", \"browse\")'><i class='icon icon-cog'></i> <?php echo $lang->homepage?></a></li>")
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
