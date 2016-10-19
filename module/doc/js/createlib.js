$(function()
{
    $('#libType').change(function()
    {
        var libType = $(this).val();
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
    })
    toggleAcl($('#acl').val());
});
