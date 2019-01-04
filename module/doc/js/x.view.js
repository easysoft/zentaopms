$(function()
{
    $('.main-actions-holder').css('height', '0px');
    var $xuanAction = "<div class='xuancard-actions fixed'>";
    $('.main-col div.main-actions .btn-toolbar a').each(function(){
        var $that    = $(this);

        if($that.attr('id') == 'back') return true;

        var href     = $that.attr('href');
        var title    = $that.attr('title') == undefined ? '' : " title='" + $that.attr('title') + "'";
        var btnClass = " class='" + $that.attr('class') + "'";
        var action   = $that.html();

        if(href.indexOf('edit') < 0) return true;

        url = 'xxc:openUrlInDialog/' + encodeURIComponent(sysurl + href);
        target = " target='_blank'";

        $xuanAction += "<a href='" + url + "'" + title + target + btnClass + '>' + action + "</a>";
    });

    $xuanAction += '</div>';
    $('#footer').replaceWith($xuanAction);
    $('.xuancard-actions a.iframe').modalTrigger();
})
