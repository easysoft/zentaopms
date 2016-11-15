$(function()
{
    $('#libType').change(function()
    {
        var libType = $(this).val();
        changeByLibType(libType);
    })
    changeByLibType($('#libType').val());
    toggleAcl($('#acl').val());
});

function changeByLibType(libType)
{
    if(libType == 'product')
    {
        $('table tr.product').removeClass('hidden');
        $('table tr.project').addClass('hidden');
    }
    else if(libType == 'project')
    {
        $('table tr.product').addClass('hidden');
        $('table tr.project').removeClass('hidden');
    }
    else
    {
        $('table tr.product').addClass('hidden');
        $('table tr.project').addClass('hidden');
    }
}
