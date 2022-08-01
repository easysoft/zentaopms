$(function()
{
    $('.main-actions-holder').css('height', '0px');
    var xuanAction = "<div class='xuancard-actions fixed'>";
    $('.main-col div.main-actions .btn-toolbar a').each(function(){
        var $that    = $(this);

        if($that.attr('id') == 'back') return true;

        var href     = $that.attr('href');
        var title    = $that.attr('title') == undefined ? '' : " title='" + $that.attr('title') + "'";
        var btnClass = " class='" + $that.attr('class') + "'";
        var action   = $that.html();

        if(href.indexOf('createCase') >= 0 || href.indexOf('toStory') >= 0 || href.indexOf('toTask') >= 0 || href.indexOf('create') >= 0 || href.indexOf('delete') >= 0) return true;

        if($that.hasClass('iframe'))
        {
            url = href;
            target = '';
        }
        else
        {
           url = 'xxc:openUrlInDialog/' + encodeURIComponent(sysurl + href);
           target = " target='_blank'";
        }

        xuanAction += "<a href='" + url + "'" + title + target + btnClass + '>' + action + "</a>";
    });

    if(xuanAction != "<div class='xuancard-actions fixed'>")
    {
        var params = window.location.search;
        if(params.indexOf('isNotice=1') == -1)
        {
            xuanAction += '</div>';
            $('body').append(xuanAction);
        }
    }
    else
    {
        $('#scrollContent').css('height', 'calc(100% - 36px)');
    }
    $('.xuancard-actions a.iframe').modalTrigger();
})
