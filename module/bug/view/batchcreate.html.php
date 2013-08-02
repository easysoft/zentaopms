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
<form method='post' target='hiddenwin'>
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->bug->project . $lang->colon . $lang->bug->batchCreate;?></caption>
    <tr>
      <th class='w-20px'>     <?php echo $lang->idAB;?></th> 
      <th class='w-100px'>    <?php echo $lang->bug->module;?></th>
      <th class='w-100px'>    <?php echo $lang->bug->project;?></th>
      <th class='w-150px red'><?php echo $lang->bug->openedBuild;?></th>
      <th class='red'>        <?php echo $lang->bug->title;?></th>
      <th>                    <?php echo $lang->bug->steps;?></th>
      <th class='w-100px'>    <?php echo $lang->typeAB;?></th>
      <th class='w-80px'>     <?php echo $lang->bug->severity;?></th>
      <th class='w-120px'>    <?php echo $lang->bug->os;?></th>
      <th class='w-100px'>    <?php echo $lang->bug->browser;?></th>
    </tr>

   <?php for($i = 0; $i < $config->bug->batchCreate; $i++):?>
    <tr class='a-center'>
      <td><?php echo $i+1;?></td>
      <td class='a-left' style='overflow:visible'><?php echo html::select("modules[$i]", $moduleOptionMenu, $moduleID, "class='select-1'");?></td>
      <td><?php echo html::select("projects[$i]", $projects, $projectID, "class='select-1' onchange='loadProjectBuilds($productID, this.value, $i)'");?></td>
      <td class='a-left chosenBox' style='overflow:visible' id='buildBox<?php echo $i;?>'><?php echo html::select("openedBuilds[$i][]", $builds, '', "class='select-1' multiple data-placeholder='{$lang->bug->placeholder->chooseBuilds}'");?></td>
      <td><?php echo html::input("titles[$i]", '', 'class=select-1');?></td>
      <td><?php echo html::textarea("stepses[$i]", '', "rows='1' class=text-1");?></td>
      <td><?php echo html::select("types[$i]", $lang->bug->typeList, '');?></td>
      <td><?php echo html::select("severities[$i]", $lang->bug->severityList, '');?></td>
      <td><?php echo html::select("oses[$i]", $lang->bug->osList, '');?></td>
      <td><?php echo html::select("browsers[$i]", $lang->bug->browserList, '');?></td>
    </tr>
    <?php endfor;?>
    <tr><td colspan='8' class='a-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
