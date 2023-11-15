$(function()
{
    new zui.Tooltip('#programHover', {title: programTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light programTip'});

    setWhite();
});

$(document).on('click', 'button[type=submit]', function()
{
    /* Remove init tips. */
    $('.has-warning').removeClass('has-warning');
    $('.text-warning').remove();
})

window.addProduct = function(e)
{
    if($(e.target).prop('checked'))
    {
        $('.productBox').addClass('hidden');
        $('#addProductBox').removeClass('hidden');
        $("[name^='newProduct']").prop('checked', true);
    }
    else
    {
        $('.productBox').removeClass('hidden');
        $('#addProductBox').addClass('hidden');
        $("[name^='newProduct']").prop('checked', false);
    }
}

window.productChange = function(e)
{
    loadBranches(e.target);

    let current    = $(e.target).val();
    let last       = $(e.target).attr('last');
    let lastBranch = $(e.target).attr('data-lastBranch');

    $(e.target).attr('data-last', current);

    let $branch = $(e.target).closest('.has-branch').find("[name^='branch']");
    if($branch.length)
    {
        let branchID = $branch.val();
        $(e.target).attr('data-lastBranch', branchID);
    }
    else
    {
        $(e.target).removeAttr('data-lastBranch');
    }

    let chosenProducts = 0;
    $(".productBox [name^='products']").each(function()
    {
        if($(this).val() > 0) chosenProducts ++;
    });

    if(chosenProducts > 1)  $('.stageBy').removeClass('hidden');
    if(chosenProducts <= 1) $('.stageBy').addClass('hidden');
}

window.branchChange = function(e)
{
    let current = $(e.target).val();
    let last    = $(e.target).attr('data-last');
    $(e.target).attr('data-last', current);

    let $product = $(e.target).closest('.form-row').find("[name^='products']");
    $product.attr('data-lastBranch', current);

    loadPlans($product, $(e.target));
}

$(document).on('click', '#copyProjects button', function()
{
    const copyProjectID = $(this).hasClass('primary-outline') ? 0 : $(this).data('id');
    setCopyProject(copyProjectID);
    zui.Modal.hide();
});

/**
 * Set copy project.
 *
 * @param  int $copyProjectID
 * @access public
 * @return void
 */
function setCopyProject(copyProjectID)
{
    const programID = $('#parent').val();
    loadPage($.createLink('project', 'create', 'model=' + model + '&programID=' + programID + '&copyProjectID=' + copyProjectID));
}

/**
 * Fuzzy search projects by project name.
 *
 * @access public
 * @return void
 */
$(document).on('keyup', '#projectName', function()
{
    var name = $(this).val();
    name = name.replace(/\s+/g, '');
    $('#copyProjects .project-block').hide();

    if(!name) $('#copyProjects .project-block').show();
    $('#copyProjects .project-block').each(function()
    {
        if($(this).text().includes(name) || $(this).data('pinyin').includes(name)) $(this).show();
    });
});

/* Click remove tips.  */
$(document).on('click', '#name', function()
{
    $('#name').removeClass('has-warning');
    $('#nameLabelInfo').remove();
});

$(document).on('click', '#code', function()
{
    $('#code').removeClass('has-warning');
    $('#codeLabelInfo').remove();
});

$(document).on('click', '#end', function()
{
    $('#end').removeClass('has-warning');
    $('#endLabelInfo').remove();
});

$(document).on('change', '#end', function()
{
    $('#end').removeClass('has-error');
    $('#endTip').remove();
});

$(document).on('click', '#days', function()
{
    $('#days').removeClass('has-warning');
    $('#daysLabelInfo').remove();
});

/**
 * Set acl list when change program.
 *
 * @access public
 * @return void
 */
window.setParentProgram = function()
{
    const programID = $('[name=parent]').val();
    const link      = $.createLink('project', 'create', 'model=' + model + '&program=' + programID);
    loadPage(link, '#aclList');
    $('select[name^=whitelist]').closest('.form-row').removeClass('hidden')
}
