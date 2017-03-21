$(document).on('keyup', 'form textarea', function()
{
    var preSelect = $(this).closest('table').parent().prev().find('select');
    if($(this).val() == '' && $(preSelect).val() == 'fail')
    {
        $(preSelect).val('pass');
    }
    else if($(this).val() != '' && $(preSelect).val() == 'pass')
    {
        $(preSelect).val('fail').parent().addClass('has-error');
        setTimeout(function(){$(preSelect).parent().removeClass('has-error');},'1000');
    }
})

/* Delete a file. */
function deleteFile(fileID)
{
    if(!fileID) return;
    hiddenwin.location.href =createLink('file', 'delete', 'fileID=' + fileID);
}

/* Download a file, append the mouse to the link. Thus we call decide to open the file in browser no download it. */
function downloadFile(fileID, extension, imageWidth)
{
    if(!fileID) return;
    var url = createLink('file', 'download', 'fileID=' + fileID + '&mouse=left') + sessionString;
    window.open(url, '_blank');
    return false;
}

/* Load files name when upload files. */
function loadFilesName()
{
    $('#filesName').find('li').remove();//Remove file name in li label before uploading files every time.
    $('.fileBox').each(function()
    {
        fileName  = $(this).find('input[type="file"]').val();
        if(fileName.lastIndexOf('\\')) fileName = fileName.substring(fileName.lastIndexOf('\\') + 1);//Process the file name.
        labelName = $(this).find('input[type="text"]').val();
        if(labelName) fileName = labelName;//If label name exits, set label name as file name.

        if(fileName) $('#filesName').append("<li>" + fileName + '</li>');//Show file name.
    })
}

$(document).ready(function()
{
    // First unbind ajaxForm for form.
    $("form[data-type='ajax']").unbind('submit');
    setForm();
    
    // Bind ajaxForm for form again.
    $.ajaxForm("form[data-type='ajax']", function(response)
    {   
        if(response.locate)
        {
            if(response.locate == 'reload' && response.target == 'parent')
            {
                parent.$.cookie('selfClose', 1);
                parent.$.closeModal(null, 'this');
            } else if(response.next) {
                location.href = response.locate;
            } else {

                // Get cases result
                $('#resultsContainer').load(response.locate + " #casesResults", function()
                {
                    $('tr:first').addClass("show-detail");
                    $('#tr-detail_1').removeClass("hide");

                    $('.result-item').click(function()
                    {
                        var $this = $(this);
                        $this.toggleClass('show-detail');
                        var show = $this.hasClass('show-detail');
                        $this.next('.result-detail').toggleClass('hide', !show);
                        $this.find('.collapse-handle').toggleClass('icon-chevron-down', !show).toggleClass('icon-chevron-up', show);
                    });

                    $('#casesResults table caption .result-tip').html($('#resultTip').html());

                    $("#submit").text(caseResultSave);
                    $("#submit").attr({"disabled":"disabled"});
                });   
            }   
        }
    
        return false;
    }); 

    $(document).on('click', ".step-group input[type='checkbox']", function()
    {
        var $next  = $(this).closest('tr').next();
        while($next.length && $next.hasClass('step-item'))
        {
            var isChecked = $(this).prop('checked');
            $next.find("input[type='checkbox']").prop('checked', isChecked);
            $next = $next.next();
        }
    });
});
