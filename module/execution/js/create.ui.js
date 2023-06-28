$(function()
{
    if($('#methodHover').length) new zui.Tooltip('#methodHover', {title: methodTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light methodTip'});

    if(isStage)
    {
        $(document).on('change', '#attribute', function(e)
        {
            let attribute = $(this).val();
            hidePlanBox(attribute);
        })

        $('#attribute').change();
    }

    if(copyExecutionID != 0 || projectID != 0) loadMembers();

    setWhite();

    $(document).on('click', 'button[type=submit]', function()
    {
        let products      = new Array();
        let existedBranch = false;

        /* Determine whether the products of the same branch are linked. */
        $("#productsBox select[name^='products']").each(function()
        {
            let productID = $(this).val();
            if(typeof(products[productID]) == 'undefined') products[productID] = new Array();
            if(multiBranchProducts[productID])
            {
                let branchID = $(this).closest('.form-row').find("select[name^=branch]").val();
                if(products[productID][branchID])
                {
                    existedBranch = true;
                }
                else
                {
                    products[productID][branchID] = branchID;
                }
                if(existedBranch) return false;
            }
        });

        if(existedBranch)
        {
            zui.Modal.alert(errorSameBranches);
            return false;
        }
    });
});

/**
 * Refresh page.
 *
 * @access public
 * @return void
 */
function refreshPage()
{
    const projectID = $('#project').val();
    loadPage($.createLink('execution', 'create', 'projectID=' + projectID));
}

/**
 * Refresh page.
 *
 * @access public
 * @return void
 */
function setType()
{
    const type = $('#type').val();
    loadPage($.createLink('execution', 'create', 'projectID=' + projectID + '&executionID=0&copyExecutionID=&planID=0&confirm=no&productID=0&extra=type=' + type));
}

/**
 * Show lifetime tips.
 *
 * @access public
 * @return void
 */
function showLifeTimeTips()
{
    const lifetime = $('#lifetime').val();
    if(lifetime == 'ops')
    {
        $('#lifeTimeTips').removeClass('hidden');
    }
    else
    {
        $('#lifeTimeTips').addClass('hidden');
    }
}

/**
 * Load team members.
 *
 * @access public
 * @return void
 */
function loadMembers()
{
    $.get($.createLink('execution', 'ajaxGetTeamMembers', 'objectID=' + $('#teams').val()), function(data)
    {
        $('#teamMembers').replaceWith(data);
    });
}

$(document).on('change', '#begin', function()
{
    $("#end,#days").val('');
    $("input[name='delta']").prop('checked', false);
});

$(document).on('change', '#end', function(e)
{
    $("input[name='delta']").prop('checked', false);
    computeWorkDays();
});
