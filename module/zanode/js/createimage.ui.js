if(task)
{
    var setProgress = self.setInterval("getTaskProgress()", 1500);

    $(function()
    {
        var href = $('.success-text').find('a').attr('href');
        href     = href.replace('?onlybody=yes', '');
        $('.success-text').find('a').attr('href', href);
    })

    window.getTaskProgress = function()
    {
        var url = $.createLink('zanode', 'ajaxGetTaskStatus', 'nodeID=' + nodeID + '&taskID=' + taskID + '&type=exportVm');
        $.get(url, function(data)
        {
            var rate   = data.rate;
            var status = data.status;

            if(rate == 1 || status == 'completed') rate = 1;
            if(status == 'inprogress' && rate >= 1) rate = 0.97;

            if(status == 'pending')
            {
                $('.status-title').text(zanodeLang.pending)
            }
            else
            {
                $('.status-title').text(zanodeLang.createImaging)
            }

            $('.rate').css('width', rate*100 + '%');
            if(rate == 1 || (status != 'inprogress' && status != 'created' && status != 'pending'))
            {
                updateStatus(data);
                clearInterval(setProgress);
            }
        }, 'json');
    }

    window.updateStatus = function(data)
    {
        var url      = $.createLink('zanode', 'ajaxUpdateImage', 'taskID=' + taskID)
        var postData = {"status":data.status, "path":data.path}

        $.post(url, postData, function(result)
        {
            if(data.status == 'completed')
            {
                $('.success-text').removeClass('hidden');
                $('.status-title').text('')
            }
            else
            {
                $('.status-title').text(zanodeLang.createImageFail)
            }
        }, 'json');
    }
}
