<?php
/**
 * The create view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitlab
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->gitlab->lblCreate;?></h2>
      </div>
      <form id='gitlabForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->gitlab->name; ?></th>
            <td class='required'><?php echo html::input('name', '', "class='form-control'"); ?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->url; ?></th>
            <td class='required'><?php echo html::input('url', '', "class='form-control'"); ?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->account;?></th>
            <td><?php echo html::input('account', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->token;?></th>
            <td><?php echo html::input('token', '', "class='form-control'");?></td>
            <td><?php echo $lang->gitlab->tokenFirst;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->password;?></th>
            <td><?php echo html::password('password', '', "class='form-control'");?></td>
            <td><?php echo $lang->gitlab->tips;?></td>
          </tr>
          <tr>
            <th></th>
            <td class='text-center form-actions'>
              <?php echo html::submitButton(); ?>
              <?php echo html::backButton(); ?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
