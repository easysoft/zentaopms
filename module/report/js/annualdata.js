/**
 * Export annual data to image file
 * @param {function} sucessCallback
 * @param {function} errorCallback
 * @return {void}
 */
function exportAnnualImage(sucessCallback, errorCallback)
{
    var $main = $('#main');
    if($main.hasClass('exporting')) return;
    var $loading = $('#loadIndicator');
    $loading.addClass('loading');
    var $container = $('#container');
    $main.addClass('exporting').css('backgroundImage', $container.css('backgroundImage'));
    var afterFinish = function(canvas)
    {
        $main.removeClass('exporting').css('backgroundImage', 'none');
        $loading.removeClass('loading');
    };
    html2canvas($main[0], {logging: false}).then(function(canvas)
    {
        canvas.onerror = function()
        {
            afterFinish(canvas);
            if(errorCallback) errorCallback('Cannot convert image to blob.');
        };
        canvas.toBlob(function(blob)
        {
            var imageUrl = URL.createObjectURL(blob);
            $('#imageDownloadBtn').attr({href: imageUrl})[0].click();
            if(sucessCallback) sucessCallback(imageUrl);
            afterFinish(canvas);
        });
    });
}

$(function()
{
    $('#exportBtn').on('click', function()
    {
        exportAnnualImage();
    });

    $('#toolbar #year').change(function()
    {
        location.href = createLink('report', 'annualData', 'year=' + $(this).val());
    });

    $('#actionData > div > ul > li').mouseenter(function(e)
      {
        $('#actionData > div > ul > li .dropdown-menu').css('left', e.pageX - $(this).offset().left + 10);
      })
});
