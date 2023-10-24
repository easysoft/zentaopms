$(document).ready(function()
{
    /* Set secondary menu highlighting. */
    if(isLibCase)
    {
      $('#navbar li[data-id=caselib]').addClass('active');
      $('#navbar li[data-id=testcase]').removeClass('active');
    }

    $(document).on('change', '[name^=steps], [name^=expects]', function()
    {
        var steps   = [];
        var expects = [];
        var status  = $('#status').val();

        $('[name^=steps]').each(function(){ steps.push($(this).val()); });
        $('[name^=expects]').each(function(){ expects.push($(this).val()); });

        $.post(createLink('testcase', 'ajaxGetStatus', 'methodName=update&caseID=' + caseID), {status : status, steps : steps, expects : expects}, function(status)
        {
            $('#status').val(status).change();
        });
    });

    initSteps();
});
