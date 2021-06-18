function setCopyProject(executionID)
{
    location.href = createLink('execution', 'create', 'projectID=&executionID=0&copyExecutionID=' + executionID);
}

$(function()
{
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

    /* Assign value to the manage products by the different request type.*/
    var product = $('#products0');
    $(product).val(productID);
    $(product).trigger("chosen:updated");
    loadBranches($(product));

    var adjustMainCol = function()
    {
        if(!isStage) $('.main-form .col-main').css('width', Math.max(250, Math.floor(($('#productsBox').outerWidth() - 50)/3) + 10));
    };
    adjustMainCol();
    $(window).on('resize', adjustMainCol);
});

function showLifeTimeTips()
{
    var lifetime = $('#lifetime option:selected').val();
    if(lifetime == 'ops')
    {
        $('#lifeTimeTips').show();
    }
    else
    {
        $('#lifeTimeTips').hide();
    }
}

/**
 * Refresh page.
 *
 * @param  object $projectID
 * @access public
 * @return void
 */
function refreshPage(projectID)
{
    location.href = createLink('execution', 'create', 'projectID=' + projectID);
}
