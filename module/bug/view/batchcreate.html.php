<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['bug']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchCreate']);?></small> <?php echo $lang->bug->common . $lang->colon . $lang->bug->batchCreate;?></strong>
  </div>
</div>

<form class='form-condensed' class='form-condensed' method='post' target='hiddenwin'>
  <table class='table table-fixed table-form'>
    <thead>
      <tr>
        <th class='w-30px'>  <?php echo $lang->idAB;?></th> 
        <th class='w-100px'> <?php echo $lang->bug->module;?></th>
        <th class='w-100px'> <?php echo $lang->bug->project;?></th>
        <th class='w-150px'><?php echo $lang->bug->openedBuild;?> <span class='required'></span></th>
        <th><?php echo $lang->bug->title;?> <span class='required'></span></th>
        <th>                 <?php echo $lang->bug->steps;?></th>
        <th class='w-100px'> <?php echo $lang->typeAB;?></th>
        <th class='w-80px'>  <?php echo $lang->bug->severity;?></th>
        <th class='w-120px'> <?php echo $lang->bug->os;?></th>
        <th class='w-100px'> <?php echo $lang->bug->browser;?></th>
      </tr>
    </thead>
    <?php for($i = 0; $i < $config->bug->batchCreate; $i++):?>
    <tr class='text-center'>
      <td><?php echo $i+1;?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("modules[$i]", $moduleOptionMenu, $moduleID, "class='form-control'");?></td>
      <td><?php echo html::select("projects[$i]", $projects, $projectID, "class='form-control' onchange='loadProjectBuilds($productID, this.value, $i)'");?></td>
      <td class='text-left' style='overflow:visible' id='buildBox<?php echo $i;?>'><?php echo html::select("openedBuilds[$i][]", $builds, '', "class='form-control chosen' multiple");?></td>
      <td><?php echo html::input("titles[$i]", '', 'class=form-control');?></td>
      <td>
        <?php echo html::textarea("stepses[$i]", '', "rows='1' class='form-control'");?>
      </td>
      <td><?php echo html::select("types[$i]", $lang->bug->typeList, '', "class='form-control'");?></td>
      <td><?php echo html::select("severities[$i]", $lang->bug->severityList, '', "class='form-control'");?></td>
      <td><?php echo html::select("oses[$i]", $lang->bug->osList, '', "class='form-control'");?></td>
      <td><?php echo html::select("browsers[$i]", $lang->bug->browserList, '', "class='form-control'");?></td>
    </tr>
    <?php endfor;?>
    <tr><td colspan='8' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
