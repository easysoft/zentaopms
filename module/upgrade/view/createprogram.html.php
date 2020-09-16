<table class='table table-form'>
  <caption class='strong'><?php echo $lang->upgrade->program;?></caption>
  <?php if($programs):?>
  <tr>
    <th><?php echo $lang->upgrade->existPGM;?></th>
    <td>
      <div class='input-group'>
        <?php echo html::select("programs", $programs, '', "class='form-control'");?>
        <span class='input-group-addon'>
          <div class="checkbox-primary">
            <input type="checkbox" name="newProgram" value="0" checked onchange="toggleProgram(this)" id="newProgram0" />
            <label for="newProgram0"><?php echo $lang->upgrade->newProgram;?></label>
          </div>
        </span>
      </div>
    </td>
  </tr>
  <?php endif;?>
  <tr class='pgmParams'>
    <th class='w-90px'><?php echo $lang->program->name;?></th>
    <td class='required'><?php echo html::input("name", isset($programName) ? $programName : '', "class='form-control'");?></td>
  </tr>
  <tr class='pgmParams'>
    <th><?php echo $lang->program->code;?></th>
    <td class='required'><?php echo html::input("code", '', "class='form-control'");?></td>
  </tr>
  <tr class='pgmParams'>
    <th><?php echo $lang->upgrade->PRJadmin;?></th>
    <td class='required'><?php echo html::select('PRJadmins[]', $users, '', "class='form-control chosen' multiple");?></td>
  </tr>
  <tr class='pgmParams'>
    <th><?php echo $lang->program->PM;?></th>
    <td><?php echo html::select('PM', array('' => '') + $users, '', "class='form-control chosen'");?></td>
  </tr>
  <tr class='pgmParams'>
    <th><?php echo $lang->program->budget;?></th>
    <td>
      <div class='input-group'>
        <?php echo html::input('budget', '', "class='form-control'");?>
        <span class='input-group-addon'></span>
        <?php echo html::select('budgetUnit', $lang->program->unitList, 'yuan', "class='form-control'");?>
      </div>
    </td>
  </tr>
  <tr class='pgmParams'>
    <th><?php echo $lang->program->dateRange;?></th>
    <td>
      <div class='input-group'>
        <?php echo html::input('begin', date('Y-m-d'), "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->begin . "' required");?>
        <span class='input-group-addon'><?php echo $lang->program->to;?></span>
        <?php echo html::input('end', '', "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->end . "' required");?>
      </div>
    </td>
  </tr>
  <tr class='pgmParams'>
    <th><?php echo $lang->project->days;?></th>
    <td>
      <div class='input-group'>
        <?php echo html::input('days', '', "class='form-control'");?>
        <span class='input-group-addon'><?php echo $lang->project->day;?></span>
      </div>
    </td>
  </tr>
  <tr class='pgmParams'>
    <th><?php echo $lang->project->acl;?></th>
    <td><?php echo nl2br(html::radio('acl', $lang->program->aclList, 'open', "onclick='setWhite(this.value);'", 'block'));?></td>
  </tr>
  <tr class='hidden' id='whitelistBox'>
    <th><?php echo $lang->project->whitelist;?></th>
    <td><?php echo html::checkbox('whitelist', $groups, '', '', '', 'inline');?></td>
  </tr>
</table>
