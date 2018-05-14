function setCopyProject(projectID)
{
    location.href = createLink('project', 'create', 'projectID=0&copyProjectID=' + projectID);
}

$(function()
{
    $('#cpmBtn').click(function(){$('#copyProjectModal').modal('show')});
    $('#copyProjects a').click(function(){setCopyProject($(this).data('id')); $('#copyProjectModal').modal('hide')});
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

    if(typeof(currentPlanID) == 'undefined')
    {
        $('#productsBox select[id^="products"]').each(function()
        {
            var branchID = 0;
            if($(this).closest('.input-group').find('select[id^="branch"]').size() > 0)
            {
                var branchID = $(this).closest('.input-group').find('select[id^="branch"]').val();
            }
            loadPlans($(this), branchID);
        });
    }

    var adjustMainCol = function()
    {
        $('.main-form .col-main').css('width', Math.max(250, Math.floor(($('#productsBox').outerWidth() - 50)/3) + 10));
    };
    adjustMainCol();
    $(window).on('resize', adjustMainCol);
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

