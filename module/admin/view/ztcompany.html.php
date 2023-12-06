<?php
/**
 * The ztcomany view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-500px'>
    <div class='main-header'>
      <h2><?php echo $lang->admin->ztCompany;?></h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <?php foreach($fields as $field):?>
        <tr>
          <th><?php echo $field == 'company' ? $lang->company->name : $lang->user->$field;?></th>
          <td><?php echo html::input($field, '', "class='form-control'");?></td>
        </tr>
        <?php endforeach;?>
        <tr>
          <td colspan='2' class='text-center'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
