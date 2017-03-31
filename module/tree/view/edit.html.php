<?php
/**
 * The edit view of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
<?php include '../../common/view/chosen.html.php';?>
<div class='modal-dialog w-500px'>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><strong><?php echo $lang->tree->edit;?></strong></h4>
  </div>
  <div class='modal-body'>
    <form action="<?php echo inlink('edit', 'module=' . $module->id .'&type=' .$type);?>" target='hiddenwin' class='form-condensed' method='post' class='mt-10px' id='dataform'>
      <table class='table table-form'> 
        <?php if($showProduct):?>
        <tr>
          <th class='w-80px'><?php echo $lang->tree->product;?></th>
          <td><?php echo html::select('root', $products, $module->root, "class='form-control chosen'");?></td>
        </tr>
        <?php endif;?>
        <?php $hidden = ($type != 'story' and $module->type == 'story');?>
        <tr <?php if($hidden) echo "style='display:none'";?>>
          <th class='w-80px'><?php echo $lang->tree->parent;?></th>
          <td><?php echo html::select('parent', $optionMenu, $module->parent, "class='form-control chosen'");?></td>
        </tr>
        <tr <?php if($hidden) echo "style='display:none'";?>>
          <th class='w-80px'><?php echo $lang->tree->name;?></th>
          <td><?php echo html::input('name', $module->name, "class='form-control' autocomplete='off'");?></td>
        </tr>
        <?php if($type == 'bug'):?>
        <tr>
          <th class='w-80px'><?php echo $lang->tree->owner;?></th>
          <td><?php echo html::select('owner', $users, $module->owner, "class='form-control chosen'", true);?></td>
        </tr>  
        <?php endif;?>
        <tr>
          <th><?php echo $lang->tree->short;?></th>
          <td><?php echo html::input('short', $module->short, "class='form-control' autocomplete='off'");?></td>
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
var currentRoot   = <?php echo $module->root;?>;
var currentParent = <?php echo $module->parent;?>;
function getProductModules(productID)
{
    $.get(createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + productID + '&viewType=story&branch=0&rootModuleID=0&returnType=json'), function(data)
    {
        var newOption = '';
        for(i in data) newOption += '<option value="' + i + '">' + data[i] + '</option>';
        $('#parent').html(newOption);
        if(productID == currentRoot) $('#parent').val(currentParent);
        $('#parent').trigger('chosen:updated')
    }, 'json');
}
$(function()
{
    $('#root').change(function()
    {
        if($(this).val() == currentRoot) return true;
        if(!confirm('<?php echo $lang->tree->confirmRoot?>'))
        {
            $('#root').val(currentRoot);
            $('#root').trigger('chosen:updated');
        }
        getProductModules($(this).val());
    })
})
</script>
