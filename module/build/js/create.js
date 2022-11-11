$().ready(function()
{
    $('#lastBuildBtn').click(function()
    {
        $('#name').val($(this).text()).focus();
    });

    $(document).on('change', '#product, #branch', function()
    {
        var productID = $('#product').val();
        var branch    = $('#branch').val();
        $.get(createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=builds&build=&branch=' + branch + '&index=&type=noempty,notrunk,separate,noproject&extra=multiple'), function(data)
        {
            if(data) $('#buildBox').html(data);
            $('#builds').chosen();
        });
    });
    $('#product').change();
});
