/* Set the story priview link. */
function setPreview()
{
    if($('#story').val() == 0)
    {
        $('#preview').addClass('hidden');
    }
    else
    {
        storyLink = createLink('story', 'view', "storyID=" + $('#story').val());
        if(!isonlybody)
        {
            var concat = storyLink.indexOf('?') < 0 ? '?'  : '&';
            storyLink  = storyLink + concat + 'onlybody=yes';
        }

        $('#preview').addClass('iframe');
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
        if($(this).val() === 'showmore') showSearchModal();
    });

    $(document).on('click', '#priRequiredBox', function()
    {
        $('#priSelect').removeClass('required');
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

    if(!isonlybody) $("#preview").modalTrigger({width:960, type:'iframe'});

    $('[data-toggle=tooltip]').tooltip();

    initSteps();

    $('#pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });

    $.get(createLink('testcase', 'ajaxGetStatus', 'methodName=create'), function(status)
    {
        $('#status').val(status).change();
    });

    $('#subNavbar li[data-id="testcase"]').addClass('active');

    $('#customField').click(function()
    {
        hiddenRequireFields();
    });

    /* Implement a custom form without feeling refresh. */
    $('#formSettingForm .btn-primary').click(function()
    {
        saveCustomFields('createFields');
        return false;
    });
});


function loadAllNew(productID)
{
    loadProductBranchesNew(productID);
}

function loadProductBranchesNew(productID)
{
    $('#branch').remove();

    var param     = page == 'create' ? 'active' : 'all';
    var oldBranch = page == 'edit' ? caseBranch : 0;
    var param     = "productID=" + productID + "&oldBranch=" + oldBranch + "&param=" + param;
    if(typeof(tab) != 'undefined' && (tab == 'execution' || tab == 'project')) param += "&projectID=" + objectID;
    $.get(createLink('branch', 'ajaxGetBranches', param), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', config.currentMethod == 'create' ? '120px' : '95px');
        }

        loadProductModulesNew(productID);
        setStories();
    })
}

function loadProductModulesNew(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = $('#branch').val();
    if(!branch) branch = 0;
    var currentModuleID = config.currentMethod == 'edit' ? $('#module').val() : 0;
    link = createLink('testcase', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=&currentModuleID=' + currentModuleID);
    $('#moduleIdBox').load(link, function()
    {
        var $inputGroup = $(this);
        $inputGroup.find('select').chosen()
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>");
        $inputGroup.fixInputGroup();
    });
    setScenes();
    setStories();
}

function setScenes()
{
    moduleID  = $('#module').val();
    productID = $('#product').val();
    branch    = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('testcase', 'ajaxGetModuleScenes', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&stype=2&storyID=0&onlyOption=false&status=noclosed&limit=50&type=full&hasParent=1');

    $('#sceneIdBox').load(link, function()
    {
        $(this).find('select').chosen()
    });
}

function loadBranchNew()
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    loadProductModulesNew($('#product').val(), branch);

    setStories();
}

function loadModuleRelatedNew()
{
    setScenes();
    setStories();
}
