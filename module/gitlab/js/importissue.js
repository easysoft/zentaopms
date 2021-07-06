$(document).ready(function()
{
    $('[name^=productList').change(function()
    {
         var execution = $(this);
         host = execution.val();
         if(host == '') return false;
         executions = '';
         url = createLink('gitlab', 'ajaxGetExecutionsByProduct', "host=" + host);
    
         $.get(url, function(response)
         {
             execution = execution.parent().next().find('select');
             execution.html('').append(response);
             execution.chosen().trigger("chosen:updated");
         });
    });
}); 
