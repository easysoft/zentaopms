function onExecutionChange(event, productID)
{
    executionID = event.target.value;
    $.getJSON($.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=testTaskBuild&build=0'), function(data) 
    {
        $('.picker').eq(1).zui('picker').render({items: data});
        $('.picker').eq(1).zui('picker').$.changeState({value: data[0].value});
    });
}
