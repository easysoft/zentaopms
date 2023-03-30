<style>
.tree-group {position: relative;}
.tree-group > .module-name {white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%; display: block;}
.tree li.has-list.open:before {left: 6px;}
.main-table-content {display: flex; gap: 20px;}
.main-table-content > .side {flex: 0 0 300px;}
.main-table-content > .content {flex: 1;}

/* css for mian */
#mainContent > #sideBar {flex: 0 0 150px; overflow-x: auto; padding-right: 5px;}
[lang^=zh] #mainContent > #sideBar {flex: 0 0 180px;}

/* css for tree */

.tree li.has-list.open:before {content: unset;}
.tree li > a {max-width: 100%; padding: 2px;}
.file-tree  a {height: 30px;}
.flex-between {display: flex; align-items: center; justify-content: space-between;}
.flex-center {display: flex; align-items: center; justify-content: center;}
.flex-start {display: flex; align-items: center;}
.tree li > .list-toggle {top: 4px;}
.input-tree {width: 120px;}
.tree-icon {position: absolute; right: 0;}
.tree li.has-input {overflow: hidden;}
.img-lib {flex: 0 0 16px; height: 14px;}
.tree-icon {position: absolute; right: 0;}
.tree li > a {max-width: 100%; padding: 2px;}
.file-tree  a.show-icon > div {padding-right: 15px;}
.tree li.has-input {overflow: hidden;}
#fileTree .icon {font-size: 14px; margin-right: 5px;}
#fileTree .title {font-size: 16px; height: 20px; margin-top: 5px; margin-bottom: 5px;}
/* css for sidebar */
.sidebar-toggle {flex: 0 0 16px;}
.sidebar-toggle > .icon {width: 12px; height: 30px; margin-top: -10px; line-height: 30px; color: #fff; text-align: center; background: #7dcdfe; border-radius: 6px; cursor: pointer;}

.spliter {flex: 0 0 12px;}
.spliter-btn {height: 28px; width: 10px; background: #fff; position: absolute; top: 30%; left: -1px; border: 1px solid #D9D9D9; border-radius: 2px;}
.spliter-btn > .spliter-inner {width: 4px; height: 12px; border-left: 1px solid #D9D9D9; border-right: 1px solid #D9D9D9;}

#mainContent .table-empty-tip > p, #createDocs {display: inline-block;}
.createDropdown a.btn-primary, .createDropdown a.btn-info {border-right: 1px solid rgba(255,255,255,0.2);}
.createDropdown button.dropdown-toggle.btn-primary, .createDropdown button.dropdown-toggle.btn-info {padding: 6px;}
.createDropdown ul > li {text-align: left;}
.createDropdown .btn.btn-info:hover {box-shadow: none;}

.table .c-name > a:first-child {display: inline-block; max-width: 90%; overflow: hidden;}
.table .c-name > .ajaxCollect {float: right; position: relative; right: 10px; top: -3px;}
table.table > thead > tr {height: 32px;}

#bysearchTab::before {display: none;}
#leftBar .selectBox #currentItem {width: 150px; display: flex; align-items: center;}
[lang^=zh] #leftBar .selectBox #currentItem {width: 180px;}
#leftBar .selectBox #currentItem > .text {overflow: hidden; text-align: left; flex: 0 1 100%;}
</style>

<div id="fileTree" class="file-tree">
<?php if($type == 'project'):?>
<div class="project-tree">
    <div class="title"><i class="icon icon-project text-primary"></i><?php echo $lang->projectCommon?></div>
    <div id="projectTree" data-id="project"></div>
</div>
<div class="execution-tree">
    <div class="title"><i class="icon icon-run text-primary"></i><?php echo $lang->execution->common?></div>
    <div id="executionTree" data-id="execution"></div>
</div>
<div class="annex-tree">
    <div class="title"><i class="icon icon-paper-clip text-primary"></i><?php echo $lang->files?></div>
    <div id="annexTree" data-id="annex"></div>
</div>
<?php endif;?>
</div>

<!-- Code for dropdown menu. -->
<div class='hidden' id='dropDownData'>
  <ul class='libDorpdown'>
    <?php if(common::hasPriv('tree', 'browse')):?>
    <li data-method="addCataLib" data-has-children='%hasChildren%'  data-libid='%libID%' data-moduleid="%moduleID%" data-type="add"><a><i class="icon icon-icon-add-directory"></i><?php echo $lang->doc->libDropdown['addModule'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv('doc', 'editLib')):?>
    <li data-method="editLib"><a href='<?php echo inlink('editLib', 'libID=%libID%');?>' data-toggle='modal' data-type='iframe'><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['editLib'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv('doc', 'deleteLib')):?>
    <li data-method="deleteLib"><a href='<?php echo inlink('deleteLib', 'libID=%libID%');?>' target='hiddenwin'><i class="icon icon-trash"></i><?php echo $lang->doc->libDropdown['deleteLib'];?></a></li>
    <?php endif;?>
  </ul>
  <ul class='moduleDorpdown'>
    <?php if(common::hasPriv('tree', 'browse')):?>
    <li data-method="addCataBro" data-type="add" data-id="%moduleID%"><a><i class="icon icon-icon-add-directory"></i><?php echo $lang->doc->libDropdown['addSameModule'];?></a></li>
    <li data-method="addCataChild" data-type="add" data-id="%moduleID%" data-has-children='%hasChildren%'><a><i class="icon icon-icon-add-directory"></i><?php echo $lang->doc->libDropdown['addSubModule'];?></a></li>
    <li data-method="editCata" class='edit-module'><a data-href='<?php echo helper::createLink('tree', 'edit', 'moduleID=%moduleID%&type=doc');?>'><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['editModule'];?></a></li>
    <li data-method="deleteCata"><a href='<?php echo helper::createLink('tree', 'delete', 'rootID=%libID%&moduleID=%moduleID%');?>' target='hiddenwin'><i class="icon icon-trash"></i><?php echo $lang->doc->libDropdown['delModule'];?></a></li>
    <?php endif;?>
  </ul>
</div>
<div class='hidden' data-id="ulTreeModal">
  <ul data-id="liTreeModal" class="menu-active-primary menu-hover-primary has-input">
    <li data-id="insert" class="has-input">
      <input data-target="%target%" class="form-control input-tree"></input>
    </li>
  </ul>
</div>
<div class="hidden" data-id="aTreeModal">
  <a href="###" style="position: relative" data-has-children="false" data-action="true" title="%name%" data-id="%id%">
    <div class="text h-full w-full flex-between overflow-hidden" style="position: relative;">
      <span style="padding-left: 5px;">%name%</span>
      <i class="icon icon-drop icon-ellipsis-v tree-icon hidden" data-iscatalogue="true"></i>
    </div>
  </a>
</div>

<script>
$(function()
{
    var moduleData = {
        "name"       : "",
        "createType" : "",
        "libID"      : "",
        "parentID"   : "",
        "objectID"   : "",
        "moduleType" : "",
        "order"      : "",
        "isUpdate"   : ""
    };

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
    }

    /**
     * Render tree dom.
     *
     * @param string treee
     * @param array treeeData
     * @access public
     * @return void
     */
    function initTree(ele, treeData)
    {
        var imgObj = {
            'annex': 'annex',
            'lib': 'wiki-file-lib',
            'api': 'interface',
            'execution': 'wiki-file-lib',
        };

        ele.tree(
        {
            initialState: 'active',
            data: treeData,
            itemCreator: function($li, item)
            {
                var objectType = config.currentModule == 'api' ? item.objectType : item.type;
                var libClass = ['lib', 'annex', 'api', 'execution'].indexOf(objectType) !== -1 ? 'lib' : '';
                var hasChild = item.children ? !!item.children.length : false;
                var link     = item.type != 'execution' || item.hasAction ? '###' : '#';
                var $item    = '<a href="' + link + '" style="position: relative" data-has-children="' + hasChild + '" title="' + item.name + '" data-id="' + item.id + '" class="' + libClass + '" data-type="' + item.type + '" data-action="' + item.hasAction + '">';

                $item += '<div class="text h-full w-full flex-start overflow-hidden">';
                if(libClass == 'lib') $item += '<div class="img-lib" style="background-image:url(static/svg/' + imgObj[item.type || 'lib'] + '.svg)"></div>';
                $item += '<span style="padding-left: 5px;">';
                $item += item.name
                $item += '</span>';
                $item += '<i class="icon icon-drop icon-ellipsis-v hidden tree-icon" data-isCatalogue="' + (libClass ? false : true) + '"></i>';
                $item += '</div>';
                $item += '</a>';

                $li.append($item);
                $li.addClass(libClass);
                if(item.active) $li.addClass('active open in');
            }
        });

        ele.on('click', '.icon-drop', function(e)
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
                parentID   = libID;
                moduleID   = libID;
                moduleType = $module.data('type');
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
                "moduleType": ['lib', 'execution'].indexOf(moduleType) !== -1 ? 'doc' : moduleType,
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
            $(this).closest('body').append(dropDown);

            e.stopPropagation();
        }).on('blur', '.file-tree input.input-tree', function()
        {
            var $input = $(this);
            var value = $input.val();
            if(!value)
            {
                $input.closest('[data-id=insert]').remove();
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
                $input.closest('ul').find('.has-input').css('padding-left', '15px');
                $input.after(resultDom);
                $input.remove();
                if(moduleData.isUpdate)
                {
                    $.getJSON(createLink('doc', 'tableContents', 'type=' + objectType + '&objectID=' + objectID , 'json'), function(data){
                        var treeData = JSON.parse(data.data);
                        $('#fileTree').data('zui.tree').reload(treeData.libTree);
                        $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary");
                    });
                }
            });
        });
    }

    if(Array.isArray(treeData))
    {
        initTree($('#fileTree'), treeData);
    }
    else
    {
        initTree($('#projectTree'), treeData.project);
        initTree($('#annexTree'), treeData.annex);
        if(treeData.execution&& treeData.execution.length)
        {
            initTree($('#executionTree'), treeData.execution);
        }
        else
        {
            $('.execution-tree').remove();
        }
    }

    $('li.has-list > ul, #fileTree').addClass("menu-active-primary menu-hover-primary");

    $('#fileTree').on('mousemove', 'a', function()
    {
        if($(this).data('type') == 'annex') return;
        if(!$(this).data('action')) return;

        var libClass = '.libDorpdown';
        if(!$(this).hasClass('lib')) libClass = '.moduleDorpdown';

        $(this).find('.icon').removeClass('hidden');
        $(this).addClass('show-icon');   if($(libClass).find('li').length == 0) return false;

    }).on('mouseout', 'a', function()
    {
        $(this).find('.icon').addClass('hidden');
        $(this).removeClass('show-icon');
    }).on('click', 'a', function(e)
    {
        if(!$(this).data('action')) return;

        var isLib    = $(this).hasClass('lib');
        var moduleID = $(this).data('id');
        var libID    = 0;
        var params   = '';

        if(isLib)
        {
            if($(this).data('type') == 'annex' && !canViewFiles) return false;

            libID    = moduleID;
            moduleID = 0;
        }
        else
        {
            libID = $(this).closest('.lib').data('id');
        }

        if(!linkParams) linkParams = '%s';
        linkParams = linkParams.replace('%s', '&libID=' + libID + '&moduleID=' + moduleID);
        if(config.currentModule == 'api') linkParams =  linkParams.substring(1);

        var link = $(this).data('type') == 'annex' ?  createLink(config.currentModule, 'showFiles', 'type=' + objectType + '&objectID=' + objectID) : createLink(config.currentModule, config.currentModule == 'api' ? 'index' : 'tableContents', linkParams);
        location.href = link
    });

    $('body').on('click', function()
    {
        $('.dropdown-in-tree').remove();
    }).on('click', '.sidebar-toggle', function()
    {
        var $icon = $(this).find('.icon');
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

        var $docListForm = $('#docListForm').data('zui.table');
        $docListForm.fixHeader();
        $docListForm.fixFooter();
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
                    var $rootDom = $('[data-id=' + item.moduleid + ']a + ul');
                    $rootDom.append($input);
                    $rootDom.closest('.tree').data('zui.tree').expand($('li[data-id="' + item.libid + '"]'));
                }
                else
                {
                    var $input   = $('[data-id=ulTreeModal]').html();
                    var $rootDom = $('[data-id=' + item.libid + ']a');
                    var $li      = $rootDom.parent();
                    moduleData.isUpdate = true;
                    $rootDom.after($input);
                    $li.addClass('open in has-list');
                }
                $rootDom.parent().find('input').focus();
                break;
            case 'addCataBro' :
                moduleData.createType = 'same';
                var $input   = $('[data-id=liTreeModal]').html();
                var $rootDom = $('#fileTree li[data-id=' + item.id + ']');
                $rootDom.after($input);
                $rootDom.closest('ul').find('.has-input').css('padding-left', '0');
                $('#fileTree').find('input').focus();
                break;
            case 'addCataChild' :
                moduleData.parentID = item.id;
                if(item.hasChildren)
                {
                    var $input   = $('[data-id=liTreeModal]').html();
                    var $rootDom = $('#fileTree [data-id=' + item.id + ']a + ul');
                    var $rootDom = $('#fileTree [data-id=' + item.id + ']a + ul');
                    $rootDom.closest('.tree').data('zui.tree').expand($('li[data-id="' + item.id + '"]'));
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
        var $input = $(this);
        var $tree  = $input.closest('.tree');
        var value = $input.val();
        if(!value)
        {
            $input.closest('[data-id=insert]').remove();
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
            $input.closest('ul').find('.has-input').css('padding-left', '15px');
            $input.after(resultDom);
            $input.remove();
            if(moduleData.isUpdate)
            {
                $.getJSON(createLink('doc', 'tableContents', 'type=' + objectType + '&objectID=' + objectID , 'json'), function(data){
                    var treeData = JSON.parse(data.data);
                    if(Array.isArray(treeData.libTree))
                    {
                        $('#fileTree').data('zui.tree').reload(treeData.libTree);
                        $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary");
                    }
                    else
                    {
                        $tree.data('zui.tree').reload(treeData.libTree[$tree.data('id')]);
                    }
                });
            }
        });
    }).on('keydown', '.file-tree input.input-tree', function(e)
    {
        if(e.keyCode == 13) $(this).trigger('blur');
    });
})
</script>
