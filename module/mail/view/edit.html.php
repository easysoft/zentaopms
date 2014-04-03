<?php
/**
 * The edit view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <wwccss@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
include '../../common/view/header.html.php';
?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['mail']);?></span>
      <strong><?php echo $lang->mail->common;?></strong>
      <small class='text-muted'> <?php echo $lang->mail->edit;?> <?php echo html::icon('pencil');?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' action='<?php echo inlink('save');?>' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th class='rowhead w-120px'><?php echo $lang->mail->turnon; ?></th>
        <td><?php echo html::radio('turnon', $lang->mail->turnonList, 1);?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->fromAddress; ?></th>
        <td><?php echo html::input('fromAddress', $mailConfig->fromAddress, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->fromName; ?></th>
        <td><?php echo html::input('fromName', $mailConfig->fromName, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->host; ?></th>
        <td><?php echo html::input('host', $mailConfig->host, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->port; ?></th>
        <td><?php echo html::input('port', $mailConfig->port, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->auth; ?></th>
        <td><?php echo html::radio('auth', $lang->mail->authList, $mailConfig->auth, 'onchange=setAuth(this.value)'); ?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->username; ?></th>
        <td><?php echo html::input('username', $mailConfig->username, "class='form-control'") ?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->password; ?></th>
        <td><?php echo html::password('password', $mailConfig->password, 'class="form-control" autocomplete="off"') ?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->secure; ?></th>
        <td><?php echo html::radio('secure', $lang->mail->secureList, $mailConfig->secure); ?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->debug; ?></th>
        <td><?php echo html::radio('debug', $lang->mail->debugList, $mailConfig->debug);?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->charset; ?></th>
        <td><?php echo html::radio('charset', $config->charsets[$this->cookie->lang], $mailConfig->charset);?></td>
      </tr>

      <tr>
         <td colspan='2' class='text-center'>
           <?php 
           echo html::submitButton();
           if($this->config->mail->turnon and $mailExist) echo html::linkButton($lang->mail->test, inlink('test'));
           echo html::linkButton($lang->mail->reset, inlink('reset'));
           ?>
         </td>
       </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
