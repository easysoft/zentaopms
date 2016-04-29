<?php
/**
 * The task view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: task.html.php 4894 2013-06-25 01:28:39Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datepicker.html.php';
include '../../common/view/datatable.fix.html.php';
include './taskheader.html.php';
js::set('moduleID', $moduleID);
js::set('productID', $productID);
js::set('browseType', $browseType);
?>
<div class='sub-featurebar'>
  <ul class='nav'>
    <li style='padding-top:5px;'>
      <span>
        <?php
        if($productID)
        {
            $product    = $this->product->getById($productID);
            $removeLink = $browseType == 'byproduct' ? inlink('task', "projectID=$projectID&browseType=$status&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("productBrowseParam")';
            echo $product->name;
            echo '&nbsp;' . html::a($removeLink, "<i class='icon icon-remove'></i>") . '&nbsp;';
        }
        elseif($moduleID)
        {
            $module     = $this->tree->getById($moduleID);
            $removeLink = $browseType == 'bymodule' ? inlink('task', "projectID=$projectID&browseType=$status&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("moduleBrowseParam")';
            echo $module->name;
            echo '&nbsp;' . html::a($removeLink, "<i class='icon icon-remove'></i>") . '&nbsp;';
        }
        else
        {
            echo $this->lang->tree->all;
        }
        echo " <i class='icon-angle-right'></i>&nbsp; ";
        ?>
      </span>
    </li>
    <?php foreach(customModel::getFeatureMenu($this->moduleName, $this->methodName) as $menuItem):?>
    <?php
    if(isset($menuItem->hidden)) continue;
    $type = $menuItem->name;
    if(strpos($type, 'QUERY') === 0)
    {
        $queryID = (int)substr($type, 5);
        echo "<li id='{$type}Tab'>" . html::a(inlink('task', "project=$projectID&type=bySearch&param=$queryID"), $menuItem->text) . '</li>' ;
    }
    elseif($type != 'status')
    {
        echo "<li id='{$type}Tab'>" . html::a(inlink('task', "project=$projectID&type=$type"), $menuItem->text) . '</li>' ;
    }
    elseif($type == 'status')
    {
        echo "<li id='statusTab' class='dropdown'>";
        $taskBrowseType = isset($status) ? $this->session->taskBrowseType : '';
        $current        = zget($lang->project->statusSelects, $taskBrowseType, '');
        if(empty($current)) $current = $menuItem->text;
        echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown'");
        echo "<ul class='dropdown-menu'>";
        foreach ($lang->project->statusSelects as $key => $value)
        {
            if($key == '') continue;
            echo '<li' . ($key == $taskBrowseType ? " class='active'" : '') . '>';
            echo html::a($this->createLink('project', 'task', "project=$projectID&type=$key"), $value);
        }
        echo '</ul></li>';
    }
    ?>
    <?php endforeach;?>
    <?php echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->project->byQuery}</a></li> ";?>
  </ul>
  <div id='querybox' class='<?php if($browseType == 'bysearch') echo 'show';?>'></div>
</div>

<div class='side' id='taskTree'>
  <a class='side-handle' data-id='projectTree'><i class='icon-caret-left'></i></a>
  <div class='side-body'>
    <div class='panel panel-sm'>
      <div class='panel-heading nobr'>
        <?php echo html::icon($lang->icons['project']);?> <strong><?php echo $project->name;?></strong>
      </div>
      <div class='panel-body'>
        <?php echo $moduleTree;?>
        <div class='text-right'>
          <?php common::printLink('project', 'edit',    "projectID=$projectID", $lang->edit);?>
          <?php common::printLink('project', 'delete',  "projectID=$projectID&confirm=no", $lang->delete, 'hiddenwin');?>
          <?php common::printLink('tree', 'browsetask', "rootID=$projectID&productID=0", $lang->tree->manage);?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class='main'>
  <form method='post' id='projectTaskForm'>
    <?php
    $datatableId  = $this->moduleName . $this->methodName;
    $useDatatable = (isset($this->config->datatable->$datatableId->mode) and $this->config->datatable->$datatableId->mode == 'datatable');
    $file2Include = $useDatatable ? dirname(__FILE__) . '/datatabledata.html.php' : dirname(__FILE__) . '/taskdata.html.php';
    $vars         = "projectID=$project->id&status=$status&parma=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage";
    include $file2Include;
    ?>
      <tfoot>
        <tr>
          <?php $columns = ($this->cookie->windowWidth > $this->config->wideSize ? 14 : 12) - ($project->type == 'sprint' ? 0 : 1);?>
          <td colspan='<?php echo $columns;?>'>
            <div class='table-actions clearfix'>
            <?php 
            $canBatchEdit         = common::hasPriv('task', 'batchEdit');
            $canBatchClose        = (common::hasPriv('task', 'batchClose') && strtolower($browseType) != 'closedBy');
            $canBatchChangeModule = common::hasPriv('task', 'batchChangeModule');
            $canBatchAssignTo     = common::hasPriv('task', 'batchAssignTo');
            if(count($tasks))
            {
                echo html::selectButton();

                $actionLink = $this->createLink('task', 'batchEdit', "projectID=$projectID");
                $misc       = $canBatchEdit ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";

                echo "<div class='btn-group dropup'>";
                echo html::commonButton($lang->edit, $misc);
                echo "<button id='moreAction' type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
                echo "<ul class='dropdown-menu' id='moreActionMenu'>";
                $actionLink = $this->createLink('task', 'batchClose');
                $misc = $canBatchClose ? "onclick=\"setFormAction('$actionLink','hiddenwin')\"" : "class='disabled'";
                echo "<li>" . html::a('#', $lang->close, '', $misc) . "</li>";

                if($canBatchChangeModule)
                {
                    $withSearch = count($modules) > 10;
                    echo "<li class='dropdown-submenu'>";
                    echo html::a('javascript:;', $lang->task->moduleAB, '', "id='moduleItem'");
                    echo "<ul class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                    foreach($modules as $moduleId => $module)
                    {
                        $actionLink = $this->createLink('task', 'batchChangeModule', "moduleID=$moduleId");
                        echo "<li class='option' data-key='$moduleID'>" . html::a('#', $module, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
                    }
                    if($withSearch) echo "<li class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></li>";
                    echo '</ul></li>';
                }
                else
                {
                    echo '<li>' . html::a('javascript:;', $lang->task->moduleAB, '', $class) . '</li>';
                }

                /* Batch assign. */
                if($canBatchAssignTo)
                {
                    $withSearch = count($memberPairs) > 10;
                    $actionLink = $this->createLink('task', 'batchAssignTo', "projectID=$projectID");
                    echo html::select('assignedTo', $memberPairs, '', 'class="hidden"');
                    echo "<li class='dropdown-submenu'>";
                    echo html::a('javascript::', $lang->task->assignedTo, 'id="assignItem"');
                    echo "<ul class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                    foreach ($memberPairs as $key => $value)
                    {
                        if(empty($key)) continue;
                        echo "<li class='option' data-key='$key'>" . html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\")", $value, '', '') . '</li>';
                    }
                    if($withSearch) echo "<li class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></li>";
                    echo "</ul>";
                    echo "</li>";
                }
                echo "</ul></div>";
            }
            echo "<div class='text'>" . $summary . "</div>";
            ?>
            </div>
            <?php $pager->show();?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php js::set('replaceID', 'taskList')?>
<script language='javascript'>
$('#project<?php echo $projectID;?>').addClass('active')
$('#listTab').addClass('active')
$('#<?php echo ($browseType == 'bymodule' and $this->session->taskBrowseType == 'bysearch') ? 'all' : $this->session->taskBrowseType;?>Tab').addClass('active');
<?php if($browseType == 'bysearch'):?>
$shortcut = $('#QUERY<?php echo (int)$param;?>Tab');
if($shortcut.size() > 0)
{
    $shortcut.addClass('active');
    $('#bysearchTab').removeClass('active');
    $('#querybox').removeClass('show');
}
<?php endif;?>
statusActive = '<?php echo isset($lang->project->statusSelects[$this->session->taskBrowseType]);?>';
if(statusActive) $('#statusTab').addClass('active')
<?php if($this->config->project->homepage != 'browse'):?>
$('#modulemenu .nav li.right:last').after("<li class='right'><a href='javascript:setHomepage(\"project\", \"browse\")'><i class='icon icon-cog'></i><?php echo $lang->homepage?></a></li>")
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
