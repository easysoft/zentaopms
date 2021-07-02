$(document).ready(function()
{
    $('[name^=gitlabID').change(function()
    {
         var gitlab = $(this);
         host = gitlab.val();
         if(host == '') return false;
         projects = '';
         url = createLink('repo', 'ajaxgetgitlabprojects', "host=" + host);
    
         $.get(url, function(response)
         {
             project = gitlab.parent().next().find('select');
             project.html('').append(response);
             project.chosen().trigger("chosen:updated");
         });

    });
}); 
