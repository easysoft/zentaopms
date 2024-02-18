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
        $('#preview').removeClass('hidden');
        $('#preview').attr('href', storyLink);
    }
    else
    {
        $('#preview').addClass('hidden');
    }
}

function checkScript()
{
    $('.autoScript').toggleClass('hidden', !$('#auto').prop('checked'));
    if(!$('#auto').prop('checked')) $('[name=script]').val('');
}

window.showUploadScriptBtn = function()
{
    $('[name=scriptFile]').siblings().first().show();
    $('[name=script]').val('');
}

window.readScriptContent = function(object)
{
    $('[name=scriptFile]').siblings().first().hide();

    var reader = new FileReader();
    reader.readAsText(object.file, 'UTF-8');
    reader.onload = function(evt){$('[name=script]').val(evt.target.result);}
}
