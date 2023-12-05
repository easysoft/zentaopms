<?php
/**
 * The view view file of account module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     account
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->account->confirmDelete)?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->account->view;?></h2>
  </div>
  <div class='main'>
    <div class='detail'>
    <table class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->account->name?></th>
        <td class='w-p25-f'><?php echo $account->name; ?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->account->provider;?></th>
        <td><?php echo zget($lang->serverroom->providerList, $account->provider);?></td>
      </tr>
      <tr>
        <th><?php echo $lang->account->adminURI?></th>
        <td><?php echo $account->adminURI; ?>
      </tr>
      <tr>
        <th><?php echo $lang->account->account;?></th>
        <td><?php echo $account->account; ?>
      </tr>
      <tr>
        <th><?php echo $lang->account->password;?></th>
        <td><?php echo $account->password; ?>
      </tr>
      <tr>
        <th><?php echo $lang->account->email;?></th>
        <td><?php echo $account->email; ?>
      </tr>
      <tr>
        <th><?php echo $lang->account->mobile;?></th>
        <td><?php echo $account->mobile; ?>
      </tr>
    </table>
    </div>
    <?php include $app->getModuleRoot() . 'common/view/action.html.php'?>
  </div>
  <div id='mainActions' class='main-actions'>
    <nav class='container'></nav>
    <div class='btn-toolbar'>
      <?php
      common::printLink('account', 'edit', "id=$account->id", "<i class='icon-edit'></i> " . $lang->edit, '', "class='btn iframe'", '', true, $account);
      if(!isonlybody()) common::printLink('account', 'browse', "", "<i class='icon-goback icon-back'></i> " . $lang->goback, '', "class='btn'");
      ?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
