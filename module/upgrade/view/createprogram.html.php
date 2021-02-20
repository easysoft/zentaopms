<table class='table table-form'>
  <caption class='strong'><?php echo $lang->upgrade->program;?></caption>
  <?php if($programs):?>
  <tr>
    <th><?php echo $lang->upgrade->existPGM;?></th>
    <td class='required'>
      <div class='input-group'>
        <?php echo html::select("programs", $programs, '', "class='form-control' onchange='getProjectByProgram(this)'");?>
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
  <?php if(count($projects) > 1):?>
  <tr>
    <th><?php echo $lang->upgrade->existPRJ;?></th>
    <td class='required'>
      <div class='input-group'>
        <?php echo html::select("projects", $projects, '', "class='form-control'");?>
        <span class='input-group-addon'>
          <div class="checkbox-primary">
            <input type="checkbox" name="newProject" value="0" checked onchange="toggleProject(this)" id="newProject0" />
            <label for="newProject0"><?php echo $lang->upgrade->newProgram;?></label>
          </div>
        </span>
      </div>
    </td>
  </tr>
  <?php endif;?>
  <tr class='PGMParams'>
    <th class='w-90px'><?php echo $lang->program->PGMName;?></th>
    <td class='required'><?php echo html::input("PGMName", isset($programName) ? $programName : '', "class='form-control'");?></td>
  </tr>
  <tr class='PGMParams'>
    <th><?php echo $lang->program->PRJName;?></th>
    <td class='required'><?php echo html::input("PRJName", isset($sprintName) ? $sprintName : '', "class='form-control'");?></td>
  </tr>
  <tr class='PGMParams'>
    <th><?php echo $lang->program->PRJCode;?></th>
    <td><?php echo html::input("code", '', "class='form-control'");?></td>
  </tr>
  <tr class='PGMParams'>
    <th><?php echo $lang->program->PRJPM;?></th>
    <td><?php echo html::select('PM', array('' => '') + $users, '', "class='form-control chosen'");?></td>
  </tr>
  <tr class='PGMParams'>
    <th><?php echo $lang->program->dateRange;?></th>
    <td>
      <div class='input-group'>
        <?php echo html::input('begin', date('Y-m-d'), "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->begin . "' required");?>
        <span class='input-group-addon'><?php echo $lang->program->to;?></span>
        <?php echo html::input('end', '', "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->end . "' required");?>
        <span class='input-group-addon' id='longTimeBox'>
          <div class="checkbox-primary">
            <input type="checkbox" name="longTime" value="1" id="longTime">
            <label for="longTime"><?php echo $lang->program->PRJLongTime;?></label>
          </div>
        </span>
      </div>
    </td>
  </tr>
  <tr class='PGMParams'>
    <th><?php echo $lang->project->acl;?></th>
    <td><?php echo nl2br(html::radio('acl', $lang->program->PGMPRJAclList, 'open', "onclick='setWhite(this.value);'", 'block'));?></td>
  </tr>
</table>
<div class='table-foot text-center'><?php echo html::submitButton();?></div>
