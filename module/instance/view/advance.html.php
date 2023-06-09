<div class='panel'>
  <?php $ips = (array) zget($domain,'load_balancer_ips', new stdclass);?>
  <?php if(!empty($ips)):?>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->instance->visitIP;?></span></div>
  </div>
  <div class='panel-body'>
      <table class='table table-form cell'>
        <?php foreach($ips as $key => $value):?>
        <tr>
          <th><?php echo $key;?></th>
          <td><?php echo $value;?></td>
        </tr>
        <?php endforeach;?>
      </table>
  </div>
  <?php endif;?>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->instance->mem;?></span></div>
  </div>
  <div class='panel-body'>
    <form id='memoryForm' class='cell not-watch load-indicator'>
      <table class='table table-form'>
        <tr>
          <th></th>
          <td colspan="3">
            <span class='label label-info'><?php echo $lang->instance->currentMemory;?>：<?php echo helper::formatKB($currentResource->resources->memory / 1024);?></span>
            <span class='label label-warning'><?php $this->instance->printSuggestedMemory($instanceMetric->memory, $lang->instance->memOptions);?></span>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->instance->adjustMem;?></th>
          <td class='w-100px'><?php echo html::select('memory_kb', $this->instance->filterMemOptions($currentResource), '', "class='form-control'");?></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <th></th>
          <td class='text-center'>
            <?php echo html::commonButton($lang->instance->saveSetting, "id='memBtn' instance-id='$instance->id'", 'btn btn-primary'); ?>
          </td>
          <td></td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
  <?php if(isset($cloudApp->features->ldap)):?>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->system->ldapManagement;?></span></div>
  </div>
  <div class='panel-body'>
    <form id='LDAPForm' class='cell not-watch load-indicator'>
      <table class='table table-form'>
        <tr>
          <?php $LDAPInstalled =  $this->system->hasSystemLDAP();?>
          <?php $enableLDAP    =  $instance->ldapSnippetName ? 'true' : '' ;?>
          <td class='w-120px'><?php echo html::checkbox('enableLDAP', array('true' => $lang->instance->enableLDAP),  $enableLDAP, ($LDAPInstalled ? '' : 'disabled'));?></td>
          <td colspan='2'>
            <?php if(!$LDAPInstalled):?>
            <?php echo $lang->instance->systemLDAPInactive;?>
            <?php echo html::a(helper::createLink('system', 'installLDAP'), $lang->instance->toSystemLDAP, '', "class='btn btn-default'");?>
            <?php endif?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th></th>
          <td class='w-100px text-center'>
            <?php echo html::commonButton($lang->instance->saveSetting, "id='ldapBtn' instance-id='$instance->id'" . ($LDAPInstalled ? '' : 'disabled'), 'btn btn-primary');?>
          </td>
          <td></td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
  <?php endif;?>
  <?php if(isset($cloudApp->features->mail)):?>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->system->SMTP->common;?></span></div>
  </div>
  <div class='panel-body'>
    <form id='SMTPForm' class='cell not-watch load-indicator'>
      <table class='table table-form'>
        <tr>
          <?php $SMTPInstalled =  $this->system->isSMTPEnabled();?>
          <?php $enableSMTP    =  $instance->smtpSnippetName ? 'true' : '' ;?>
          <td class='w-120px'><?php echo html::checkbox('enableSMTP', array('true' => $lang->instance->enableSMTP),  $enableSMTP, ($SMTPInstalled ? '' : 'disabled'));?></td>
          <td colspan='2'>
            <?php if(!$SMTPInstalled):?>
            <?php echo $lang->instance->systemSMTPInactive;?>
            <?php echo html::a(helper::createLink('system', 'installSMTP'), $lang->instance->toSystemSMTP, '', "class='btn btn-default'");?>
            <?php endif?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th></th>
          <td class='w-100px text-center'>
            <?php echo html::commonButton($lang->instance->saveSetting, "id='smtpBtn' instance-id='$instance->id'" . ($SMTPInstalled ? '' : 'disabled'), 'btn btn-primary');?>
          </td>
          <td></td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
  <?php endif;?>

  <?php if(!empty($currentResource->scalable) && $config->edition == 'biz'):?>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->instance->scalable;?></span></div>
  </div>
  <div class='panel-body'>
    <form id='replicasForm' class='cell not-watch load-indicator'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->instance->componentFields['replicas'];?></th>
          <td class='w-80px center'>
            <span id="replicas-text"><?php echo $currentResource->replicas;?></span>
            <?php echo html::select('scalable', array(1=>1, 2=>2, 3=>3, 4=>4), intval($currentResource->replicas) ? : 1, " id='replicas-input' max='4' min='1' class='form-control scalable-input hide'");?>
          </td>
          <td>
            <?php echo html::commonButton($lang->instance->change, 'id="replicas-edit"', 'btn btn-primary ' . (in_array($instance->status, array('uninstalling', 'destroying', 'destroyed', 'unknown', 'abnormal')) ? 'disabled' : ''));?>
            <?php echo html::commonButton($lang->instance->saveSetting, "id='replicas-save' instance-id='$instance->id'", 'btn btn-primary hide');?>
          </td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
  <?php endif;?>

  <?php if(count($customItems)):?>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->instance->customSetting;?></span></div>
  </div>
  <div class='panel-body'>
    <form id='customForm' class='cell not-watch load-indicator'>
      <table class='table table-form'>
        <?php foreach($customItems as $item):?>
        <tr>
          <th><?php echo $item->label;?></th>
          <td>
            <?php echo html::input($item->name, $item->default, "class='form-control' placeholder='{$item->label}'");?>
          </td>
          <td></td>
          <td></td>
        </tr>
        <?php endforeach;?>
        <tr>
          <th></th>
          <td class='text-center'>
            <?php echo html::commonButton($lang->instance->saveSetting, "id='customBtn' instance-id='$instance->id'", 'btn btn-primary'); ?>
           </td>
          <td></td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
  <?php endif;?>
  <?php if(!empty($dbList)):?>
  <hr/>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->instance->dbList;?></div>
  </div>
  <div class='panel-body'>
    <table class='table table-bordered text-center'>
      <thead>
        <tr>
          <th><?php echo $lang->instance->dbName;?></th>
          <th><?php echo $lang->instance->dbType;?></th>
          <th><?php echo $lang->instance->dbStatus;?></th>
          <th><?php echo $lang->instance->action;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($dbList as $db):?>
        <tr>
          <td><?php echo $db->db_name;?></td>
          <td><?php echo $db->db_type;?></td>
          <td><?php echo $db->ready ? $lang->instance->dbReady : $lang->instance->dbWaiting;?></td>
          <td><?php $this->instance->printDBAction($db, $instance);?></td>
        <tr>
        <?php endforeach;?>
      <tbody>
    </table>
  </div>
  <?php endif;?>
</div>
