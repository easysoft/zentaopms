<?php
/**
 * The create view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: create.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<script language='Javascript'>
function loadProducts(project)
{
    link = createLink('project', 'ajaxGetProducts', 'projectID=' + project);
    $('#productBox').load(link);
}
/* 设置文档类型。*/
function setType(type)
{
    if(type == 'url'){
        $('#urlBox').show();
        $('#fileBox').hide();
        $('#contentBox').hide();
    }
    else if(type == 'text'){
        $('#urlBox').hide();
        $('#fileBox').hide();
        $('#contentBox').show();
    }
    else{
        $('#urlBox').hide();
        $('#fileBox').show();
        $('#contentBox').hide();
    }
}
$(function() {KE.show({id:'content', items:tools, filterMode:true, imageUploadJson: createLink('file', 'ajaxUpload')});});
</script>
<div class='yui-d0'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin'>
    <table class='table-1'> 
      <caption><?php echo $lang->doc->create;?></caption>
      <?php if($libID == 'product'):?>
      <tr>
        <th class='rowhead'><?php echo $lang->doc->product;?></th>
        <td><?php echo html::select('product', $products, $productID, "class='select-3'");?></td>
      </tr>  
      <?php elseif($libID == 'project'):?>
      <tr>
        <th class='rowhead'><?php echo $lang->doc->project;?></th>
        <td><?php echo html::select('project', $projects, $projectID, "class='select-3' onchange=loadProducts(this.value);");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->doc->product;?></th>
        <td><span id='productBox'><?php echo html::select('product', $products, '', "class='select-3'");?></span></td>
      </tr>  
      <?php endif;?>
      <tr>
        <th class='rowhead'><?php echo $lang->doc->module;?></th>
        <td><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='select-3'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->doc->type;?></th>
        <td><?php echo html::radio('type', $lang->doc->types, 'file', "onclick=setType(this.value)");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->doc->title;?></th>
        <td><?php echo html::input('title', '', "class='text-1'");?></td>
      </tr> 
      <tr id='urlBox' class='hidden'>
        <th class='rowhead'><?php echo $lang->doc->url;?></th>
        <td><?php echo html::input('url', '', "class='text-1'");?></td>
      </tr>  
      <tr id='contentBox' class='hidden'>
        <th class='rowhead'><?php echo $lang->doc->content;?></th>
        <td><?php echo html::textarea('content', '', "class='area-1' style='width:90%; height:200px'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->doc->keywords;?></th>
        <td><?php echo html::input('keywords', '', "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->doc->digest;?></th>
        <td><?php echo html::textarea('digest', '', "class='text-1' rows=3");?></td>
      </tr>  
      <tr id='fileBox'>
        <th class='rowhead'><?php echo $lang->doc->files;?></th>
        <td><?php echo $this->fetch('file', 'buildform', 'fileCount=2');?></td>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton() . html::hidden('lib', $libID);?></td>
      </tr>
    </table>
  </form>
</div>
<?php include './footer.html.php';?>
