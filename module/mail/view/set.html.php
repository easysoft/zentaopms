<?php
/**
 * The config email view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
include '../../common/view/header.html.php';
include '../../common/view/tablesorter.html.php';?>
<?php
if(!empty($mailConfig->gmail->username)) 
{
    $gmailUsername = $mailConfig->gmail->username;
}
else 
{
    $gmailUsername = '';
}
if(!empty($mailConfig->gmail->password)) 
{
    $gmailPassword = $mailConfig->gmail->password;
}
else 
{
    $gmailPassword = '';
}
if(!empty($mailConfig->smtp->username)) 
{
    $smtpUsername = $mailConfig->smtp->username;
}
else 
{
    $smtpUsername = '';
}
if(!empty($mailConfig->smtp->password)) 
{
    $smtpPassword = $mailConfig->smtp->password;
}
else 
{
    $smtpPassword = '';
}
if(!empty($mailConfig->smtp->host)) 
{
    $smtpHost = $mailConfig->smtp->host;
}
else 
{
    $smtpHost = '';
}
if(!empty($mailConfig->smtp->secure)) 
{
    $smtpSecure = $mailConfig->smtp->secure;
}
else 
{
    $smtpSecure = '';
}
if(!empty($mailConfig->smtp->auth)) 
{
    $smtpAuth = $mailConfig->smtp->auth;
}
else 
{
    $smtpAuth = '';
}
if(!empty($mailConfig->smtp->port)) 
{
    $smtpPort = $mailConfig->smtp->port;
}
else 
{
    $smtpPort = '';
}
?>
<form method='post' enctype='multipart/form-data' action='<?php echo inlink('save');?>'>
  <table align='center' class='table-1'>
  <caption><?php echo $lang->mail->setParam; ?></caption>
  <tr>
    <th class='rowhead'><?php echo $lang->mail->turnon; ?></th>
    <td><?php echo html::select('turnon', $lang->mail->turnonList, $mailConfig->turnon); ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->mail->mta; ?></th>
    <td><?php echo html::select('mta', $lang->mail->mtaList, $mailConfig->mta, 'class=select-3 onchange=setMtaType(this.value)'); ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->mail->fromAddress; ?></th>
    <td><?php echo html::input('fromAddress', $mailConfig->fromAddress) ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->mail->fromName; ?></th>
    <td><?php echo html::input('fromName', $mailConfig->fromName) ?></td>
  </tr>
  <tr id='gmailDebug' class='hidden'>
    <th><?php echo $lang->mail->gmail->debug; ?></th>
    <td><?php echo html::select('gmailDebug', $lang->mail->debugList, ''); echo $lang->mail->debugExample; ?></td>
  </tr>
  <tr id='gmailUsername' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->gmail->username; ?></th>
    <td><?php echo html::input('gmailUsername', $gmailUsername) ?></td>
  </tr>
  <tr id='gmailPassword' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->gmail->password; ?></th>
    <td><?php echo html::input('gmailPassword', $gmailPassword) ?></td>
  </tr>
  <tr id='smtpHost' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->host; ?></th>
    <td><?php echo html::input('smtpHost', $smtpHost); echo $lang->mail->smtp->hostInfo;?></td>
  </tr>
  <tr id='smtpDebug' class='hidden'>
    <th><?php echo $lang->mail->smtp->debug; ?></th>
    <td><?php echo html::select('smtpDebug', $lang->mail->debugList, ''); echo $lang->mail->debugExample; ?></td>
  </tr>
  <tr id='smtpAuth' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->auth; ?></th>
    <td><?php echo html::select('smtpAuth', $lang->mail->smtp->authList, $smtpAuth, 'class=select-3 onchange=setVerificationType(this.value)'); ?></td>
  </tr>
  <tr id='smtpUsername' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->username; ?></th>
    <td><?php echo html::input('smtpUsername', $smtpUsername) ?></td>
  </tr>
  <tr id='smtpPassword' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->password; ?></th>
    <td><?php echo html::input('smtpPassword', $smtpPassword) ?></td>
  </tr>
  <tr id='smtpSecure' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->secure; ?></th>
    <td><?php echo html::select('smtpSecure', $lang->mail->smtp->secureList, $smtpSecure); ?></td>
  </tr>
  <tr id='smtpPort' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->port; ?></th>
    <td><?php echo html::input('smtpPort', $smtpPort); echo $lang->mail->smtp->portInfo ?></td>
  </tr>
  <tr>
    <td colspan='2' class='a-center'><?php echo html::submitButton();?></td>
  </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
