<form id='smtpForm' class='cell load-indicator main-form form-ajax'>
  <h4>
    <?php $enableSMTP= $this->system->smtpSnippetName();?>
    <?php echo html::checkbox('enableSMTP', array('true' => $lang->system->SMTP->enabled), $enableSMTP ? 'true' : '', (($smtpLinked or $activeSMTP) ? "onclick='return false;'" : ''));?>
  </h4>
  <table class="table table-form">
    <tbody>
      <tr>
        <th><?php echo $lang->system->SMTP->account;?></th>
        <td class='required w-300px'><?php echo html::input('user', zget($smtpSettings, 'SMTP_USER', ''), "class='form-control' placeholder='example@smtp.com'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->system->SMTP->password;?></th>
        <td class='required'><?php echo html::input('pass', zget($smtpSettings, 'SMTP_PASS', ''), "class='form-control' placeholder=''");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->system->SMTP->host;?></th>
        <td class='required'><?php echo html::input('host', zget($smtpSettings, 'SMTP_HOST', ''), "class='form-control' placeholder=''");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->system->SMTP->port;?></th>
        <td class='required'><?php echo html::input('port', zget($smtpSettings, 'SMTP_PORT', ''), "class='form-control' placeholder='25'");?></td>
        <td></td>
      </tr>
      <tr>
        <td class='w-100px text-right'><?php echo html::commonButton($lang->system->verify, "id='verifyAccountBtn'");?></td>
        <td class='w-300px text-left'><span id='verifyResult'></span></td>
        <td></td>
      </tr>
    </tbody>
  </table>
  <div class='text-center form-actions'><?php echo html::submitButton($activeSMTP ? $lang->system->SMTP->update : $lang->system->SMTP->install);?></div>
</form>
