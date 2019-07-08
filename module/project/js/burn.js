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
    var $canvas = $('#burnCanvas');
    $('#burnWrapper').append("<div class='hidden' id='cloneCanvas'></div>");
    $('#cloneCanvas').append($canvas.clone());
    $('#cloneCanvas').append("<img id='cloneImage' />");
    $('#cloneCanvas #cloneImage').attr('src', $canvas.get(0).toDataURL("image/png"));
    setTimeout(function()
    {
        /* Add watermark */
        var canvas = document.querySelector('#cloneCanvas #burnCanvas');
        var cans = canvas.getContext('2d');

        // Set background color to white
        cans.fillStyle = '#fff';
        cans.fillRect(0, 0, canvas.width, canvas.height);

        cans.drawImage($('#cloneCanvas #cloneImage').get(0), 0, 0);
        cans.font = "16px Microsoft JhengHei";
        cans.fillStyle = "rgba(17, 17, 17, 0.50)";
        cans.textAlign = 'left';
        cans.textBaseline = 'Middle';
        cans.fillText(watermark, 50, canvas.height - 50);

        var type     = 'png';
        var $canvas  = $('#cloneCanvas #burnCanvas');
        var imgSrc   = $canvas.get(0).toDataURL("image/png");
        var imgData  = imgSrc.replace(type,'image/octet-stream');
        var filename = projectName + '.' + type;
        saveFile(imgData,filename);
        $('#burnWrapper #cloneCanvas').remove();
    }, 500);
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
