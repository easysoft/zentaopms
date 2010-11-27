<?php
/**
 * The edit file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
/**
 * Load all fields.
 * 
 * @param  int $productID 
 * @access public
 * @return void
 */
function loadAll(productID)
{
    if(!changeProductConfirmed)
    {
         firstChoice = confirm('<?php echo $lang->bug->confirmChangeProduct;?>');
         changeProductConfirmed = true;    // Only notice the user one time.
    }
    if(changeProductConfirmed || firstChoice)
    {
        $('#taskIdBox').get(0).innerHTML = emptySelect;
        loadModuleMenu(productID); 
        loadProductStories(productID);
        loadProductProjects(productID); 
        loadProductBuilds(productID);
    }
}

/**
 * Load module menu.
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug');
    $('#moduleIdBox').load(link);
}

/**
 * Load product stories 
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadProductStories(productID)
{
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&moduleId=0&storyID=' + oldStoryID);
    $('#storyIdBox').load(link);
}

/**
 * Load projects of product. 
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadProductProjects(productID)
{
    link = createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&projectID=' + oldProjectID);
    $('#projectIdBox').load(link);
}

/**
 * loadProductBuilds 
 * 
 * @param  productID $productID 
 * @access public
 * @return void
 */
function loadProductBuilds(productID)
{
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);
    $('#openedBuildBox').load(link);
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild);
    $('#resolvedBuildBox').load(link);
}

/**
 * loadProjectRelated 
 * 
 * @param  projectID $projectID 
 * @access public
 * @return void
 */
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

/**
 * loadProjectTasks 
 * 
 * @param  projectID $projectID 
 * @access public
 * @return void
 */
function loadProjectTasks(projectID)
{
    link = createLink('task', 'ajaxGetProjectTasks', 'projectID=' + projectID + '&taskID=' + oldTaskID);
    $('#taskIdBox').load(link);
}

/**
 * loadProjectStories 
 * 
 * @param  projectID $projectID 
 * @access public
 * @return void
 */
function loadProjectStories(projectID)
{
    productID = $('#product').get(0).value; 
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=' + productID + '&storyID=' + oldStoryID);
    $('#storyIdBox').load(link);
}

/**
 * Load builds of a project.
 * 
 * @param  int      $projectID 
 * @access public
 * @return void
 */
function loadProjectBuilds(projectID)
{
    productID = $('#product').val();
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);
    $('#openedBuildBox').load(link);
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=resolvedBuild&build=' + oldResolvedBuild);
    $('#resolvedBuildBox').load(link);
}

/**
 * Set duplicate field.
 * 
 * @param  string $resolution 
 * @access public
 * @return void
 */
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

/**
 * Get story or task list.
 * 
 * @param  string $module 
 * @access public
 * @return void
 */
function getList(module)
{
    productID = $('#product').get(0).value;
    projectID = $('#project').get(0).value;
    storyID   = $('#story').get(0).value;
    taskID    = $('#task').get(0).value;
    if(module == 'story')
    {
        link = createLink('search', 'select', 'productID=' + productID + '&projectID=' + projectID + '&module=story&moduleID=' + storyID);
        $('#storyListIdBox a').attr("href", link);
    }
    else
    {
        link = createLink('search', 'select', 'productID=' + productID + '&projectID=' + projectID + '&module=task&moduleID=' + taskID);
        $('#taskListIdBox a').attr("href", link);
    }
}

var userList = "<?php echo join(',', array_keys($users));?>".split(',');
$(function() {
    $("#mailto").autocomplete(userList, { multiple: true, mustMatch: true});
    $("#searchStories").colorbox({width:680, height:400, iframe:true, transition:'none'});
    $("#searchTasks").colorbox({width:680, height:400, iframe:true, transition:'none'});
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
            <div class='w-p90'><?php echo html::textarea('steps', htmlspecialchars($bug->steps), "rows='12'");?></div>
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
          <td><div id='storyIdBox' class='searchleft'><?php echo html::select('story', $stories, $bug->story, "class=select-3");?></div>
          <div id='storyListIdBox'><?php echo html::a('', $lang->go, "_blank", "class='search' id='searchStories' onclick=getList('story')");?></div>
          </td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->bug->task;?></td>
          <td><div id='taskIdBox' class='searchleft'><?php echo html::select('task', $tasks, $bug->task, 'class=select-3');?></div>
          <div id='taskListIdBox'><?php echo html::a('', $lang->go, "_blank", "class='search' id='searchTasks' onclick=getList('task')");?></div>
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
