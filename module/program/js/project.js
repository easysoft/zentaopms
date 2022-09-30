$(function()
{
    $('input[name^="showEdit"]').click(function()
    {
        $.cookie('showProjectBatchEdit', $(this).is(':checked') ? 1 : 0, {expires: config.cookieLife, path: config.webRoot});
        setCheckbox();
    });
    setCheckbox();

    $(":checkbox[name^='projectIdList']").on('click', function()
    {
        updateStatistic()
    });

    $(".check-all").on('click', function()
    {
        if($(":checkbox[name^='projectIdList']:not(:checked)").length == 0)
        {
            $(":checkbox[name^='projectIdList']").prop('checked', false);
        }
        else
        {
            $(":checkbox[name^='projectIdList']").prop('checked', true);
        }
        updateStatistic()
    });
});

/**
 * Set batch edit checkbox.
 *
 * @access public
 * @return void
 */
function setCheckbox()
{
    $('#projectsForm .checkbox-primary').hide();
    if($.cookie('showProjectBatchEdit') == 1)
    {
        $('#projectsForm .checkbox-primary').show();
    }
    else
    {
        $(":checkbox[name^='projectIdList']").prop('checked', false);
        $('.table-actions').hide();
        $('.check-all').removeClass('checked');
    }
}

/**
 * Add a statistics prompt statement after the Edit button.
 *
 * @access public
 * @return void
 */
function addStatistic()
{
    var checkedLength = $(":checkbox[name^='projectIdList']:checked").length;
    if(checkedLength > 0)
    {
        $('.table-actions').show();
    }
    else
    {
        $('.table-actions').hide();
    }
}

/**
 * Anti shake operation for jquery.
 *
 * @param  fn $fn
 * @param  delay $delay
 * @access public
 * @return void
 */
function debounce(fn, delay)
{
    var timer = null;
    return function()
    {
        if(timer) clearTimeout(timer);
        timer = setTimeout(fn, delay)
    }
}

/**
 * Update statistics.
 *
 * @access public
 * @return void
 */
function updateStatistic()
{
    debounce(addStatistic(), 200)
}
