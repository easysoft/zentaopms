$().ready(function()
{
    $('#lastBuildBtn').click(function()
    {
        $('#name').val($(this).text()).focus();
    });

    $(document).on('change', '#product, #branch', function()
    {
        var productID = $('#product').val();
        var branch    = $('#branch').length > 0 ? $('#branch').val() : '';
        $.get(createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=' + branch + '&index=&needCreate=&type=noempty,notrunk,separate,noproject&extra=multiple'), function(data)
        {
            if(data) $('#buildBox').html(data);
            $('#builds').chosen();
        });
    });
    $('#product').change();
});
