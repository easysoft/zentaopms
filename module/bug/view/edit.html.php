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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<style>#project, #story, #task{width:245px}</style>
<script language='Javascript'>
changeProductConfirmed = false;
changeProjectConfirmed = false;
oldProjectID = '<?php echo $bug->project;?>';
oldStoryID   = '<?php echo $bug->story;?>';
oldTaskID    = '<?php echo $bug->task;?>';
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
        loadProjects(productID);        // 加载项目列表。
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
function loadProjects(productID)
{
    link = createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&projectID=' + oldProjectID);
    $('#projectIdBox').load(link);
}

/* 加载项目的任务列表和需求列表。*/
function loadProjectStoriesAndTasks(projectID)
{
    if(projectID)
    {
        loadProjectTasks(projectID);
        loadProjectStories(projectID);
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
</script>
<form method='post' target='hiddenwin' enctype='multipart/form-data'>
<div class='yui-d0'>
  <div id='titlebar'>
    <div id='main'>
    BUG #<?php echo $bug->id . $lang->colon;?>
    <?php echo html::input('title', $bug->title, 'class=text-1');?>
    </div>
    <div><?php echo html::submitButton()?></div>
  </div>
</div>

<div class='yui-doc3 yui-t7'>
  <div class='yui-g'>  

    <div class='yui-u first'>  
      <fieldset>
        <legend><?php echo $lang->bug->legendBasicInfo;?></legend>
        <table class='table-1 a-left' cellpadding='0' cellspacing='0'>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->labProductAndModule;?></td>
            <td>
              <?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value); class='select-2'");?>
              <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $currentModuleID);?></span>
            </td>
          </tr>

          <tr>
            <td class='rowhead'><?php echo $lang->bug->type;?></td>
            <td><?php echo html::select('type', (array)$lang->bug->typeList, $bug->type, 'class=select-2');?>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->severity;?></td>
            <td><?php echo html::select('severity', (array)$lang->bug->severityList, $bug->severity, 'class=select-2');?>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->os;?></td>
            <td><?php echo html::select('os', (array)$lang->bug->osList, $bug->os, 'class=select-2');?></td>
          </tr>

          <tr>
            <td class='rowhead'><?php echo $lang->bug->status;?></td>
            <td><?php echo html::select('status', (array)$lang->bug->statusList, $bug->status, 'class=select-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->assignedTo;?></td>
            <td><?php echo html::select('assignedTo', $users, $bug->assignedTo, 'class=select-2');?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendPrjStoryTask;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->project;?></td>
            <td><span id='projectIdBox'><?php echo html::select('project', $projects, $bug->project, 'class=select-3 onchange=loadProjectStoriesAndTasks(this.value)');?></span></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->story;?></td>
            <td><span id='storyIdBox'><?php echo html::select('story', $stories, $bug->story, 'class=select-3');?></span></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->task;?></td>
            <td><span id='taskIdBox'><?php echo html::select('task', $tasks, $bug->task, 'class=select-3');?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendMailto;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead'></td>
            <td><?php echo html::input('mailto', $bug->mailto, 'class=text-3');?></div>
          </tr>
        </table>
      </fieldset>

      <fieldset>
      <legend><?php echo $lang->bug->legendAttatch;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead'></td>
            <td>
              <?php foreach($bug->files as $file) echo html::a($file->fullPath, $file->title);?>
              <input type='file' name='files' />
            </td>
          </tr>
        </table>
      </fieldset>
      
    </div>  

    <div class='yui-u'>  
      <fieldset>
        <legend><?php echo $lang->bug->legendOpenInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->bug->openedBy;?></td>
            <td><?php echo $users[$bug->openedBy];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->openedBuild;?></td>
            <td><?php echo html::input('openedBuild', $bug->openedBuild, 'class=text-2');?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendResolveInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->bug->resolvedBy;?></td>
            <td><?php echo html::select('resolvedBy', $users, $bug->resolvedBy, 'class=select-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolvedDate;?></td>
            <td><?php echo html::input('resolvedDate', $bug->resolvedDate, 'class=text-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolvedBuild;?></td>
            <td><?php echo html::input('resolvedBuild', $bug->resolvedBuild, 'class=text-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolution;?></td>
            <td><?php echo html::select('resolution', $lang->bug->resolutionList, $bug->resolution, 'class=select-2 onchange=setDuplicate(this.value)');?></td>
          </tr>
          <tr id='duplicateBugBox' <?php if($bug->resolution != 'duplicate') echo "style='display:none'";?>>
            <td class='rowhead'><?php echo $lang->bug->duplicateBug;?></td>
            <td><?php echo html::input('duplicateBug', $bug->duplicateBug, 'class=text-2');?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendCloseInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->bug->closedBy;?></td>
            <td><?php echo html::select('closedBy', $users, $bug->closedBy, 'class=select-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->closedDate;?></td>
            <td><?php echo html::input('closedDate', $bug->closedDate, 'class=text-2');?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendLinkBugs;?></legend>
        <div>&nbsp;</div>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendCases;?></legend>
        <div>&nbsp;</div>
      </fieldset>

    </div>  
  </div>
</div>  

<div class='yui-d0'>
  <fieldset>
  <legend><?php echo $lang->bug->legendComment;?></legend>
    <table class='table-1'>
      <tr>
        <td width='90%'><textarea name='comment' rows='4' class='area-1'></textarea></td>
        <td>
          <?php echo html::submitButton();?>
          <input type='button' value='<?php echo $lang->bug->buttonToList;?>' class='button-s' 
           onclick='location.href="<?php echo $this->createLink('bug', 'browse', "productID=$productID");?>"' />
        </td>
      </tr>
    </table>
  </fieldset>

  <fieldset>
  <legend><?php echo $lang->bug->legendSteps;?></legend>
    <table class='table-1'>
      <tr>
        <td width='90%'><textarea name='steps' rows='4' class='area-1'><?php echo $bug->steps;?></textarea></td>
        <td></td>
      </tr>
    </table>
  </fieldset>
</div>
<?php include '../../common/footer.html.php';?>
