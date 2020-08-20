$(document).on('click', '.ajaxPager', function()
{   
    $('#logBox').load($(this).attr('href'));
    return false;
})
