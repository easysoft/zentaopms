<?php
/**
 * The create view of bug module of ZenTaoMS.
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
<style>
#project, #product, #story, #openedBuild {width:245px}
#severity, #browser {width: 113px}
</style>
<script language='Javascript'>
/* 当选择产品时，触发这个方法。*/
function loadAll(productID)
{
    $('#taskIdBox').get(0).innerHTML = '';  // 将taskID内容复位。
    loadModuleMenu(productID);              // 加载产品的模块列表。
    loadProductStories(productID);          // 加载产品的需求列表。
    loadProductProjects(productID);         // 加载项目列表。
    loadProductBuilds(productID);           // 加载build列表。
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
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID);
    $('#storyIdBox').load(link);
}

/* 加载项目列表。*/
function loadProductProjects(productID)
{
    link = createLink('product', 'ajaxGetProjects', 'productID=' + productID);
    $('#projectIdBox').load(link);
}

/* 加载产品build列表。*/
function loadProductBuilds(productID)
{
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild');
    $('#buildBox').load(link);
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
        $('#taskIdBox').get(0).innerHTML = '';
        loadProductStories($('#product').get(0).value);
    }
}

/* 加载项目的任务列表。*/
function loadProjectTasks(projectID)
{
    link = createLink('task', 'ajaxGetProjectTasks', 'projectID=' + projectID);
    $('#taskIdBox').load(link);
}

/* 加载项目的需求列表。*/
function loadProjectStories(projectID)
{
    productID = $('#product').get(0).value; 
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=' + productID);
    $('#storyIdBox').load(link);
}

/* 加载项目的build列表。*/
function loadProjectBuilds(projectID)
{
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&varName=openedBuild');
    $('#buildBox').load(link);
}
</script>
<div class='yui-doc3'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin'>
    <table align='center' class='table-1'> 
      <caption><?php echo $lang->bug->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->bug->lblProductAndModule;?></th>
        <td class='a-left'>
          <?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value); class='select-2'");?>
          <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, 'class=select-3');?></span>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->bug->lblProjectAndTask;?></th>
        <td class='a-left'>
        <span id='projectIdBox'><?php echo html::select('project', $projects, $projectID, 'onchange=loadProjectRelated(this.value)');?></span>
          <span id='taskIdBox'></span>
        </td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->bug->lblStory;?></th>
        <td class='a-left'>
          <span id='storyIdBox'><?php echo html::select('story', $stories, $storyID);?></span>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->bug->openedBuild;?></th>
        <td class='a-left'>
          <span id='buildBox'><?php echo html::select('openedBuild', $builds, $buildID);?></span>
        </td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->bug->lblTypeAndSeverity;?></th>
        <td class='a-left'> 
          <?php echo html::select('type', (array)$lang->bug->typeList, 'codeerror', 'class=select-2');?> 
          <?php echo html::select('severity', (array)$lang->bug->severityList, '', 'class=select-2');?>
        </td>
      </tr>
      <tr>
        <th class='rowhead'><nobr><?php echo $lang->bug->lblSystemBrowserAndHardware;?></nobr></th>
        <td class='a-left'>
          <?php echo html::select('os', (array)$lang->bug->osList, '', 'class=select-2');?>
          <?php echo html::select('browser', (array)$lang->bug->browserList, '', 'class=select-2');?>
        </td>
      </tr>
      <tr>
        <th class='rowhead'><nobr><?php echo $lang->bug->lblAssignedTo;?></nobr></th>
        <td class='a-left'> <?php echo html::select('assignedTo', $users, '', 'class=select-3');?></td>
      </tr>
      <tr>
        <th class='rowhead'><nobr><?php echo $lang->bug->lblMailto;?></nobr></th>
        <td class='a-left'> <?php echo html::select('mailto[]', $users, '', 'class=select-3 size=5 multiple=multiple');?> </td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->bug->title;?></th>
        <td class='a-left'><input type='text' name='title' class='text-1' value='<?php echo $title;?>' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->bug->steps;?></th>
        <td class='a-left'><textarea name='steps' class='area-1' rows='6'><?php echo $steps;?></textarea></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->bug->files;?></th>
        <td class='a-left'><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>  
      <tr>
        <td colspan='2'>
          <?php echo html::submitButton() . html::resetButton() . html::hidden('case', $caseID);?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/footer.html.php';?>
