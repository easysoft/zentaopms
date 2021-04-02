<table class='table table-form'>
  <caption class='strong'><?php echo $lang->upgrade->program;?></caption>
  <tr>
    <th>
      <span class="pgm-exist hidden"><?php echo $lang->upgrade->existProgram;?></span>
      <span class="pgm-no-exist"><?php echo $lang->program->name;?></span>
    </th>
    <td class='required'>
      <div class='input-group'>
        <?php echo html::select("programs", $programs, $programID, "class='form-control hidden pgm-exist' onchange='getProjectByProgram(this)'");?>
        <?php echo html::input("programName", isset($programName) ? $programName : '', "class='form-control pgm-no-exist'");?>
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
    <th><?php echo $lang->program->common . $lang->program->status;?></th>
    <td><?php echo html::select('programStatus', $lang->program->statusList, '', "class='form-control chosen'");?></td>
  </tr>
  <tr class='projectName'>
    <th>
      <span class="prj-exist hidden"><?php echo $lang->upgrade->existProject;?></span>
      <span class="prj-no-exist"><?php echo $lang->project->name;?></span>
    </th>
    <td class='required'>
      <div class='input-group'>
        <?php echo html::select("projects", $projects, '', "class='form-control hidden prj-exist' onchange='getProgramStatus(project, this.value)'");?>
        <?php echo html::input("projectName", isset($sprintName) ? $sprintName : '', "class='form-control prj-no-exist'");?>
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
  <tr class='programParams'>
    <th><?php echo $lang->project->status;?></th>
    <td><?php echo html::select('projectStatus', $lang->project->statusList, '', "class='form-control chosen'");?></td>
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
  <tr class='programParams'>
    <th><?php echo $lang->project->PM;?></th>
    <td><?php echo html::select('PM', array('' => '') + $users, '', "class='form-control chosen'");?></td>
  </tr>
  <tr class='programParams'>
    <th><?php echo $lang->project->dateRange;?></th>
    <td>
      <div class='input-group'>
        <?php echo html::input('begin', date('Y-m-d'), "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->begin . "' required");?>
        <span class='input-group-addon'><?php echo $lang->project->to;?></span>
        <?php echo html::input('end', '', "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->end . "' required");?>
        <span class='input-group-addon' id='longTimeBox'>
          <div class="checkbox-primary">
            <input type="checkbox" name="longTime" value="1" id="longTime">
            <label for="longTime"><?php echo $lang->project->longTime;?></label>
          </div>
        </span>
      </div>
    </td>
  </tr>
  <tr class='programParams'>
    <th><?php echo $lang->project->acl;?></th>
    <td><?php echo nl2br(html::radio('acl', $lang->program->subAclList, 'open', '', 'block'));?></td>
  </tr>
</table>
<div class='table-foot text-center'><?php echo html::submitButton();?></div>
