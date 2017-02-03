function setCopyProject(projectID)
{
    location.href = createLink('project', 'create', 'projectID=0&copyProjectID=' + projectID);
}

$(function()
{
    $('#cpmBtn').click(function(){$('#copyProjectModal').modal('show')});
    $('#copyProjects a').click(function(){setCopyProject($(this).data('id')); $('#copyProjectModal').modal('hide')});
    $('#productsBox .col-sm-3').width($('#type').closest('td').width());
});

function showTypeTips()
{
    var type = $('#type').val();
    if(type == 'ops')
    {
        $('.type-tips').show();
    }
    else
    {
        $('.type-tips').hide();
    }
}
