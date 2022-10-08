<div class="modal fade" id="projectModal">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->project->select;?></h4>
      </div>
      <div class="modal-body">
        <?php if(empty($projects)):?>
        <div class="table-empty-tip">
          <p>
            <span class="text-muted"><?php echo $lang->project->noProject;?></span>
            <?php echo html::a("javascript:createProject()", "<i class='icon icon-plus'></i> " . $lang->project->create, '', "class='btn btn-info'");?>
          </p>
        </div>
        <?php else:?>
        <div class='input-group'>
          <?php echo html::select('projectToStory', $projects, '', "class='form-control chosen' onchange=getProgramByProject(this.value);");?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->todo->reasonList['story'], "id='toStoryButtonByProject'", 'btn btn-primary');?></span>
          <?php echo html::hidden('projectProgram', 0);?>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>

<script>
$("#projectModal modal-header modal-title").text("<?php echo $lang->project->select; ?>")

$('#toStoryLink').click(function()
{
    $('#projectModal .modal-body .input-group .input-group-btn').addClass('hidden');
    $('#projectModal #toStoryButtonByProject').closest('.input-group-btn').removeClass('hidden');
})

$('#toStoryButtonByProject').click(function()
{
    var onlybody  = config.onlybody == 'yes';
    var projectID = $('#projectToStory').val();
    var link      = createLink('story', 'create', 'productID=0&branch=0&moduleID=0&storyID=0&projectID=' + projectID + '&bugID=0&planID=0&todoID=' + todoID, config.defaultView, onlybody);

    if(!onlybody) window.parent.$.apps.open(link, 'project');
    if(onlybody) location.href = link;
})

/**
 * Get programs by project id.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function getProgramByProject(projectID)
{
    link = createLink('todo', 'ajaxGetProgramID', "projectID=" + projectID + '&type=project');
    $.post(link, function(data)
    {
        $('#projectProgram').val(data);
    })
}

/**
 * Link to create project.
 *
 * @access public
 * @return void
 */
function createProject()
{
    var onlybody    = config.onlybody;
    config.onlybody = 'no';

    var link = createLink('project', 'create');
    config.onlybody      = onlybody;
    parent.location.href = link;
}
</script>
