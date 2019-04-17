$(document).on('change', '.moduleChange',function ajaxGetMouletStories(){
    var moduleID = $(this).val();
    if(typeof(moduleID) == 'undefined') moduleID = 0;
    link = createLink('testcase', 'ajaxGetMouletStories', 'moduleID=' + moduleID);
    $(this).parent('td').next('td').load(link, function(){$(this).parent('td').next('td').find('slect').chosen();});
})

$(document).on('change', '.storyChange',function ajaxGetStoryMoule(){
    var storyID = $(this).val();
    if(typeof(storyID) == 'undefined') storyID = 0;
    link = createLink('testcase', 'ajaxGetStoryMoule', 'storyID=' + storyID);
    $(this).parent('td').prev('td').load(link, function(){$(this).parent('td').prev('td').find('slect').chosen();});
})
