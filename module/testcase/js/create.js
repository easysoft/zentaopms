/* Set the story priview link. */
function setPreview()
{
    if(!$('#story').val())
    {
        $('#preview').addClass('hidden');
    }
    else
    {
        storyLink = createLink('story', 'view', "storyID=" + $('#story').val());
        var concat = config.requestType != 'GET' ? '?'  : '&';
        storyLink  = storyLink + concat + 'onlybody=yes';
        $('#preview').removeClass('hidden');
        $('#preview').attr('href', storyLink);
    }
}

$(function()
{
    var $searchStories = $('#searchStories');
    var lastSearchFn = false;
    var $searchInput = $('#storySearchInput');
    var $searchResult = $('#searchResult');
    var $selectedItem;
    var showSearchModal = function()
    {
        $searchStories.modal('show').on('shown.zui.modal', function()
        {
            var key = $('#story_chosen .chosen-results > li.no-results > span').text();
            if(key) $searchInput.val(key).trigger('change');
            $searchInput.focus();
        });
    };
    $(document).on('change', '#story', function()
    {
       if($(this).val() === 'showmore')
       {
            showSearchModal();
       }
    });

    $(document).on('click', '#story_chosen .chosen-results > li.no-results', showSearchModal);

    $searchStories.on('hide.zui.modal', function()
    {
        var key = '';
        var $story = $('#story');
        if($selectedItem && $selectedItem.length)
        {
            key = $selectedItem.data('key');
            if(!$story.children('option[value="' + key + '"]').length)
            {
                $story.prepend('<option value="' + key + '">' + $selectedItem.text() + '</option>');
            }
        }
        $story.val(key).trigger("chosen:updated");
        $selectedItem = null;
    });

    var selectItem = function(item)
    {
        $selectedItem = $(item).first();
        $searchStories.modal('hide');
    };

    $searchResult.on('click', 'a', function(){selectItem(this);}).on('mouseenter', 'a', function()
    {
        $searchResult.find('a.selected').removeClass('selected');
        $(this).addClass('selected');
    }).on('mouseleave', 'a', function()
    {
        $(this).removeClass('selected');
    });

    $searchInput.on('paste change keyup', function()
    {
        if(lastSearchFn) clearTimeout(lastSearchFn);
        lastSearchFn = setTimeout(function()
        {
            var key = $searchInput.val() || '';
            if(key && key != $searchInput.data('lastkey'))
            {
                $searchResult.empty().append('<li class="loading"><i class="icon-spin icon-spinner icon-2x"></i></li>');
                var branch = $('#branch').val();
                if(typeof(branch) == 'undefined') branch = 0;
                var link = createLink('story', 'ajaxSearchProductStories', 'key=' + key + '&productID=' + $('#product').val() + '&branch=' + branch + '&moduleID=' + $('#module').val() + '&storyID=0&status=noclosed&limit=50');
                $.getJSON(link, function(result)
                {
                    $searchResult.empty();
                    if(result)
                    {
                        for(var key in result)
                        {
                            if(key === 'info')
                            {
                                $searchResult.append('<li class="tip">' + result[key] + '</li>');
                            }
                            else
                            {
                                $searchResult.append("<li><a href='javascript:;' data-key='" + key + "'>" + result[key] + "</a></li>");
                            }
                        }
                        $searchResult.find('li:first > a').addClass('selected');
                    }
                });
                $searchInput.data('lastkey', key);
            }
            else if(!key.length)
            {
                $searchResult.empty();
            }
        }, 500);
    }).on('keyup', function(e)
    {
        var $selected = $searchResult.find('a.selected').first();
        if(e.keyCode == 38) // keyup
        {
            var $prev = $selected.closest('li').prev().children('a');
            if($prev.length)
            {
                $selected.removeClass('selected');
                $prev.addClass('selected');
            }
        }
        else if(e.keyCode == 40) // keydown
        {
            var $next = $selected.closest('li').next().children('a');
            if($next.length)
            {
                $selected.removeClass('selected');
                $next.addClass('selected');
            }
        }
        else if(e.keyCode == 13) selectItem($selected);
    });

    $("#preview").modalTrigger({width:960, type:'iframe'});

    $('[data-toggle=tooltip]').tooltip();

    /* First unbind ajaxForm for form.*/
    $("form[data-type='ajax']").unbind('submit');
    setForm();

    /* Bind ajaxForm for form again. */
    $.ajaxForm("form[data-type='ajax']", function(response)
    {
        if(response.message) alert(response.message);
        if(response.locate)
        {
            if(response.locate == 'reload' && response.target == 'parent')
            {
                parent.$.cookie('selfClose', 1);
                parent.$.closeModal(null, 'this');
            }
            else
            {
                location.href = response.locate;
            }
        }
        return false;
    });

    initSteps();
})
