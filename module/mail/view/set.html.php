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
$turnon        = 'false';
$mta           = 'smtp';
$gmailUsername = '';
$gmailPassword = '';
$smtpUsername  = '';
$smtpPassword  = '';
$smtpHost      = '';
$smtpSecure    = '';
$smtpAuth      = '';
$smtpPort      = '';
if($mailConfig->turnon == 1)             $turnon        = 'ture';
if(!empty($mailConfig->mta))             $mta           = $mailConfig->mta;
if(!empty($mailConfig->gmail->username)) $gmailUsername = $mailConfig->gmail->username;
if(!empty($mailConfig->gmail->password)) $gmailPassword = $mailConfig->gmail->password;
if(!empty($mailConfig->smtp->username))  $smtpUsername  = $mailConfig->smtp->username;
if(!empty($mailConfig->smtp->password))  $smtpPassword  = $mailConfig->smtp->password;
if(!empty($mailConfig->smtp->host))      $smtpHost      = $mailConfig->smtp->host;
if(!empty($mailConfig->smtp->secure))    $smtpSecure    = $mailConfig->smtp->secure;
if(!empty($mailConfig->smtp->auth))      $smtpAuth      = $mailConfig->smtp->auth;
if(!empty($mailConfig->smtp->port))      $smtpPort      = $mailConfig->smtp->port;
?>
<form method='post' enctype='multipart/form-data' action='<?php echo inlink('save');?>'>
  <table align='center' class='table-1'>
  <caption><?php echo $lang->mail->setParam; ?></caption>
  <tr>
    <th class='rowhead w-200px'><?php echo $lang->mail->turnon; ?></th>
    <td><?php echo html::select('turnon', $lang->mail->turnonList, $turnon, 'class=select-3'); ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->mail->mta; ?></th>
    <td><?php echo html::select('mta', $lang->mail->mtaList, $mta, 'class=select-3 onchange=setMtaType(this.value)'); ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->mail->fromAddress; ?></th>
    <td><?php echo html::input('fromAddress', $mailConfig->fromAddress, 'class=text-3') ?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->mail->fromName; ?></th>
    <td><?php echo html::input('fromName', $mailConfig->fromName, 'class=text-3') ?></td>
  </tr>
  <tr id='gmailDebug' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->gmail->debug; ?></th>
    <td>
      <?php 
      echo html::select('gmailDebug', $lang->mail->debugList, isset($mailConfig->gmail->debug) ? $mailConfig->gmail->debug : '', 'class=select-3'); 
      echo $lang->mail->debugExample; 
      ?>
    </td>
  </tr>
  <tr id='gmailUsername' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->gmail->username; ?></th>
    <td><?php echo html::input('gmailUsername', $gmailUsername, 'class=text-3') ?></td>
  </tr>
  <tr id='gmailPassword' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->gmail->password; ?></th>
    <td><?php echo html::password('gmailPassword', $gmailPassword, 'class="text-3" autocomplete="off"') ?></td>
  </tr>
  <tr id='smtpHost' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->host; ?></th>
    <td><?php echo html::input('smtpHost', $smtpHost, 'class=text-3'); echo $lang->mail->smtp->hostInfo;?></td>
  </tr>
  <tr id='smtpDebug' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->debug; ?></th>
    <td>
      <?php
      echo html::select('smtpDebug', $lang->mail->debugList, isset($mailConfig->smtp->debug) ? $mailConfig->smtp->debug : '', 'class=select-3'); 
      echo $lang->mail->debugExample; 
      ?>
    </td>
  </tr>
  <tr id='smtpAuth' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->auth; ?></th>
    <td><?php echo html::select('smtpAuth', $lang->mail->smtp->authList, $smtpAuth, 'class=select-3 onchange=setVerificationType(this.value)'); ?></td>
  </tr>
  <tr id='smtpUsername' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->username; ?></th>
    <td><?php echo html::input('smtpUsername', $smtpUsername, 'class=text-3') ?></td>
  </tr>
  <tr id='smtpPassword' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->password; ?></th>
    <td><?php echo html::password('smtpPassword', $smtpPassword, 'class="text-3" autocomplete="off"') ?></td>
  </tr>
  <tr id='smtpSecure' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->secure; ?></th>
    <td><?php echo html::select('smtpSecure', $lang->mail->smtp->secureList, $smtpSecure, 'class=select-3'); ?></td>
  </tr>
  <tr id='smtpPort' class='hidden'>
    <th class='rowhead'><?php echo $lang->mail->smtp->port; ?></th>
    <td><?php echo html::input('smtpPort', $smtpPort, 'class=text-3'); echo $lang->mail->smtp->portInfo ?></td>
  </tr>
  <tr>
    <td colspan='2' class='a-center'><?php echo html::submitButton();?></td>
  </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
