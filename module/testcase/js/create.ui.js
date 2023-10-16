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
}

function clickRefresh()
{
    const productID = $('[name=product]').val();
    loadProductModules(productID);
}

function changeStory(event)
{
    const storyID = $(event.target).val();
    if(storyID)
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
