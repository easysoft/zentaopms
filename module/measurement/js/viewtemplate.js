$(document).ready(function()
{
    $('#submit').click(function()
    {
        $('.m-measurement-viewtemplate .col-md-9').show();
        $('#reportForm').submit();
    });

    $('fieldset [name^=program]').prop('readonly', true);

    $('.m-measurement-viewtemplate .col-md-9').hide();
});
