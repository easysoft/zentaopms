window.clickSubmit = function(e)
{
    if($(e.submitter).hasClass('save-btn'))   $('input[name=status]').val('normal');
    if($(e.submitter).hasClass('save-draft')) $('input[name=status]').val('draft');
}
