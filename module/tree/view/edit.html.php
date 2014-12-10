<?php
/**
 * The edit view of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: edit.html.php 4795 2013-06-04 05:59:58Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
$webRoot = $this->app->getWebRoot();
$jsRoot  = $webRoot . "js/";
?>
<div class='modal-dialog w-500px'>
  <div class='modal-body'>
    <div id='titlebar'>
      <div class='heading'>
        <span class='prefix'><?php echo html::icon($lang->icons['tree']);?></span>
        <strong><small class='text-muted'><?php echo html::icon($lang->icons['edit']);?></small> <?php echo $lang->tree->edit;?></strong>
      </div>
    </div>
    <form action="<?php echo inlink('edit', 'module=' . $module->id .'&type=' .$type);?>" target='hiddenwin' class='form-condensed' method='post' class='mt-10px' id='dataform'>
      <table class='table table-form'> 
        <?php $hidden = ($type != 'story' and $module->type == 'story');?>
        <tr <?php if($hidden) echo "style='display:none'";?>>
          <th class='w-80px'><?php echo $lang->tree->parent;?></th>
          <td><?php echo html::select('parent', $optionMenu, $module->parent, "class='form-control chosen'");?></td>
        </tr>
        <tr <?php if($hidden) echo "style='display:none'";?>>
          <th class='w-80px'><?php echo $lang->tree->name;?></th>
          <td><?php echo html::input('name', $module->name, "class='form-control'");?></td>
        </tr>
        <?php if($type == 'bug'):?>
        <tr>
          <th class='w-80px'><?php echo $lang->tree->owner;?></th>
          <td><?php echo html::select('owner', $users, $module->owner, "class='form-control chosen'", true);?></td>
        </tr>  
        <?php endif;?>
        <tr>
          <td colspan='2' class='text-center'>
          <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
