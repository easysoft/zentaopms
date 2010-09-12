<?php
/**
 * The edit file of bug module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/autocomplete.html.php';?>
<?php include '../../common/view/alert.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<style>
#product, #module, #project, #story, #task, #resolvedBuild{width:220px}
#steps {width:100%}
.select-3 {width:220px}
.text-3   {width:215px}
</style>
<script language='Javascript'>
changeProductConfirmed = false;
changeProjectConfirmed = false;
oldProjectID = '<?php echo $bug->project;?>';
oldStoryID   = '<?php echo $bug->story;?>';
oldTaskID    = '<?php echo $bug->task;?>';
oldOpenedBuild   = '<?php echo $bug->openedBuild;?>';
oldResolvedBuild = '<?php echo $bug->resolvedBuild;?>';
emptySelect  = "<select name='task' id='task'><option value=''></option></select>";
/* 当选择产品时，触发这个方法。*/
function loadAll(productID)
{
    if(!changeProductConfirmed)
    {
         firstChoice = confirm('<?php echo $lang->bug->confirmChangeProduct;?>');
         changeProductConfirmed = true;    // 已经提示过，下次就不再提示了。
    }
    if(changeProductConfirmed || firstChoice)
    {
        $('#taskIdBox').get(0).innerHTML = emptySelect;
        loadModuleMenu(productID);      // 加载产品的模块列表。
        loadProductStories(productID);  // 加载产品的需求列表。
        loadProductProjects(productID); // 加载项目列表。
        loadProductBuilds(productID);   // 加载build列表。
    }
}

/* 加载模块列表。*/
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug');
    $('#moduleIdBox').load(link);
}

/* 加载产品的需求列表。*/
function loadProductStories(productID)
{
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&moduleId=0&storyID=' + oldStoryID);
    $('#storyIdBox').load(link);
}

/* 加载项目列表。*/
function loadProductProjects(productID)
{
    link = createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&projectID=' + oldProjectID);
    $('#projectIdBox').load(link);
}

/* 加载产品build列表。*/
function loadProductBuilds(productID)
{
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);
    $('#openedBuildBox').load(link);
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild);
    $('#resolvedBuildBox').load(link);
}

/* 加载项目的任务列表和需求列表。*/
function loadProjectRelated(projectID)
{
    if(projectID)
    {
        loadProjectTasks(projectID);
        loadProjectStories(projectID);
        loadProjectBuilds(projectID);
    }
    else
    {
        $('#taskIdBox').get(0).innerHTML = emptySelect;
        loadProductStories($('#product').get(0).value);
    }
}

/* 加载项目的任务列表。*/
function loadProjectTasks(projectID)
{
    link = createLink('task', 'ajaxGetProjectTasks', 'projectID=' + projectID + '&taskID=' + oldTaskID);
    $('#taskIdBox').load(link);
}

/* 加载项目的需求列表。*/
function loadProjectStories(projectID)
{
    productID = $('#product').get(0).value; 
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=' + productID + '&storyID=' + oldStoryID);
    $('#storyIdBox').load(link);
}

/* 加载项目的build列表。*/
function loadProjectBuilds(projectID)
{
    productID = $('#product').val();
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);
    $('#openedBuildBox').load(link);
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild);
    $('#resolvedBuildBox').load(link);
}

/* 设置重复bug的显示。*/
function setDuplicate(resolution)
{
    if(resolution == 'duplicate')
    {
        $('#duplicateBugBox').show();
    }
    else
    {
        $('#duplicateBugBox').hide();
    }
}

var userList = "<?php echo join(',', array_keys($users));?>".split(',');
$(function() {
    $("#mailto").autocomplete(userList, { multiple: true, mustMatch: true});
    $("#searchStories").colorbox({width:680, height:400, iframe:true, transition:'none'});
    $("#searchTasks").colorbox({width:680, height:400, iframe:true, transition:'none'});
    KE.show({id:'steps', items:simpleTools});

});
</script>
<form method='post' target='hiddenwin' enctype='multipart/form-data'>
<div class='yui-d0'>
  <div id='titlebar'>
    <div id='main'>
    BUG #<?php echo $bug->id . $lang->colon;?>
    <?php echo html::input('title', str_replace("'","&#039;",$bug->title), 'class=text-1');?>
    </div>
    <div><?php echo html::submitButton()?></div>
  </div>
</div>

<div class='yui-d0 yui-t8'>
  <div class='yui-main'>
    <div class='yui-b'>
      <table class='table-1 bd-none'>
        <tr class='bd-none'><td class='bd-none'>
          <fieldset>
            <legend><?php echo $lang->bug->legendSteps;?></legend>
            <div class='w-p90'><?php echo html::textarea('steps', $bug->steps, "rows='12'");?></div>
          </fieldset>
          <fieldset>
          <legend><?php echo $lang->bug->legendComment;?></legend>
            <?php echo html::textarea('comment', '', "rows='6' class='area-1'");?>
          </fieldset>
          <fieldset>
          <legend><?php echo $lang->bug->legendAttatch;?></legend>
          <?php echo $this->fetch('file', 'buildform', 'filecount=2');?>
          </fieldset>
          <div class='a-center'>
            <?php 
            echo html::submitButton();
            $browseLink = $app->session->bugList != false ? $app->session->bugList : inlink('browse', "productID=$bug->product");
            echo html::linkButton($lang->goback, $browseLink);
            ?>
          </div>
        </td></tr>
      </table>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>

  <div class='yui-b'>
    <fieldset>
      <legend><?php echo $lang->bug->legendBasicInfo;?></legend>
      <table class='table-1 a-left' cellpadding='0' cellspacing='0'>
        <tr>
          <td class='rowhead w-p20'><?php echo $lang->bug->product;?></td>
          <td>
            <?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value);");?>
          </td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->module;?></td>
          <td>
            <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $currentModuleID);?></span>
          </td>
        </tr>

        <tr>
          <td class='rowhead'><?php echo $lang->bug->type;?></td>
          <td><?php echo html::select('type', $lang->bug->typeList, $bug->type, 'class=select-3');?>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->severity;?></td>
          <td><?php echo html::select('severity', $lang->bug->severityList, $bug->severity, 'class=select-3');?>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->pri;?></td>
          <td><?php echo html::select('pri', $lang->bug->priList, $bug->pri, 'class=select-3');?>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->status;?></td>
          <td><?php echo html::select('status', $lang->bug->statusList, $bug->status, 'class=select-3');?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->assignedTo;?></td>
          <td><?php echo html::select('assignedTo', $users, $bug->assignedTo, 'class=select-3');?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->os;?></td>
          <td><?php echo html::select('os', $lang->bug->osList, $bug->os, 'class=select-3');?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->browser;?></td>
          <td><?php echo html::select('browser', $lang->bug->browserList, $bug->browser, 'class=select-3');?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->keywords;?></td>
          <td><?php echo html::input('keywords', $bug->keywords, 'class="text-3"');?></td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend><?php echo $lang->bug->legendPrjStoryTask;?></legend>
      <table class='table-1 a-left'>
        <tr>
          <td class='rowhead w-p20'><?php echo $lang->bug->project;?></td>
          <td><span id='projectIdBox'><?php echo html::select('project', $projects, $bug->project, 'class=select-3 onchange=loadProjectRelated(this.value)');?></span></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->story;?></td>
          <td><span id='storyIdBox'><?php echo html::select('story', $stories, $bug->story, "class=select-3");?></span>
          <?php echo html::a($this->createLink('search', 'select', "productID=$productID&projectID=$bug->project&module=story&storyID=$bug->story"), $lang->go, "_blank", "class='search' id='searchStories'");?>
          </td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->task;?></td>
          <td><span id='taskIdBox'><?php echo html::select('task', $tasks, $bug->task, 'class=select-3');?></span>
          <?php echo html::a($this->createLink('search', 'select', "productID=$productID&projectID=$bug->project&module=task&taskID=$bug->task"), $lang->go, "_blank", "class='search' id='searchTasks'");?>
          </td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend><?php echo $lang->bug->legendLife;?></legend>
      <table class='table-1 a-left'>
        <tr>
          <td class='rowhead w-p20'><?php echo $lang->bug->openedBy;?></td>
          <td><?php echo $users[$bug->openedBy];?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->openedBuild;?></td>
          <td><span id='openedBuildBox'><?php echo html::select('openedBuild[]', $openedBuilds, $bug->openedBuild, 'size=4 multiple=multiple class=select-3');?></span></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->resolvedBy;?></td>
          <td><?php echo html::select('resolvedBy', $users, $bug->resolvedBy, 'class=select-3');?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->resolvedDate;?></td>
          <td><?php echo html::input('resolvedDate', $bug->resolvedDate, 'class=text-3');?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->resolvedBuild;?></td>
          <td><span id='resolvedBuildBox'><?php echo html::select('resolvedBuild', $resolvedBuilds, $bug->resolvedBuild, 'class=select-3');?></span></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->resolution;?></td>
          <td><?php echo html::select('resolution', $lang->bug->resolutionList, $bug->resolution, 'class=select-3 onchange=setDuplicate(this.value)');?></td>
        </tr>
        <tr id='duplicateBugBox' <?php if($bug->resolution != 'duplicate') echo "style='display:none'";?>>
          <td class='rowhead'><?php echo $lang->bug->duplicateBug;?></td>
          <td><?php echo html::input('duplicateBug', $bug->duplicateBug, 'class=text-3');?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->closedBy;?></td>
          <td><?php echo html::select('closedBy', $users, $bug->closedBy, 'class=select-3');?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->closedDate;?></td>
          <td><?php echo html::input('closedDate', $bug->closedDate, 'class=text-3');?></td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->bug->legendMisc;?></legend>
      <table class='table-1 a-left'>
        <tr>
          <td class='rowhead w-p20'><?php echo $lang->bug->mailto;?></td>
          <td><?php echo html::input('mailto', $bug->mailto, 'class="text-3"');?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->linkBug;?></td>
          <td><?php echo html::input('linkBug', $bug->linkBug, 'class="text-3"');?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->case;?></td>
          <td><?php echo html::input('case', $bug->case, 'class="text-3"');?></td>
        </tr>
      </table>
    </fieldset>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
