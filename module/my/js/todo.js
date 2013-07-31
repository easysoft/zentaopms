function changeDate(date)
{
    date = date.replace(/\-/g, '');
    link = createLink('my', 'todo', 'type=' + date);
    location.href=link;
}

/**
 * Change form action.
 * 
 * @param  formName   $formName 
 * @param  actionName $actionName 
 * @param  actionLink $actionLink 
 * @access public
 * @return void
 */
function changeAction(formName, actionName, actionLink)
{
    if(actionName == 'batchFinish') $('#' + formName).attr('target', 'hiddenwin');
    $('#' + formName).attr('action', actionLink).submit();
}

/**
 * Delete todo. 
 * 
 * @param  int    todoID 
 * @access public
 * @return void
 */
function deleteTodo(todoID)
{
    if(confirm(confirmDelete))
    {
        url = createLink('todo', 'delete','todoID=' + todoID + '&confrim=yes');
        $.ajax(
        {
            type:     'GET', 
            url:      url,
            dataType: 'json', 
            success:  function(data) 
            {
                if(data.result == 'success') 
                {
                    url = createLink('my', 'todo');
                    $('#todo').load(url + ' #todoList', function()
                    {
                        $('.colored').colorize();
                        $('tfoot td').css('background', 'white').unbind('click').unbind('hover');
                    });
                }
            }
        });
    }
}

$(".colorbox").colorbox({width:960, height:550, iframe:true, transition:'none'});
