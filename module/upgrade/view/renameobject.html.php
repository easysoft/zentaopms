<?php
/**
 * The html template file of renameObject method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun<sunguangming@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: renameobject.html.php 4129 2021-11-30 13:07:14Z sunguangming $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<style>.group-end {border-bottom: 1px solid #efefef;}</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php if($type == 'project') echo $lang->upgrade->duplicateProject;?></h2>
    </div>
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-40px text-left'><?php echo $lang->$type->id;?></th>
          <th class='c-name text-left'><?php echo $lang->$type->name;?></th>
          <th class='c-name text-left'><?php echo $lang->upgrade->editedName;?></th>
        </tr>
        <?php foreach($objectGroup as $objectName => $objectList):?>
        <?php foreach($objectList as $key => $object):?>
        <tr <?php if($object->id == end($objectList)->id) echo "class='group-end'";?>>
          <td><?php echo $object->id;?></td>
          <td><?php echo $object->name;?></td>
          <td><?php echo html::input("project[$object->id]", '', "class='form-control'");?></td>
        </tr>
        <?php endforeach;?>
        <?php endforeach;?>
        <tr>
          <td colspan='3' class='text-center form-actions'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
