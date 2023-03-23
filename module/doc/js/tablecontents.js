$(function()
{
    /**
     * Render Dropdown dom.
     *
     * @access public
     * @return string
     */
    function renderDropdown(option)
    {
        var libClass = '.libDorpdown';
        if(option.type != 'dropDownLibrary') libClass = '.moduleDorpdown';
        if($(libClass).find('li').length == 0) return '';
        var dropdown = '<ul class="dropdown-menu dropdown-in-tree" id="' + option.type + '" style="display: unset; left:' + option.left + 'px; top:' + option.top + 'px;">';
        dropdown += $(libClass).html().replace(/%libID%/g, option.libID).replace(/%moduleID%/g, option.moduleID).replace(/%hasChildren%/g, option.hasChildren);
        dropdown += '</ul>';
        return dropdown;
    };

    $('#fileTree').tree(
    {
        initialState: 'active',
        data: treeData,
        itemCreator: function($li, item)
        {
            var libClass = ['lib', 'annex', 'api', 'execution'].indexOf(item.type) !== -1 ? 'lib' : '';
            var hasChild = item.children ? !!item.children.length : false;
            var $item = '<a href="###" data-has-children="' + hasChild + '" title="' + item.name + '" data-id="' + item.id + '" class="' + libClass + '" data-type="' + item.type + '">';
            $item += '<div class="text h-full w-full flex-between">' + item.name;
            $item += '<i class="icon icon-drop icon-ellipsis-v float-r hidden" data-isCatalogue="' + (item.type ? false : true) + '"></i>';
            $item += '</div>';
            $item += '</a>';
            $li.append($item);
            $li.addClass(libClass);
            if (item.active) $li.addClass('active open in');
        }
    });
    $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary");

    $('#fileTree').on('mousemove', 'a', function()
    {
        if($(this).data('type') == 'annex') return;
        var libClass = '.libDorpdown';
        if(!$(this).hasClass('lib')) libClass = '.moduleDorpdown';
        if($(libClass).find('li').length == 0) return false;
        $(this).find('.icon').removeClass('hidden');
    }).on('mouseout', 'a', function()
    {
        $(this).find('.icon').addClass('hidden');
    }).on('click', 'a', function(e)
    {
        var isLib    = $(this).hasClass('lib');
        var moduleID = $(this).data('id');
        var libID    = 0;
        var params   = '';

        if(isLib)
        {
            if($(this).data('type') == 'annex') return false;

            libID     = moduleID;
            moduleID  = 0;
        }
        else
        {
            libID   = $(this).closest('.lib').data('id');
        }
        linkParams = linkParams.replace('%s', '&libID=' + libID + '&moduleID=' + moduleID);
        location.href = createLink('doc', 'tableContents', linkParams);
    });

    var moduleData = {
        "name"       : "",
        "createType" : "",
        "libID"      : '',
        "parentID"   : '',
        "objectID"   : '',
        "moduleType" : '',
        "order"      : "",
        "isUpdate"   : ""
    };
    $('#fileTree').on('click', '.icon-drop', function(e)
    {
        $('.dropdown-in-tree').css('display', 'none');
        var isCatalogue = $(this).attr('data-isCatalogue') === 'false' ? false : true;
        var dropDownID  = isCatalogue ? 'dropDownCatalogue' : 'dropDownLibrary';
        var libID       = 0;
        var moduleID    = 0;
        var parentID    = 0;
        var $module     = $(this).closest('a');
        var hasChildren = $module.data('has-children');
        var moduleType  = '';
        if($module.hasClass('lib'))
        {
            libID      = $module.data('id');
            moduleType = $module.data('type');
            parentID   = libID;
        }
        else
        {
            moduleID   = $module.data('id');
            libID      = $module.closest('.lib').data('id');
            moduleType = $module.closest('.lib').data('type');
            parentID   = $module.closest('ul').closest('.lib').data('id');
        }
        moduleData = {
            "libID"     : libID,
            "parentID"  : parentID,
            "objectID"  : moduleID,
            "moduleType": moduleType == 'lib' ? 'doc' : moduleType,
        };
        var option = {
            left        : e.pageX,
            top         : e.pageY,
            type        : dropDownID,
            libID       : libID,
            moduleID    : moduleID,
            hasChildren : hasChildren
        };
        var dropDown = renderDropdown(option);
        $(".m-doc-tablecontents").append(dropDown);
        e.stopPropagation();
    });
    $('body').on('click', function(e)
    {
        $('.dropdown-in-tree').remove();
    }).on('click', '.sidebar-toggle', function()
    {
        var $this = $(this);
        var $icon = $this.find('.icon');
        if($('#sideBar').hasClass('hidden'))
        {
            $icon.addClass('icon-angle-left');
            $icon.removeClass('icon-angle-right');
            $('#sideBar').removeClass('hidden');
        }
        else
        {
            $icon.addClass('icon-angle-right');
            $icon.removeClass('icon-angle-left');
            $('#sideBar').addClass('hidden');
        }
    }).on('click', '.dropdown-in-tree li', function(e)
    {
        var item = $(this).data();
        if($(this).hasClass('edit-module'))
        {
            new $.zui.ModalTrigger({
                keyboard : true,
                type     : 'ajax',
                url      : $(this).find('a').data('href')
            }).show();
        }
        if(item.type !== 'add') return;
        var $item             = $(this);
        moduleData.parentID   = 0;
        moduleData.isUpdate   = false;
        moduleData.createType = 'child';
        switch(item.method)
        {
            case 'addCataLib' :
                if(item.hasChildren)
                {
                    var $input   = $('[data-id=liTreeModal]').html();
                    var $rootDom = $('[data-id=' + item.libid + ']a').parent().find('ul');
                }
                else
                {
                    var $input   = $('[data-id=ulTreeModal]').html();
                    var $rootDom = $('[data-id=' + item.libid + ']a').parent();
                    moduleData.isUpdate = true;
                    $rootDom.addClass('open in has-list');
                }
                $rootDom.append($input);
                $rootDom.find('input').focus();
                break;
            case 'addCataBro' :
                moduleData.createType = 'same';
                var $input   = $('[data-id=liTreeModal]').html();
                var $rootDom = $('#fileTree [data-id=' + item.id + ']li');
                $rootDom.after($input);
                $rootDom.find('input').focus();
                break;
            case 'addCataChild' :
                moduleData.parentID   = item.id;
                if(item.hasChildren)
                {
                    var $input   = $('[data-id=liTreeModal]').html();
                    var $rootDom = $('#fileTree [data-id=' + item.id + ']li').find('ul');
                }
                else
                {
                    var $input          = $('[data-id=ulTreeModal]').html();
                    var $rootDom        = $('#fileTree [data-id=' + item.id + ']li');
                    moduleData.isUpdate = true;
                    $rootDom.addClass('open in has-list');
                }
                $rootDom.append($input);
                $rootDom.find('input').focus();
                break;
        }
    }).on('blur', '.file-tree input.input-tree', function()
    {
        var $this = $(this);
        var value = $this.val();
        if(!value)
        {
            $this.closest('[data-id=insert]').remove();
            return;
        }

        moduleData.name = value;
        $.post(createLink('tree', 'ajaxCreateModule'), moduleData, function(result)
        {
            result = JSON.parse(result);
            if(result.result == 'fail')
            {
                bootbox.alert(
                    result.message[0],
                    function()
                    {
                        setTimeout(function()
                        {
                            $('.file-tree .input-tree').focus()
                        }, 10)
                    }
                );
                return false;
            }
            var module    = result.module;
            var resultDom = $('[data-id=aTreeModal]').html().replace(/%name%/g, module.name).replace(/%id%/g, module.id).replace('insert', module.id);
            $this.parent().append(resultDom);
            $this.remove();
            if(moduleData.isUpdate)
            {
                $.getJSON(createLink('doc', 'tableContents', 'type=' + objectType, 'json'), {}, function(data){
                        var treeData = JSON.parse(data.data);
                        $('#fileTree').data('zui.tree').reload(treeData.libTree);
                        $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary");
                    }, 'json');
            }
        });
    });
});
