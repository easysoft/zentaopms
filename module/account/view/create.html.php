<?php
/**
 * The create view file of account module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      guanxiying <guanxiying@easycorp.ltd>
 * @package     account
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<form id='ajaxForm' method='post' action='<?php echo inlink('create');?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-100px'><?php echo $lang->account->name;?></th>
      <td class='required'><?php echo html::input('name', '', "class='form-control'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->provider;?></th>
      <td class='required'><?php echo html::select('provider', $lang->serverroom->providerList, '', "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->adminURI;?></th>
      <td><?php echo html::input('adminURI', '', "class='form-control'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->account;?></th>
      <td class='required'><?php echo html::input('account', '', "class='form-control'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->password;?></th>
      <td><?php echo html::input('password', '', "class='form-control'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->email;?></th>
      <td><?php echo html::input('email', '', "class='form-control'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->mobile;?></th>
      <td><?php echo html::input('mobile', '', "class='form-control'")?></td>
    </tr>
    <tr>
      <td colspan='2' class='text-center form-actions'>
        <?php echo html::submitButton();?>
        <?php if(!isonlybody()) echo html::backButton();?>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
