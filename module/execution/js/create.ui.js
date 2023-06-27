$(function()
{
    if($('#methodHover').length) new zui.Tooltip('#methodHover', {title: methodTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light methodTip'});
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
