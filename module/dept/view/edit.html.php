<?php
/**
 * The edit view of dept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     dept
 * @version     $Id: edit.html.php 4795 2013-06-04 05:59:58Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
$webRoot = $this->app->getWebRoot();
$jsRoot  = $webRoot . "js/";
if(isset($pageCSS)) css::internal($pageCSS);
?>
<div class='modal-dialog w-500px'>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
    <h4 class="modal-title"><strong><?php echo $lang->dept->edit;?></strong></h4>
  </div>
  <div class='modal-body'>
    <form action="<?php echo inlink('edit', 'deptID=' . $dept->id);?>" target='hiddenwin' method='post' class='mt-10px' id='dataform'>
      <table class='table table-form' style='width:100%'>
        <tr>
          <th class='thWidth'><?php echo $lang->dept->parent;?></th>
          <td><?php echo html::select('parent', $optionMenu, $dept->parent, "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->dept->name;?></th>
          <td><?php echo html::input('name', $dept->name, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->dept->manager;?></th>
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
<script>
<?php if(isset($pageJS)) echo $pageJS;?>
$('#dataform .chosen').chosen();
</script>
