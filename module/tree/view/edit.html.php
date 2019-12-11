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
js::set('type', $type);
if(isset($pageCSS)) css::internal($pageCSS);
?>
<div class='modal-dialog w-500px'>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
    <h4 class="modal-title">
      <strong>
        <?php
        $lblEditTree = $lang->tree->edit;
        if($type == 'doc') $lblEditTree = $lang->doc->editType;
        if($type == 'line') $lblEditTree = $lang->tree->manageLine;
        echo $lblEditTree;
        ?>
      </strong>
    </h4>
  </div>
  <div class='modal-body'>
    <form action="<?php echo inlink('edit', 'module=' . $module->id .'&type=' .$type);?>" target='hiddenwin' method='post' class='mt-10px' id='dataform'>
      <table class='table table-form'>
        <?php if($showProduct):?>
        <tr>
          <th class='thWidth'><?php echo $lang->tree->product;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::select('root', $products, $module->root, "class='form-control chosen' onchange='loadBranches(this)'");?>
              <?php if($product->type != 'normal'):?>
              <span class='input-group-addon fix-border fix-padding'></span>
              <?php echo html::select('branch', $branches, $module->branch, "class='form-control chosen control-branch'");?>
              </div>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <?php endif;?>
        <?php $hidden = ($type != 'story' and $module->type == 'story');?>
        <?php if($type == 'doc'):?>
        <tr>
          <th class='thWidth'><?php echo $lang->doc->lib;?></th>
          <td><?php echo html::select('root', $libs, $module->root, "class='form-control chosen'");?></td>
        </tr>
        <?php endif;?>
        <?php if($module->type != 'line'):?>
        <tr <?php if($hidden) echo "style='display:none'";?>>
          <th class='thWidth'><?php echo ($type == 'doc' || $type == 'feedback') ? $lang->tree->parentCate : $lang->tree->parent;?></th>
          <td><?php echo html::select('parent', $optionMenu, $module->parent, "class='form-control chosen'");?></td>
        </tr>
        <?php endif;?>
        <tr <?php if($hidden) echo "style='display:none'";?>>
          <th class='thWidth'>
            <?php
            $lblTreeName = $lang->tree->name;
            if($type == 'doc' || $type == 'feedback') $lblTreeName = $lang->tree->cate;
            if($type == 'line') $lblTreeName = $lang->tree->line;
            echo $lblTreeName;
            ?>
          </th>
          <td><?php echo html::input('name', $module->name, "class='form-control'");?></td>
        </tr>
        <?php if($type == 'bug'):?>
        <tr>
          <th class='thWidth'><?php echo $lang->tree->owner;?></th>
          <td><?php echo html::select('owner', $users, $module->owner, "class='form-control chosen'", true);?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->tree->short;?></th>
          <td><?php echo html::input('short', $module->short, "class='form-control'");?></td>
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

function loadDocModule(libID)
{
    var link = createLink('doc', 'ajaxGetChild', 'libID=' + libID + '&type=parent');
    $.post(link, function(data)
    {
        $('#parent').empty().append($(data).children()).trigger('chosen:updated');
    });
}
$(function()
{
    $('#root').change(function()
    {
        if($(this).val() == currentRoot) return true;
        var confirmRoot = <?php echo json_encode($type == 'doc' ? $lang->tree->confirmRoot4Doc : $lang->tree->confirmRoot);?>;
        if(!confirm(confirmRoot))
        {
            $('#root').val(currentRoot);
            $('#root').trigger('chosen:updated');
        }
        else
        {
            if(type != 'doc') getProductModules($(this).val());
            if(type == 'doc') loadDocModule($(this).val());
        }
    })

    $('#dataform .chosen').chosen();

    // hide #parent chosen dropdown on root dropdown show
    $('#root').on('chosen:showing_dropdown', function()
    {
        $('#parent').trigger('chosen:close');
    });
})

/**
 * Load branches by product.
 * 
 * @param  object $obj 
 * @access public
 * @return void
 */
function loadBranches(obj)
{
    var productID   = $(obj).val();
    var $inputGroup = $(obj).closest('.input-group');
    $inputGroup.find('#branch').remove();
    $inputGroup.find('#branch_chosen').remove();
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + productID), function(data)
    {
        if(data)
        {
            $inputGroup.append(data);
            $inputGroup.find('#branch').removeAttr('onchange');
            $inputGroup.find('#branch').chosen();
        }
    })
}
</script>
