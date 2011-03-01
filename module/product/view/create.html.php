<?php
/**
 * The create view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' target='hiddenwin'>
  <table class='table-1'> 
    <caption><?php echo $lang->product->create;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->product->name;?></th>
      <td><?php echo html::input('name', '', "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->product->code;?></th>
      <td><?php echo html::input('code', '', "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->product->PO;?></th>
      <td><?php echo html::select('PO', $users, $this->app->user->account, "class='select-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->product->QM;?></th>
      <td><?php echo html::select('QM', $users, $this->app->user->account, "class='select-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->product->RM;?></th>
      <td><?php echo html::select('RM', $users, $this->app->user->account, "class='select-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->product->desc;?></th>
      <td><?php echo html::textarea('desc', '', "rows='8' class='area-1'");?></textarea></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->product->acl;?></th>
      <td><?php echo nl2br(html::radio('acl', $lang->product->aclList, 'open', "onclick='setWhite(this.value);'"));?></td>
    </tr>  
    <tr id='whitelistBox' class='hidden'>
      <th class='rowhead'><?php echo $lang->product->whitelist;?></th>
      <td><?php echo html::checkbox('whitelist', $groups);?></td>
    </tr>  
    <tr><td colspan='2' class='a-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
