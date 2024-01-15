function changeProduct(event)
{
    const productID = $(event.target).val();

    loadProductBranches(productID);
    loadProductModules(productID);
    loadProductStories(productID);
    loadScenes(productID);
}

function changeBranch()
{
    const productID = $('[name=product]').val();
    loadProductModules(productID);
    loadProductStories(productID);
    loadScenes(productID);
}

function changeModule()
{
    const productID = $('[name=product]').val();
    loadProductStories(productID);
    loadScenes(productID);
}

function clickRefresh()
{
    const productID = $('[name=product]').val();
    loadProductModules(productID);
}

function changeStory(event)
{
    const storyID = $(event.target).val();
    if(storyID != '0')
    {
        const storyLink = $.createLink('story', 'view', 'storyID=' + storyID);
        $('#preview').parent().removeClass('hidden');
        $('#preview').attr('href', storyLink);
    }
    else
    {
        $('#preview').parent().addClass('hidden');
    }
}

function checkScript()
{
    $('.autoScript').toggleClass('hidden', !$('#auto').prop('checked'));
    if(!$('#auto').prop('checked')) $('[name=script]').val('');
}

function showUploadScriptBtn()
{
    $('#scriptFile').next().show();
    $('[name=script]').val('');
}

function hideUploadScriptBtn()
{
    $('#scriptFile').next().hide();
}

function readScriptContent()
{
    $uploadBtnLabel = $('#scriptFile').next();

    $uploadBtnLabel.toggle($('#scriptFile').parents('td').find('.file-list').length < 1);

    var reader = new FileReader();
    reader.readAsText($('#scriptFile')[0].files[0], 'UTF-8');
    reader.onload = function(evt){$('[name=script]').val(evt.target.result);}
}
