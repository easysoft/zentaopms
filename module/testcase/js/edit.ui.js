function loadLibModules()
{
    const libID = $('#lib').val();
    const link = $.createLink('tree', 'ajaxGetOptionMenu', 'libID=' + libID + '&viewtype=caselib&branch=0&rootModuleID=0&returnType=html&fieldID=&needManage=true');

    $('#moduleIdBox').load(link);
}

function checkScript()
{
    $('.autoScript').toggleClass('hidden', !$('#auto').prop('checked'));
}

function readScriptContent()
{
    $uploadBtnLabel = $('#scriptFile').next();

    $uploadBtnLabel.toggle($('#scriptFile').parents('td').find('.file-list').length < 1);

    var reader = new FileReader();
    reader.readAsText($('#scriptFile')[0].files[0], 'UTF-8');
    reader.onload = function(evt){$('#script').val(evt.target.result);}
}

function showUploadScriptBtn()
{
    $('#scriptFile').next().show();
}
