$(function()
{
    initBurnChar();
    $('#interval').change(function()
    {
        location.href = createLink('project', 'burn', 'projectID=' + projectID + '&type=' + type + '&interval=' + $(this).val());
    });
})

/* Save burn as image.*/
function downloadBurn()
{
    var canvas   = $('#burnCanvas');
    var type     = 'png';
    var imgSrc   = canvas.get(0).toDataURL("image/png");
    imgData      = imgSrc.replace(type,'image/octet-stream');
    var filename = 'burn.' + type;
    saveFile(imgData,filename);
}

var saveFile = function(data, filename)
{
    var saveLink = document.createElement('a');
    saveLink.href = data;
    saveLink.download = filename;

    var event = document.createEvent('MouseEvents');
    event.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
    saveLink.dispatchEvent(event);
};
