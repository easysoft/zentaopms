<form id='LDAPForm' class='cell not-watch load-indicator main-form form-ajax'>
  <h4>
    <?php $enableLDAP = $this->system->hasSystemLDAP();?>
    <?php echo html::checkbox('enableLDAP', array('true' => $lang->system->LDAP->ldapEnabled), $enableLDAP ? 'true' : '', (($ldapLinked or $activeLDAP) ? "onclick='return false;'" : '')); ?>
  </h4>
  <table class='table table-form'>
    <tbody>
      <tr>
        <th><?php echo $lang->system->LDAP->ldapSource;?></th>
        <td class='required w-300px'><?php echo html::select('source', $lang->system->ldapTypeList, $activeLDAP,"class='form-control'");?></td>
        <td></td>
      </tr>
    </tbody>
  </table>
  <table id='quchengLDAP' class='table table-form'>
    <tbody>
      <tr>
        <th><?php echo $lang->system->LDAP->ldapUsername?></th>
        <td><?php echo $ldapApp->account->username;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->system->LDAP->ldapRoot;?></th>
        <td><?php echo 'dc=quickon,dc=org';?></td>
      <tr>
    </tbody>
  </table>
  <div id='extraLDAP'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th><?php echo $lang->system->LDAP->host;?></th>
          <td class='required w-300px'><?php echo html::input('extra[host]', zget($ldapSettings, 'host', ''), "class='form-control required' placeholder='192.168.1.1'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->LDAP->port;?></th>
          <td class='required'><?php echo html::input('extra[port]',  zget($ldapSettings, 'port', ''), "class='form-control' placeholder='389'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->LDAP->ldapUsername;?></th>
          <td class='required'><?php echo html::input('extra[bindDN]', zget($ldapSettings, 'bindDN', ''), "class='form-control' placeholder='admin'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->LDAP->password;?></th>
          <td class='required'><?php echo html::input('extra[bindPass]', zget($ldapSettings, 'bindPass', ''), "class='form-control' placeholder='******'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->LDAP->ldapRoot;?></th>
          <td class='required'><?php echo html::input('extra[baseDN]', zget($ldapSettings, 'baseDN', ''), "class='form-control' placeholder='dc=quickon,dc=org'");?></td>
          <td></td>
        </tr>
      </tbody>
    </table>
    <div class='advanced'><?php echo html::a("#advanced-settings", $lang->system->LDAP->ldapAdvance . "<i class='icon icon-chevron-double-down'></i>", '', "data-toggle='collapse'");?></div>
    <table class="collapse table table-form" id="advanced-settings">
      <tbody>
          <tr>
            <th><?php echo $lang->system->LDAP->filterUser;?></th>
            <td class='w-300px'><?php echo html::input('extra[filter]', zget($ldapSettings, 'filter', '&(objectClass=posixAccount)(uid=%s)'), "class='form-control' placeholder='&(objectClass=posixAccount)(cn=%s)'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->system->LDAP->email;?></th>
            <td class='w-300px'><?php echo html::input('extra[attrEmail]', zget($ldapSettings, 'attrEmail', 'mail'), "class='form-control' placeholder='mail'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->system->LDAP->extraAccount;?></th>
            <td class='w-300px'><?php echo html::input('extra[attrUser]', zget($ldapSettings, 'attrUser', 'uid'), "class='form-control' placeholder='uid'");?></td>
            <td></td>
          </tr>
      </tbody>
    </table>
    <table class='table table-form'>
      <tbody>
        <tr>
          <td class='w-100px text-right'><?php echo html::commonButton($lang->system->verify, "id='testConnectBtn'");?></td>
          <td class='w-300px text-left'><span id='connectResult'></span></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class='text-center form-actions'><?php echo html::commonButton($activeLDAP ? $lang->system->LDAP->ldapUpdate : $lang->system->LDAP->ldapInstall, "id='submitBtn'", "btn btn-primary btn-wide");?></div>
</form>
<div class="modal fade" id="waiting" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-400px">
    <div class="modal-content">
      <div class="modal-body">
        <h4><?php echo $lang->system->LDAP->updateLDAP;?></h4>
        <div><span id='message'></span></div>
      </div>
    </div>
  </div>
</div>
