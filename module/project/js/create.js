function setCopyProject(projectID)
{
    location.href = createLink('project', 'create', 'projectID=0&copyProjectID=' + projectID);
}

$(function()
{
    $('#cpmBtn').click(function(){$('#copyProjectModal').modal('show')});
    $('#copyProjects a').click(function(){setCopyProject($(this).data('id')); $('#copyProjectModal').modal('hide')});
    var typeWidth = $('#type').closest('td').width();
    $('#productsBox .col-sm-3,#plansBox .col-sm-3').width(typeWidth);
    $(document).on('change', "#productsBox select", function()
    {
        $('#productsBox .col-sm-3,#plansBox .col-sm-3').width(typeWidth);
    });
    $('#begin').on('change', function()
    {
       $("#end").val('');
       $("#days").val('');
       $("input:radio[name='delta']").attr("checked",false);
    });
    $('#end').on('change', function()
    {
        $("input:radio[name='delta']").attr("checked", false);
    })
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

