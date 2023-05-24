$(function()
{
    var bugIdList = [1, 2];

    $(document).on('click', '.edit-btn', function()
    {
        var batchEditLink = $.createLink('bug', 'batchEdit', 'productID=' + productID + '&branch=' + branch);
        var tempform      = document.createElement("form");
        tempform.action   = batchEditLink;
        tempform.method   = "post";
        tempform.style.display = "none";

        var opt   = document.createElement("input");
        opt.name  = 'bugIdList';
        opt.value = bugIdList;

        tempform.appendChild(opt);
        document.body.appendChild(tempform);
        tempform.submit();

        return false;
    });
})
