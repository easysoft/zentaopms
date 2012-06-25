<?php
/**
 * The create view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php include '../../common/view/autocomplete.html.php';?>
<?php include '../../common/view/alert.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<script>var holders = <?php echo json_encode($lang->bug->placeholder);?></script>
<script language='Javascript'>
userList = "<?php echo join(',', array_keys($users));?>".split(',');
</script>
<form method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table class='table-1'> 
    <caption><?php echo $lang->bug->create;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->bug->lblProductAndModule;?></th>
      <td>
        <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value)' class='select-3'");?>
        <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "onchange='setAssignedTo()'");?></span>
      </td>
     </tr>  
     <tr>
      <th class='rowhead'><?php echo $lang->bug->project;?></th>
      <td><span id='projectIdBox'><?php echo html::select('project', $projects, $projectID, 'class=select-3 onchange=loadProjectRelated(this.value)');?></span></td>
     </tr>
     <tr>
      <th class='rowhead'><?php echo $lang->bug->openedBuild;?></th>
      <td>
        <span id='buildBox'><?php echo html::select('openedBuild[]', $builds, $buildID, 'size=3 multiple=multiple class=select-3');?></span>
        <?php echo $lang->build->notice2;?>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><nobr><?php echo $lang->bug->lblAssignedTo;?></nobr></th>
      <td> <?php echo html::select('assignedTo', $users, $assignedTo, 'class=select-3');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->bug->title;?></th>
      <td><?php echo html::input('title', $title, "class='text-1'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->bug->steps;?></th>
      <td>
        <table class='w-p100 bd-none'>
          <tr class='bd-none' valign='top'>
            <td class='w-p85 bd-none padding-zero'><?php echo html::textarea('steps', $steps, "rows='10'");?></td>
            <td class='bd-none pl-10px' id='tplBox'><?php echo $this->fetch('bug', 'buildTemplates');?></td>
          </tr>
        </table>
      </td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->bug->lblStory;?></th>
      <td>
        <span id='storyIdBox'><?php echo html::select('story', $stories, $storyID);?></span>
      </td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->bug->task;?></th>
      <td><span id='taskIdBox'><?php echo html::select('task', $tasks, $taskID);?></span></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->bug->lblTypeAndSeverity;?></th>
      <td> 
        <?php echo html::select('type', $lang->bug->typeList, $type, 'class=select-2');?> 
        <?php echo html::select('severity', $lang->bug->severityList, $severity, 'class=select-2');?>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><nobr><?php echo $lang->bug->lblSystemBrowserAndHardware;?></nobr></th>
      <td>
        <?php echo html::select('os', $lang->bug->osList, $os, 'class=select-2');?>
        <?php echo html::select('browser', $lang->bug->browserList, $browser, 'class=select-2');?>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><nobr><?php echo $lang->bug->lblMailto;?></nobr></th>
      <td> <?php echo html::input('mailto', $mailto, 'class=text-1');?> </td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->bug->keywords;?></th>
      <td><?php echo html::input('keywords', $keywords, "class='text-1'");?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->bug->files;?></th>
      <td><?php echo $this->fetch('file', 'buildform', 'fileCount=2&percent=0.85');?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'>
        <?php echo html::submitButton() . html::resetButton() . html::hidden('case', $caseID);?>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
