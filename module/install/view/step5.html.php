<?php
/**
 * The html template file of step4 method of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: step4.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('errorEmpty', $lang->install->errorEmpty);?>
<style>.modal-body table tr th{text-align: right}</style>
<div class='container'>
  <div class='modal-dialog'>
  <?php if(isset($error)):?>
    <div class='modal-header'>
      <strong><?php echo $lang->install->error;?></strong>
    </div>
    <div class='modal-body'>
      <div class='alert alert-danger alert-pure with-icon'>
        <i class='icon-exclamation-sign'></i>
        <div class='content'><?php echo $error;?></div>
      </div>
    </div>
    <div class='modal-footer'>
      <?php echo html::commonButton($lang->install->pre, "onclick='javascript:history.back(-1)'");?>
    </div>
  <?php elseif(isset($success)):?>
    <div class='modal-header'>
      <strong><?php echo $lang->install->success;?></strong>
    </div>
    <div class='modal-body'>
      <div class='alert alert-success alert-pure with-icon'>
        <i class='icon-check-circle'></i>
        <div class='content'><?php echo $afterSuccess;?></div>
      </div>
    </div>
    <div class='modal-footer'>
      <?php echo html::commonButton($lang->install->pre, "onclick='javascript:history.back(-1)'");?>
    </div>
  <?php else:?>
    <div class='modal-header'>
      <strong><?php echo $lang->install->getPriv;?></strong>
    </div>
    <div class='modal-body'>
      <form method='post' target='hiddenwin'>
        <table class='table table-form mw-400px' style='margin: 0 auto'>
          <tr>
            <th class='c-company'><?php echo $lang->install->company;?></th>
            <td class='c-company-value'><?php echo html::input('company', '', "class='form-control'");?></td>
          </tr>
          <tr class='hidden'>
            <th><?php echo $lang->install->working;?></th>
            <td><?php echo html::select('flow', $lang->install->workingList, 'full', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->install->account;?></th>
            <td><?php echo html::input('account', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->install->password;?></th>
            <td><?php echo html::input('password', '', "class='form-control' placeholder='{$lang->install->placeholder->password}' oninput=\"this.value = this.value.replace(/[^\\x00-\\xff]/g, '');\"");?></td>
          </tr>
          <tr>
            <th></th><td><?php echo html::checkBox('importDemoData', $lang->install->importDemoData);?></td>
          </tr>
          <tr class='text-center'>
            <td colspan='2'><?php echo html::submitButton();?></td>
          </tr>
        </table>
      </form>
    </div>
  <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
