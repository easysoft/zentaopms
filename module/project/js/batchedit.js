$(function()
{
    $("select[name^='parents']").change(function()
    {
        var $this     = $(this);
        var programID = $this.val();
        var projectID = $this.attr("data-id");
        var oldParent = $this.attr("data-parent");
        var title     = changeProgram.replace('%s', $this.attr("data-name"));

        /* Determine whether the project can change the program set. */
        link = createLink('project', 'ajaxCheckProduct', 'programID=' + programID + '&projectID=' + projectID);
        $.getJSON(link, function(data)
        {
            var changed = true;

            if(data && data.result)
            {
                changed = confirm(data.message);
            }

            if(data && !data.result)
            {
                $('#promptTable tbody tr').remove();
                $('.modal-title').text(title);
                for(var i in data.message)
                {
                    var product = data.message[i];
                    $('#promptTable').append("<tr><td><i class='icon icon-product'></i> <strong>" + product +"</strong> " + linkedProjectsTip +"</td></tr>");
                    for(var j in data.multiLinkedProjects)
                    {
                        if(i == j)
                        {
                            html = ''
                            for(k in data.multiLinkedProjects[j])
                            {
                                var project = data.multiLinkedProjects[j][k];
                                html += "<p><i class='icon icon-project'></i> " + project +"</p>";
                            }
                            $('#promptTable').append("<tr><td style='padding-left:40px'>" + html + "</td></tr>");
                        }
                    }
                }

                changed = false;
                $('#promptBox').modal({show: true});
            }

            if(!changed) $this.val(oldParent).trigger("chosen:updated");
            $this.attr('data-parent', $this.val());
        });
    });
});
