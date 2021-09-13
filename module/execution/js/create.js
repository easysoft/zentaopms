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
    var $product = $('#products0');
    if(copyExecutionID) productID = $product.val();
    $product.val(productID);
    $product.trigger("chosen:updated");
    loadBranches($product);

    var adjustMainCol = function()
    {
        if(!isStage) $('.main-form .col-main').css('width', Math.max(250, Math.floor(($('#productsBox').outerWidth() - 50)/3) + 10));
    };
    adjustMainCol();
    $(window).on('resize', adjustMainCol);

    $('#teams_chosen').click(function()
    {
        if(systemMode == 'new')
        {
            $('#teams_chosen ul li').each(function(index)
            {
                if(index == 0)
                {
                    var maxWidth = $('.chosen-container .chosen-drop.chosen-auto-max-width.in').width() - 70;

                    $('#teams_chosen ul .label').remove();
                    $(this).after(' <label class="label">' + projectCommon + '</label>');
                    $(this).attr('style', 'display: inline-block; vertical-align: middle; max-width: ' + maxWidth + 'px');
                }
                else
                {
                    $(this).html($(this).html().replace('&nbsp;&nbsp;&nbsp;', ''));
                    $(this).prepend('&nbsp;&nbsp;&nbsp;');
                }
            })
        }
    })

    $('#teams').change(function()
    {
        var objectID = $(this).val();
        $.get(createLink('execution', 'ajaxGetTeamMembers', 'objectID=' + objectID), function(data)
        {
            $('#teamMembers').parent().html(data);
            $('#teamMembers').chosen();
        });
    })

    if(copyExecutionID != 0) $('#teams').change();

    var acl = $("[name^='acl']:checked").val();
    setWhite(acl);
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
