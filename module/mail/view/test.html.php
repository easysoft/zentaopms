<?php
/**
 * The test view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <wwccss@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(isset($error)):?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='alert alert-warning with-icon'><i class='icon-frown'></i><div class='content'><?php echo join('', $error);?></div></div>
<?php include '../../common/view/footer.lite.html.php';?>
<?php else:?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['mail']);?></span>
      <strong><?php echo $lang->mail->common;?></strong>
      <small class='text-muted'> <?php echo $lang->mail->test;?> <?php echo html::icon($lang->icons['test']);?></small>
    </div>
    <div class='actions'><div class='text text-info'><?php echo $lang->mail->sendmailTips;?></div></div>
  </div>
  <form class='form-condensed' method='post' target='resultWin'>
    <table class='table table-form'>
      <tr>
        <td><?php echo html::select('to', $users, $app->user->account, "class='form-control chosen'");?></td>
        <td class='text-left'>
          <?php 
          echo html::submitButton($lang->mail->test);
          echo html::linkButton($lang->mail->edit, inLink('edit'));
          ?>
        </td>
      </tr>
    </table>
  </form>
  <table class='table table-form'><tr><td><iframe id='resultWin' name='resultWin'></iframe></td></tr></table>
</div>
<?php include '../../common/view/footer.html.php';?>
<?php endif;?>
