<?php
/**
 * The create view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: create.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/form.html.php';
include '../../common/view/chosen.html.php';
include '../../common/view/kindeditor.html.php';
js::set('holders', $lang->bug->placeholder);
js::set('page', 'create');
js::set('createRelease', $lang->release->create);
js::set('createBuild', $lang->build->create);
js::set('refresh', $lang->refresh);
?>
<div class='container'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['bug']);?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->bug->create;?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' enctype='multipart/form-data' id='dataform' data-type='ajax'>
    <table class='table table-form'> 
      <tr>
        <th class='w-110px'><?php echo $lang->bug->lblProductAndModule;?></th>
        <td class='w-p25-f'>
          <?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value) class='form-control' autocomplete='off'");?>
        </td>
        <td>
          <div class='input-group w-p25-f' id='moduleIdBox'>
          <?php
          echo html::select('module', $moduleOptionMenu, $moduleID, "onchange='loadModuleRelated()' class='form-control'");
          if(count($moduleOptionMenu) == 1)
          {
              echo "<span class='input-group-addon'>";
              echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=bug"), $lang->tree->manage, '_blank');
              echo '&nbsp; ';
              echo html::a("javascript:loadProductModules($productID)", $lang->refresh);
              echo '</span>';
          }
          ?>
          </div>
        </td><td class='w-150px'></td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->project;?></th>
        <td><span id='projectIdBox'><?php echo html::select('project', $projects, $projectID, 'class=form-control onchange=loadProjectRelated(this.value) autocomplete="off"');?></span></td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->openedBuild;?></th>
        <td>
          <span id='buildBox'>
          <?php echo html::select('openedBuild[]', $builds, $buildID, "size=4 multiple=multiple class='chosen form-control'");?>
          </span>
        </td>
        <td id='buildBoxActions'></td>
      </tr>
      <tr>
        <th><nobr><?php echo $lang->bug->lblAssignedTo;?></nobr></th>
        <td><span id='assignedToBox'><?php echo html::select('assignedTo', $users, $assignedTo, "class='form-control chosen'");?></span></td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->title;?></th>
        <td colspan='2'><?php echo html::input('title', $bugTitle, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->bug->steps;?></th>
        <td colspan='2'><?php echo html::textarea('steps', $steps, "rows='10' class='form-control'");?></td>
        <td style='vertical-align: top;'>
          <ul id='tplBox' class='list-group'><?php echo $this->fetch('bug', 'buildTemplates');?></ul>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->bug->lblStory;?></th>
        <td colspan='2'>
          <span id='storyIdBox'><?php echo html::select('story', $stories, $storyID, "class='form-control'");?></span>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->bug->task;?></th>
        <td colspan='2'><span id='taskIdBox'><?php echo html::select('task', $tasks, $taskID, "class='form-control'");?></span></td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->lblTypeAndSeverity;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::select('type', $lang->bug->typeList, $type, "class='form-control' style='width: 50%'");?> 
            <?php echo html::select('severity', $lang->bug->severityList, $severity, "class='form-control' style='width: 50%'");?>
          </div>
        </td>
      </tr>
      <tr>
        <th><nobr><?php echo $lang->bug->lblSystemBrowserAndHardware;?></nobr></th>
        <td>
          <div class='input-group'>
          <?php echo html::select('os', $lang->bug->osList, $os, "class='form-control' style='width: 50%'");?>
          <?php echo html::select('browser', $lang->bug->browserList, $browser, "class='form-control' style='width: 50%'");?>
          </div>
        </td>
      </tr>
      <tr>
        <th><nobr><?php echo $lang->bug->lblMailto;?></nobr></th>
        <td colspan='2'>
          <?php 
          echo html::select('mailto[]', $users, str_replace(' ', '', $mailto), "class='form-control chosen' multiple");
          ?>
        </td>
        <td class='text-top'>
          <?php
          if($contactLists) echo html::select('', $contactLists, '', "class='form-control' onchange=\"setMailto('mailto', this.value)\"");
          ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->keywords;?></th>
        <td colspan='2'><?php echo html::input('keywords', $keywords, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->bug->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=2&percent=0.85');?></td>
      </tr>  
      <tr>
        <td></td>
        <td colspan='2'>
          <?php echo html::submitButton() . html::backButton() . html::hidden('case', $caseID);?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
