$('.ajaxCollect').click(function()
{
    if(browseType == 'collectedbyme')
    {
        window.location.reload();
    }
})
$(function()
{
    $('#pageActions .btn-toolbar').prepend("<a class='btn btn-link querybox-toggle querybox-opened' id='bysearchTab'><i class='icon icon-search muted'></i>" + docLang.search + "</a>");
});
