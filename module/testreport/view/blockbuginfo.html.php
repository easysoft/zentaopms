<tr>
  <th rowspan='4' class='text-top'><?php echo $lang->testreport->bugInfo?></th>
  <td class='text-top'>
    <div class='input-group' id='bugConfirmedRate'>
      <span class='input-group-addon'><?php echo $lang->testreport->bugConfirmedRate?></span>
      <span class='input-group-addon'><?php echo $bugConfirmedRate . '%'?></span>
      <span class='input-group-addon'><?php echo $lang->testreport->bugCreateByCaseRate?></span>
      <span class='input-group-addon'><?php echo $bugCreateByCaseRate . '%'?></span>
    </div>
  </td>
  <td></td>
</tr>
<tr>
  <td class='text-top'>
    <table class='table' id='bugSeverityGroups'>
      <caption><?php echo $lang->testreport->bugSeverityGroups?></caption>
      <tr>
        <th class='text-right w-100px'><?php echo $lang->bug->severity?></th>
        <th><?php echo $lang->testreport->value?></th>
      </tr>
      <?php foreach($lang->bug->severityList as $severityKey => $severityValue):?>
      <tr>
        <td class='text-right'><?php echo $severityValue === '' ? 'null' : $severityValue?></td>
        <td><?php echo zget($bugSeverityGroups, $severityKey, 0);?></td>
      </tr>
      <?php endforeach?>
    </table>
  </td>
  <td class='text-top'>
    <table class='table' id='bugStatusGroups'>
      <caption><?php echo $lang->testreport->bugStatusGroups?></caption>
      <tr>
        <th class='text-right w-100px'><?php echo $lang->bug->status?></th>
        <th><?php echo $lang->testreport->value?></th>
      </tr>
      <?php foreach($lang->bug->statusList as $statusKey => $statusValue):?>
      <?php if(!isset($bugStatusGroups['']) and $statusValue === '') continue;?>
      <tr>
        <td class='text-right'><?php echo $statusValue === '' ? 'null' : $statusValue?></td>
        <td><?php echo zget($bugStatusGroups, $statusKey, 0);?></td>
      </tr>
      <?php endforeach?>
    </table>
  </td>
</tr>
<tr>
  <td class='text-top'>
    <table class='table' id='bugOpenedByGroups'>
      <caption><?php echo $lang->testreport->bugOpenedByGroups?></caption>
      <tr>
        <th class='text-right w-100px'><?php echo $lang->bug->openedBy?></th>
        <th><?php echo $lang->testreport->value?></th>
      </tr>
      <?php foreach($bugOpenedByGroups as $account => $num):?>
      <tr>
        <td class='text-right'><?php echo zget($users, $account)?></td>
        <td><?php echo $num;?></td>
      </tr>
      <?php endforeach?>
    </table>
  </td>
  <td class='text-top'>
    <table class='table' id='bugResolvedByGroups'>
      <caption><?php echo $lang->testreport->bugResolvedByGroups?></caption>
      <tr>
        <th class='text-right w-100px'><?php echo $lang->bug->resolvedBy?></th>
        <th><?php echo $lang->testreport->value?></th>
      </tr>
      <?php foreach($bugResolvedByGroups as $account => $num):?>
      <tr>
        <td class='text-right'><?php echo zget($users, $account)?></td>
        <td><?php echo $num;?></td>
      </tr>
      <?php endforeach?>
    </table>
  </td>
</tr>
<tr>
  <td class='text-top'>
    <table class='table' id='bugResolutionGroups'>
      <caption><?php echo $lang->testreport->bugResolutionGroups?></caption>
      <tr>
        <th class='text-right w-100px'><?php echo $lang->bug->resolution?></th>
        <th><?php echo $lang->testreport->value?></th>
      </tr>
      <?php foreach($lang->bug->resolutionList as $resolutionKey => $resolutionValue):?>
      <tr>
        <td class='text-right'><?php echo $resolutionValue === '' ? 'null' : $resolutionValue?></td>
        <td><?php echo zget($bugResolutionGroups, $resolutionKey, 0);?></td>
      </tr>
      <?php endforeach?>
    </table>
  </td>
  <td class='text-top'>
    <table class='table' id='bugModuleGroups'>
      <caption><?php echo $lang->testreport->bugModuleGroups?></caption>
      <tr>
        <th class='text-right w-100px'><?php echo $lang->bug->module?></th>
        <th><?php echo $lang->testreport->value?></th>
      </tr>
      <?php ksort($bugModuleGroups)?>
      <?php foreach($bugModuleGroups as $moduleKey => $num):?>
      <tr>
        <td class='text-right'><?php echo zget($modules, $moduleKey);?></td>
        <td><?php echo $num;?></td>
      </tr>
      <?php endforeach?>
    </table>
  </td>
</tr>
