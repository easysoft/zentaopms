<?php
/**
 * The create view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: create.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['product']);?></span>
      <strong><small class='text-muted'><i class='icon icon-plus'></i></small> <?php echo $lang->product->create;?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <tr>
        <th class='w-90px'><?php echo $lang->product->name;?></th>
        <td class='w-p25-f'><?php echo html::input('name', '', "class='form-control'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->code;?></th>
        <td><?php echo html::input('code', '', "class='form-control'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->PO;?></th>
        <td><?php echo html::select('PO', $poUsers, $this->app->user->account, "class='form-control chosen'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->QD;?></th>
        <td><?php echo html::select('QD', $qdUsers, '', "class='form-control chosen'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->RD;?></th>
        <td><?php echo html::select('RD', $rdUsers, '', "class='form-control chosen'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', '', "rows='8' class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->product->acl;?></th>
        <td colspan='2'><?php echo nl2br(html::radio('acl', $lang->product->aclList, 'open', "onclick='setWhite(this.value);'", 'block'));?></td>
      </tr>  
      <tr id='whitelistBox' class='hidden'>
        <th><?php echo $lang->product->whitelist;?></th>
        <td colspan='2'><?php echo html::checkbox('whitelist', $groups);?></td>
      </tr>  
      <tr><td></td><td colspan='2'><?php echo html::submitButton();?></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
