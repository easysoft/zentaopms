<table class='table table-form'>
  <caption class='strong'><?php echo $lang->upgrade->program;?></caption>
  <tr>
    <th>
      <span class="pgm-exist hidden"><?php echo $lang->upgrade->existPGM;?></span>
      <span class="pgm-no-exist"><?php echo $lang->program->PGMName;?></span>
    </th>
    <td class='required'>
      <div class='input-group'>
        <?php echo html::select("programs", $programs, $programID, "class='form-control hidden pgm-exist' onchange='getProjectByProgram(this)'");?>
        <?php echo html::input("PGMName", isset($programName) ? $programName : '', "class='form-control pgm-no-exist'");?>
        <span class='input-group-addon'>
          <div class="checkbox-primary">
            <input type="checkbox" name="newProgram" value="0" checked onchange="toggleProgram(this)" id="newProgram0" />
            <label for="newProgram0"><?php echo $lang->upgrade->newProgram;?></label>
          </div>
        </span>
      </div>
      <?php echo html::hidden('programID', '');?>
    </td>
  </tr>
  <tr>
    <th><?php echo $lang->program->PGMCommon . $lang->program->PGMStatus;?></th>
    <td><?php echo html::select('PGMStatus', $lang->program->statusList, '', "class='form-control chosen'");?></td>
  </tr>
  <tr class='PRJName'>
    <th>
      <span class="prj-exist hidden"><?php echo $lang->upgrade->existPRJ;?></span>
      <span class="prj-no-exist"><?php echo $lang->program->PRJName;?></span>
    </th>
    <td class='required'>
      <div class='input-group'>
        <?php echo html::select("projects", $projects, '', "class='form-control hidden prj-exist'");?>
        <?php echo html::input("PRJName", isset($sprintName) ? $sprintName : '', "class='form-control prj-no-exist'");?>
        <?php if(count($projects)):?>
        <span class='input-group-addon'>
          <div class="checkbox-primary">
            <input type="checkbox" name="newProject" value="0" checked onchange="toggleProject(this)" id="newProject0" />
            <label for="newProject0"><?php echo $lang->upgrade->newProgram;?></label>
          </div>
        </span>
        <?php endif;?>
      </div>
    </td>
  </tr>
  <tr class='PGMParams'>
    <th><?php echo $lang->program->PRJStatus;?></th>
    <td><?php echo html::select('PRJStatus', $lang->program->statusList, '', "class='form-control chosen'");?></td>
  </tr>
  <tr class='LineName'>
    <th>
      <span class="line-exist hidden"><?php echo $lang->upgrade->existLine;?></span>
      <span class="line-no-exist"><?php echo $lang->product->lineName;?></span>
    </th>
    <td>
      <div class='input-group'>
        <?php echo html::select("lines", $lines, '', "class='form-control hidden line-exist'");?>
        <?php echo html::input("lineName", isset($lineName) ? $lineName : '', "class='form-control line-no-exist'");?>
        <?php if(count($lines)):?>
        <span class='input-group-addon'>
          <div class="checkbox-primary">
            <input type="checkbox" name="newLine" value="0" checked onchange="toggleLine(this)" id="newLine0" />
            <label for="newLine0"><?php echo $lang->upgrade->newProgram;?></label>
          </div>
        </span>
        <?php endif;?>
      </div>
    </td>
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
    <td><?php echo nl2br(html::radio('acl', $lang->program->PGMPRJAclList, 'open', '', 'block'));?></td>
  </tr>
</table>
<div class='table-foot text-center'><?php echo html::submitButton();?></div>
