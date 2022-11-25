<?php echo html::hidden('feedbackID', '');?>
<div class="modal fade" id="toTask">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $lang->feedback->selectProjects;?></h4>
      </div>
      <div class="modal-body">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->feedback->project;?></th>
            <td><?php echo html::select('taskProjects', $projects, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th id='executionHead'><?php echo $lang->feedback->execution;?></th>
            <td><?php echo html::select('executions', '', '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <td colspan='2' class='text-center'>
              <?php echo html::a('', $lang->feedback->nextStep, '',"class='btn btn-primary btn-wide iframe hidden' data-app='my' data-width='95%' id=taskProjectA");?>
              <?php echo html::commonButton($lang->feedback->nextStep, "id='taskProjectButton'", 'btn btn-primary btn-wide');?>
              <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-default btn-wide');?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="toBug">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $lang->feedback->selectProjects;?></h4>
      </div>
      <div class="modal-body">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->feedback->project?></th>
            <td><?php echo html::select('bugProjects', $projects, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <td colspan='2' class='text-center'>
              <?php echo html::hidden('productID', '');?>
              <?php echo html::a('', $lang->feedback->nextStep, '',"class='btn btn-primary btn-wide iframe hidden' data-app='my' data-width='95%'  id=bugProjectA");?>
              <?php echo html::commonButton($lang->feedback->nextStep, "id='bugProjectButton'", 'btn btn-primary btn-wide');?>
              <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-default btn-wide');?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
function getFeedbackID(obj)
{
    var feedbackID = $(obj).attr("data-id");
    $('#feedbackID').val(feedbackID);
    $('#taskProjects').change();
    getProjects(obj);
    getExecutions(0);
}

function getProjects(obj)
{
    var productID = $(obj).attr("data-product");
    var link      = createLink('feedback', 'ajaxGetProjects', 'productID=' + productID + '&field=taskProjects');

    $.post(link, function(data)
    {
        $('#taskProjects').replaceWith(data);
        $('#taskProjects_chosen').remove();
        $('#taskProjects').chosen();
        changeTaskButton(false)
    })
}

function initToBug(obj)
{
    var feedbackID = $(obj).attr("data-id");
    var productID  = $(obj).attr("data-product");
    $('#feedbackID').val(feedbackID);
    $('#productID').val(productID);

    var link = createLink('feedback', 'ajaxGetProjects', 'productID=' + productID + "&field=bugProjects&onchange=changeButton");
    $.post(link, function(data)
    {
        $('#bugProjects').replaceWith(data);
        $('#bugProjects_chosen').remove();
        $('#bugProjects').chosen();
    })
}

function getExecutions(projectID)
{
    if(projectID)
    {
        var langLink = createLink('feedback', 'ajaxGetExecutionLang', 'projectID=' + projectID);
        $.post(langLink, function(executionLang)
        {
            $('#executionHead').html(executionLang);
        })
    }

    var link = createLink('feedback', 'ajaxGetExecutions', 'projectID=' + projectID);

    $.post(link, function(data)
    {
        $('#executions').replaceWith(data);
        $('#executions_chosen').remove();
        $('#executions').chosen();
        changeTaskButton()
    })
}

/** Close dialog box when click href. */
$('#taskProjectA').on('click', function()
{
    $('#toTask').modal('hide')
});

$('#bugProjectA').on('click', function()
{
    $('#toBug').modal('hide')
});

/** alert warning .*/
$('#taskProjectButton').on('click', function()
{
    var executionID = $('#executions').val();

    var projectID  = $('#taskProjects').val();
    var executionID = executionID ? executionID : 0;
    if(!executionID)
    {
        alert('<?php echo $lang->feedback->noExecution;?>');
    }
    else if(!projectID)
    {
        alert('<?php echo $lang->feedback->noProject;?>');
    }
});

$('#bugProjectButton').on('click', function()
{
    var projectID  = $('#bugProjects').val();

    if(!projectID)
    {
        alert('<?php echo $lang->feedback->noProject;?>');
    }
});

/** change Button to A for open iframe. */
function changeTaskButton()
{

    var projectID   = $('#taskProjects').val();
    var executionID = $('#executions').val();
    var feedbackID  = $('#feedbackID').val();
    var executionID = executionID ? executionID : 0;

    if(executionID == 0)
    {
        $('#taskProjectButton').removeClass('hidden');
        $('#taskProjectA').addClass('hidden');
    }

    if(projectID && executionID)
    {
        var href = createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=0&extra=projectID=' + projectID + ',feedbackID=' + feedbackID, '', true);
        $('#taskProjectButton').addClass('hidden');
        $('#taskProjectA').removeClass('hidden').attr('href', href);

    }
}

function changeButton()
{
    var projectID  = $('#bugProjects').val();
    var feedbackID = $('#feedbackID').val();
    var productID  = $('#productID').val();

    if(projectID)
    {
        var href = createLink('bug', 'create', 'product=' + productID + '&branch=0&extras=projectID=' + projectID + ',feedbackID=' + feedbackID, '', true);
        $('#bugProjectButton').addClass('hidden');
        $('#bugProjectA').removeClass('hidden').attr('href', href);
    }
    else
    {
        $('#bugProjectButton').removeClass('hidden');
        $('#bugProjectA').addClass('hidden');
    }
}

</script>
