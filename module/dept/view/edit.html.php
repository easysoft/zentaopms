<?php
/**
 * The edit view of dept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     dept
 * @version     $Id: edit.html.php 4795 2013-06-04 05:59:58Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
$webRoot = $this->app->getWebRoot();
$jsRoot  = $webRoot . "js/";
?>
<?php include '../../common/view/chosen.html.php';?>
<div class='modal-dialog w-500px'>
  <div class='modal-body'>
    <div id='titlebar'>
      <div class='heading'>
        <span class='prefix'><?php echo html::icon($lang->icons['tree']);?></span>
        <strong><small class='text-muted'><?php echo html::icon($lang->icons['edit']);?></small> <?php echo $lang->dept->edit;?></strong>
      </div>
    </div>
    <form action="<?php echo inlink('edit', 'deptID=' . $dept->id);?>" target='hiddenwin' class='form-condensed' method='post' class='mt-10px' id='dataform'>
      <table class='table table-form' style='width:100%'> 
        <tr>
          <th class='w-80px'><?php echo $lang->dept->parent;?></th>
          <td><?php echo html::select('parent', $optionMenu, $dept->parent, "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th class='w-80px'><?php echo $lang->dept->name;?></th>
          <td><?php echo html::input('name', $dept->name, "class='form-control'");?></td>
        </tr>
        <tr>
          <th class='w-80px'><?php echo $lang->dept->manager;?></th>
          <td><?php echo html::select('manager', $users, $dept->manager, "class='form-control chosen'", true);?></td>
        </tr>  
        <tr>
          <td colspan='2' class='text-center'>
          <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
