$(function()
{
    var overtime = 30;
    var timerID  = 0;
    $("#submitBtn").on('click', function()
    {
        $('#waiting').modal('show');
        timerID = setInterval(function()
        {
            $('#timer').text(overtime);
            overtime--;
        }, 1000);

        var slbData = {};
        slbData.ippool = $('#ippool').val();
        $.post(createLink('system', 'editSLB'), slbData).done(function(response)
        {
            $('#waiting').modal('hide');
            clearInterval(timerID);
            overtime = 30;

            var res = JSON.parse(response);
            if(res.result == 'success'){
                parent.window.location.href = res.locate;
            }else{
                bootbox.alert(
                {
                    title:   notices.fail,
                    message: res.message,
                });
            }
        });
    });
});
