<?php
/**
 * The create view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: create.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('holders ', $lang->doc->placeholder);?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['doc']);?></span>
      <strong><small class='text-muted'><i class='icon icon-plus'></i></small> <?php echo $lang->doc->create;?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <?php if($libID == 'product'):?>
      <tr>
        <th class='w-80px'><?php echo $lang->doc->product;?></th>
        <td class='w-p25-f'><?php echo html::select('product', $products, $productID, "class='form-control chosen'");?></td><td></td>
      </tr>  
      <?php elseif($libID == 'project'):?>
      <tr>
        <th class='w-80px'><?php echo $lang->doc->project;?></th>
        <td class='w-p25-f'><?php echo html::select('project', $projects, $projectID, "class='form-control chosen' onchange=loadProducts(this.value)");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->doc->product;?></th>
        <td class='w-p25-f'><span id='productBox'><?php echo html::select('product', $products, '', "class='form-control chosen'");?></span></td><td></td>
      </tr>  
      <?php endif;?>
      <tr>
        <th class='w-80px'><?php echo $lang->doc->module;?></th>
        <td><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->doc->type;?></th>
        <td colspan='2'><?php echo html::radio('type', $lang->doc->types, 'file', "onclick=setType(this.value)");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->doc->title;?></th>
        <td colspan='2'><?php echo html::input('title', '', "class='form-control'");?></td>
      </tr> 
      <tr id='urlBox' class='hide'>
        <th><?php echo $lang->doc->url;?></th>
        <td colspan='2'><?php echo html::input('url', '', "class='form-control'");?></td>
      </tr>  
      <tr id='contentBox' class='hide'>
        <th><?php echo $lang->doc->content;?></th>
        <td colspan='2'><?php echo html::textarea('content', '', "class='form-control' style='width:90%; height:200px'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->doc->keywords;?></th>
        <td colspan='2'><?php echo html::input('keywords', '', "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->doc->digest;?></th>
        <td colspan='2'><?php echo html::textarea('digest', '', "class='form-control' rows=3");?></td>
      </tr>  
      <tr id='fileBox'>
        <th><?php echo $lang->doc->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=2');?></td>
      </tr>  
      <tr>
        <td></td>
        <td><?php echo html::submitButton() . html::backButton() . html::hidden('lib', $libID);?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
