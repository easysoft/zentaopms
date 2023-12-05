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
<?php if(!helper::isAjaxRequest()):?>
<div>
  <div class="modal-content">
    <div class="modal-header">
      <strong class="modal-title"><?php if(!empty($title)) echo $title; ?></strong>
      <?php if(!empty($subtitle)) echo "<label class='text-important'>" . $subtitle . '</label>'; ?>
    </div>
    <div class="modal-body">
<?php endif;?>
<form id='ajaxForm' method='post' action="<?php echo inlink('edit', "id=$account->id&from=$from");?>">
  <table class='table table-form'>
    <tr>
      <th class='w-100px'><?php echo $lang->account->name?></th>
      <td class='required'><?php echo html::input('name', "$account->name", "class='form-control'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->provider;?></th>
      <td class='required'><?php echo html::select('provider', $lang->serverroom->providerList, $account->provider, "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->adminURI?></th>
      <td><?php echo html::input('adminURI', "$account->adminURI", "class='form-control'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->account;?></th>
      <td class='required'><?php echo html::input('account', "$account->account", "class='form-control'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->password;?></th>
      <td><?php echo html::input('password', "$account->password", "class='form-control'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->email;?></th>
      <td><?php echo html::input('email', "$account->email", "class='form-control'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->account->mobile;?></th>
      <td><?php echo html::input('mobile', "$account->mobile", "class='form-control'")?></td>
    </tr>
    <tr>
      <td colspan='2' class='text-center form-actions'>
        <?php echo html::submitButton();?>
        <?php echo html::backButton('', '', 'btn btn-wide');?>
      </td>
    </tr>
  </table>
</form>
<?php if(!helper::isAjaxRequest()):?>
    </div>
  </div>
</div>
<?php endif;?>
<?php include '../../common/view/footer.modal.html.php';?>
