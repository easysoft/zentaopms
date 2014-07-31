$(function()
{
    $('.result-item').click(function()
    {
        var $this = $(this);
        $this.toggleClass('show-detail');
        var show = $this.hasClass('show-detail');
        $this.next('.result-detail').toggleClass('hide', !show);
        $this.find('.collapse-handle').toggleClass('icon-chevron-down', !show).toggleClass('icon-chevron-up', show);;
    });

    $('#casesResults table caption .result-tip').html($('#resultTip').html());
});
