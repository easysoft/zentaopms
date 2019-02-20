<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-500px'>
    <div class='main-header'>
      <h2>
        <span class='prefix'><?php echo html::icon('cloud');?></span>
        <?php echo $lang->admin->bind->caption;?>
      </h2>
    </div>
    <form class='mw-400px' method="post" target="hiddenwin">
      <table class='table table-form'>
        <tr>
          <th class='w-100px'><?php echo $lang->user->account;?></th>
          <?php
          $account = zget($config->global, 'community', '');
          if($account == 'na') $account = '';
          ?>
          <td><?php echo html::input('account', $account, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->password;?></th>
          <td><?php echo html::password('password', '', "class='form-control'");?></td>
        </tr>  
        <tr>
          <td colspan='2' class="text-center">
            <?php
            echo html::submitButton();
            echo html::hidden('sn', $sn) . html::hidden('site', common::getSysURL());
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
