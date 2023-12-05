<?php
/**
 * The test view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <wwccss@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(isset($error)):?>
<?php include '../../common/view/header.lite.html.php';?>
<style>body{background:#fff;}</style>
<div class='alert alert-warning with-icon'><i class='icon-frown'></i><div class='content'><?php echo join('', $error);?></div></div>
<?php include '../../common/view/footer.lite.html.php';?>
<?php else:?>
<?php include $this->app->getModuleRoot() . 'message/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-700px'>
    <div class='main-header'>
      <h2>
        <?php echo $lang->mail->common;?>
        <small class='text-muted'> <?php echo $lang->arrow . $lang->mail->test;?></small>
      </h2>
      <div class='pull-right btn-toolbar'><div class='text text-info'><?php echo $lang->mail->sendmailTips;?></div></div>
    </div>
    <form method='post' target='resultWin'>
      <table class='table table-form'>
        <tr>
          <td><?php echo html::select('to', $users, $app->user->account, "class='form-control chosen'");?></td>
          <td class='text-left'>
            <?php 
            echo html::submitButton($lang->mail->test, '', 'btn btn-primary');
            $mta = $config->mail->mta;
            echo html::linkButton($lang->mail->edit, inlink(($mta == 'sendcloud' or $mta == 'ztcloud') ? $mta : 'edit'));
            ?>
          </td>
        </tr>
      </table>
    </form>
    <table class='table table-form'><tr><td><iframe id='resultWin' name='resultWin'></iframe></td></tr></table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
<?php endif;?>
