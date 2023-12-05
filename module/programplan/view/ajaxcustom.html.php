<?php
/**
 * The setting file of programplan gantt of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hucheng Tang <liumengyi@easycorp.ltd>
 * @package     programplan
 * @version     $Id: ajaxcustom.html.php 935 2022-10-25 16:15:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<style>
.checkbox-primary{display: inline-block;width:100px;}
</style>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->programplan->settingGantt;?></h2>
  </div>
  <form class='form-indicator main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->execution->gantt->format;?></th>
        <td><?php echo html::radio('zooming', $lang->execution->gantt->zooming, $zooming ? $zooming : 'day');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->programplan->viewSetting;?></th>
        <td><?php echo html::checkbox('stageCustom', $lang->programplan->stageCustom, $stageCustom);?></td>
      </tr>
      <tr>
        <th><?php echo $lang->customField;?></th>
        <td><?php echo html::checkbox('ganttFields', $customFields, $showFields);?></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
