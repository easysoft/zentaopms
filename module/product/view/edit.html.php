<?php
/**
 * The edit view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: edit.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('noProject', ($config->global->flow == 'onlyStory' or $config->global->flow == 'onlyTest') ? true : false);?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['product']);?> <strong><?php echo $product->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('product', 'view', 'product=' . $product->id), $product->name);?></strong>
      <small class='text-muted'> <?php echo $lang->product->edit;?> <i class='icon icon-pencil'></i></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table align='center' class='table table-form'> 
      <tr>
        <th class='w-90px'><?php echo $lang->product->name;?></th>
        <td class='w-p25-f'><?php echo html::input('name', $product->name, "class='form-control' autocomplete='off'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->code;?></th>
        <td><?php echo html::input('code', $product->code, "class='form-control' autocomplete='off'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->PO;?></th>
        <td><?php echo html::select('PO', $poUsers, $product->PO, "class='form-control chosen'");?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->product->QD;?></th>
        <td><?php echo html::select('QD', $qdUsers, $product->QD, "class='form-control chosen'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->RD;?></th>
        <td><?php echo html::select('RD', $rdUsers, $product->RD, "class='form-control chosen'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->type;?></th>
        <td><?php echo html::select('type', $lang->product->typeList, $product->type, "class='form-control'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->status;?></th>
        <td><?php echo html::select('status', $lang->product->statusList, $product->status, "class='form-control'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($product->desc), "rows='8' class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->acl;?></th>
        <td colspan='2'><?php echo nl2br(html::radio('acl', $lang->product->aclList, $product->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
      </tr>  
      <tr id='whitelistBox' <?php if($product->acl != 'custom') echo "class='hidden'";?>>
        <th><?php echo $lang->product->whitelist;?></th>
        <td colspan='2'><?php echo html::checkbox('whitelist', $groups, $product->whitelist);?></td>
      </tr>  
      <tr><td></td><td colspan='2'><?php echo html::submitButton();?></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
