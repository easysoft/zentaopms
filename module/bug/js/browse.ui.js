$(function()
{
    var bugIdList = [1, 2];

    $(document).on('click', '.batch-btn', function()
    {
        var tempform    = document.createElement("form");
        tempform.action = $(this).data('url');
        tempform.method = "post";
        tempform.style.display = "none";

        var opt   = document.createElement("input");
        opt.name  = 'bugIdList';
        opt.value = bugIdList;

        tempform.appendChild(opt);
        document.body.appendChild(tempform);
        tempform.submit();
    });
})
