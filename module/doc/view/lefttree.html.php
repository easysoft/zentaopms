<style>
.tree li.has-list.open:before {display: none;}
.tree-group {position: relative;}
.tree-group > .module-name {white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%; display: block;}
.tree li.has-list.open:before {left: 6px;}
.main-table-content {display: flex; gap: 20px;}
.main-table-content > .side {flex: 0 0 300px;}
.main-table-content > .content {flex: 1;}
#main {margin-bottom: 0;}

/* css for mian */
#mainContent > #sideBar {flex: 0 0 180px; overflow-x: auto; padding-right: 5px;}

/* css for tree */
#fileTree .title {font-size: 16px; height: 20px; margin-top: 5px; margin-bottom: 5px;}
#fileTree.tree li.has-list.open:before {content: unset;}
#fileTree.tree li > a {max-width: 100%; padding: 2px;}
.file-tree  a {height: 30px;}
.flex-between {display: flex; align-items: center; justify-content: space-between;}
.flex-center {display: flex; align-items: center; justify-content: center;}
.flex-start {display: flex; align-items: center;}
#fileTree.tree li > .list-toggle {top: 4px;}
.input-tree {width: 120px;}
.tree-icon {position: absolute; right: 0;}
#fileTree.tree li.has-input {overflow: hidden;}
#fileTree.tree li.has-input  > input.input-bro {margin-left: 15px;}
.img-lib {flex: 0 0 14px; height: 14px; margin-right: 5px; margin-bottom: 2px;}
.file-icon {width: 14px; margin-bottom: 4px;}
.tree-icon {position: absolute; right: 0;}
#fileTree.tree li > a {max-width: 100%; padding: 2px;}
.file-tree  a.show-icon > div,
.file-tree  a.hover > div {padding-right: 15px;}
.tree-text {overflow: hidden; min-width: 50px;}
i.btn-info, i.btn-info:hover {border: none; background: #fff; box-shadow: unset;}
.tree-version-trigger {padding: 0 10px; width: 54px; border-radius: 5px; background: #F9F9F9; display: flex; align-items: center;}
.tree-version-trigger > .text {overflow: hidden; flex: 0 0 30px;}
.file-tree > .bd-b {border-bottom: 1px solid #EDEEF2;}
.file-tree > .tree-child {padding: 5px 0; position: relative;}

/* css for sidebar */
.sidebar-toggle {flex: 0 0 16px;}
.sidebar-toggle > .icon {width: 12px; height: 30px; margin-top: -10px; line-height: 30px; color: #fff; text-align: center; background: #7dcdfe; border-radius: 6px; cursor: pointer; padding-left: 0; padding-top: 0;}
.bottom-btn-tree {position: absolute; bottom: 15px; left: 25px;}

.spliter {flex: 0 0 12px;}
.spliter-btn {height: 28px; width: 10px; background: #fff; position: absolute; top: 30%; left: -1px; border: 1px solid #D9D9D9; border-radius: 2px;}
.spliter-btn > .spliter-inner {width: 4px; height: 12px; border-left: 1px solid #D9D9D9; border-right: 1px solid #D9D9D9;}

#mainContent .table-empty-tip > p, #createDocs {display: inline-block;}
.createDropdown a.btn-primary, .createDropdown a.btn-info {border-right: 1px solid rgba(255,255,255,0.2);}
.createDropdown button.dropdown-toggle.btn-primary, .createDropdown button.dropdown-toggle.btn-info {padding: 6px;}
.createDropdown ul > li {text-align: left;}
.createDropdown .btn.btn-info:hover {box-shadow: none;}

#leftBar .selectBox #currentItem {width: 150px; display: flex; align-items: center;}
[lang^=zh] #leftBar .selectBox #currentItem {width: 180px;}
#leftBar .selectBox #currentItem > .text {overflow: hidden; text-align: left; flex: 0 1 100%;}
.dropdown-in-tree {max-height: 293px; overflow-y: auto;}
.before-tree-item {flex: 0 0 14px; margin-right: 5px; margin-bottom: 2px;}

/* Catalog sort style. */
.sortable-sorting .catalog > a {cursor: move;}
.sortable-sorting li .flex-start {opacity: 0.5;}
.sortable-sorting .drop-here .flex-start {background-color: #fff3e0;}
.sortable-sorting .drop-here .flex-start > * {opacity: 0.1;}
.sortable-sorting .drag-shadow .flex-start {opacity: 1 !important;}
.sortable-sorting .drag-shadow .icon-drop {visibility: hidden;}
.is-sorting > li .flex-start {opacity: 1; border-radius: 4px;}
.is-sorting > li ul {display: none !important;}
li.drag-shadow ul {display: none !important;}
#fileTree a.dragging-shadow {box-shadow: 0 1px 1px rgba(0,0,0,.05), 0 2px 6px 0 rgba(0,0,0,0.1);}
</style>

<?php
/* Release used for api space. */
js::set('release', isset($release) ? $release : 0);
js::set('versionLang', $lang->build->common);
js::set('spaceType', $this->session->spaceType);
js::set('rawModule', $this->app->rawModule);
js::set('rawMethod', $this->app->rawMethod);

/* ObjectType and objectID used for other space. */
js::set('objectType', isset($type) ? $type : '');
js::set('objectID',   isset($objectID) ? $objectID : '');
js::set('isFirstLoad', isset($isFirstLoad) ? $isFirstLoad: '');
js::set('canViewFiles', common::hasPriv('doc', 'showfiles'));
js::set('spaceMethod', $config->doc->spaceMethod);
js::set('canSortDocCatalog', common::hasPriv('doc', 'sortCatalog'));
js::set('canSortAPICatalog', common::hasPriv('api', 'sortCatalog'));
?>

<div id="fileTree" class="file-tree menu-active-primary menu-hover-primary">
<?php if(isset($type) and $type == 'project'):?>
  <div class="project-tree bd-b tree-child">
    <div class="title"><i class="icon icon-project btn-info"> </i><?php echo $lang->projectCommon?></div>
    <div id="projectTree" data-id="project"></div>
  </div>
  <div class="execution-tree bd-b tree-child">
    <div class="title"><i class="icon icon-run btn-info"> </i><?php echo $lang->execution->common?></div>
    <div id="executionTree" data-id="execution"></div>
  </div>
  <div class="annex-tree tree-child">
    <div class="title"><i class="icon icon-paper-clip btn-info"> </i><?php echo $lang->files?></div>
    <div id="annexTree" data-id="annex"></div>
  </div>
<?php endif;?>
</div>
<?php if($app->rawMethod == 'view' and common::hasPriv('doc', 'displaySetting')):?>
<div class="text-center bottom-btn-tree">
  <?php common::printLink('doc', 'displaySetting', '', $lang->doc->displaySetting, '', "class='btn btn-info btn-wide iframe' data-width='400px'", true, true);?>
</div>
<?php endif;?>

<!-- Code for dropdown menu. -->
<?php
$canAddCatalog['doc'] = common::hasPriv('doc', 'addCatalog');
$canAddCatalog['api'] = common::hasPriv('api', 'addCatalog');

$canEditCatalog['doc'] = common::hasPriv('doc', 'editCatalog');
$canEditCatalog['api'] = common::hasPriv('api', 'editCatalog');

$canDeleteCatalog['doc'] = common::hasPriv('doc', 'deleteCatalog');
$canDeleteCatalog['api'] = common::hasPriv('api', 'deleteCatalog');

$hasModulePriv['doc'] = $canAddCatalog['doc'] || $canEditCatalog['doc'] || $canDeleteCatalog['doc'];
$hasModulePriv['api'] = $canAddCatalog['api'] || $canEditCatalog['api'] || $canDeleteCatalog['api'];

$hasLibPriv['doc'] = $canAddCatalog['doc'] || common::hasPriv('doc', 'editLib') || common::hasPriv('doc', 'deleteLib');
$hasLibPriv['api'] = $canAddCatalog['api'] || common::hasPriv('api', 'editLib') || common::hasPriv('api', 'deleteLib');

js::set('canAddCatalog',    $canAddCatalog);
js::set('canEditCatalog',   $canEditCatalog);
js::set('canDeleteCatalog', $canDeleteCatalog);
js::set('hasModulePriv',    $hasModulePriv);
js::set('hasLibPriv',       $hasLibPriv);
?>
<div class='hidden' id='dropDownData'>
  <?php foreach(array('doc', 'api') as $module):?>
  <ul class='<?php echo $module;?>LibDorpdown'>
    <?php if($canAddCatalog[$module]):?>
    <li data-method="addCataLib" data-has-children='%hasChildren%'  data-libid='%libID%' data-moduleid="%moduleID%" data-type="add"><a><i class="icon icon-add-directory"></i><?php echo $lang->doc->libDropdown['addModule'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv($module, 'editLib')):?>
    <li data-method="editLib"><a href='<?php echo inlink('editLib', 'libID=%libID%');?>' data-toggle='modal' data-type='iframe'><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['editLib'];?></a></li>
    <?php endif;?>
    <?php if(common::hasPriv($module, 'deleteLib')):?>
    <li data-method="deleteLib"><a href='<?php echo inlink('deleteLib', 'libID=%libID%');?>' target='hiddenwin'><i class="icon icon-trash"></i><?php echo $lang->doc->libDropdown['deleteLib'];?></a></li>
    <?php endif;?>
  </ul>
  <ul class='<?php echo $module;?>ModuleDorpdown'>
    <?php if($canAddCatalog[$module]):?>
    <li data-method="addCataBro" data-type="add" data-id="%moduleID%"><a><i class="icon icon-add-directory"></i><?php echo $lang->doc->libDropdown['addSameModule'];?></a></li>
    <li data-method="addCataChild" data-type="add" data-id="%moduleID%" data-has-children='%hasChildren%'><a><i class="icon icon-add-directory"></i><?php echo $lang->doc->libDropdown['addSubModule'];?></a></li>
    <?php endif;?>
    <?php if($canEditCatalog[$module]):?>
    <li data-method="editCata" class='edit-module'><a data-href='<?php echo helper::createLink($module, 'editCatalog', "moduleID=%moduleID%&type=" . ($app->rawModule == 'api' ? 'api' : 'doc'));?>'><i class="icon icon-edit"></i><?php echo $lang->doc->libDropdown['editModule'];?></a></li>
    <?php endif;?>
    <?php if($canDeleteCatalog[$module]):?>
    <li data-method="deleteCata"><a href='<?php echo helper::createLink($module, 'deleteCatalog', 'rootID=%libID%&moduleID=%moduleID%');?>' target='hiddenwin'><i class="icon icon-trash"></i><?php echo $lang->doc->libDropdown['delModule'];?></a></li>
    <?php endif;?>
  </ul>
  <?php endforeach;?>
</div>
<div class='hidden' data-id="ulTreeModal">
  <ul data-id="liTreeModal" class="menu-active-primary menu-hover-primary has-input">
    <li data-id="insert" class="has-input flex-start">
      <input data-target="%target%" class="form-control input-tree overflow-hidden"></input>
    </li>
  </ul>
</div>

<script>
$(function()
{
    if(typeof linkParams == 'undefined') linkParams = '%s';

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

    var versionsData = {};
    var visibleSort  = false;

    /**
     * Render Dropdown dom.
     *
     * @access public
     * @return string
     */
    function renderDropdown(option)
    {
        var moduleType = option.moduleType == 'lib' ? 'doc' : option.moduleType;
        var libClass   = '.' + moduleType + 'LibDorpdown';
        if(option.type != 'dropDownLibrary') libClass = '.' + moduleType + 'ModuleDorpdown';
        if($(libClass).find('li').length == 0) return '';

        var dropdown = '<ul class="dropdown-menu dropdown-in-tree" id="' + option.type + '" style="display: unset; left:' + option.left + 'px; top:' + option.top + 'px;">';
        dropdown += $(libClass).html().replace(/%libID%/g, option.libID).replace(/%moduleID%/g, option.moduleID).replace(/%hasChildren%/g, option.hasChildren);
        dropdown += '</ul>';

        if(typeof(option.moduleType) != 'undefined' && option.moduleType == 'api') dropdown = dropdown.replace(/doc/g, 'api');
        return dropdown;
    }

    /*
     * Redner version dropdown dom.
     *
     * @param versions array
     * @access public
     * @return string
     */
    function renderDropVersion(option)
    {
        var versions = option.versions;
        if (!versions || !versions.length)
        {
            $dropdown = '';
        }
        else
        {
            var lib  = 0;
            var $lis = '<li><a href="###" data-id=0>' + versionLang + '</a></li>';
            for(i = 0; i< versions.length; i++)
            {
                var version = versions[i];
                $lis += '<li><a href="###"  data-id="' + version.id + '">' + version.version+ '</a></li>';
                lib   = version.lib;
            }
            var $dropdown = '<ul id="versionSwitcher" data-lib = "' + lib + '" class="dropdown-menu dropdown-in-tree" style="display: unset; left:' + option.left + 'px; top:' + option.top + 'px;">';
            $dropdown += $lis;
            $dropdown += '</ul>';
        }
        return $dropdown;
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
            'annex'     : 'annex',
            'api'       : 'interface',
            'lib'       : 'wiki',
            'execution' : 'wiki-file-lib',
            'text'      : 'wiki-file',
            'word'      : 'word',
            'ppt'       : 'ppt',
            'excel'     : 'excel'
        };

        ele.tree(
        {
            data: treeData,
            initialState: 'active',
            itemCreator: function($li, item)
            {
                if(item.type == 'apiDoc' && release) item.hasAction = false;
                if(typeof item.hasAction == 'undefined') item.hasAction = true;
                if(typeof item.active == 'undefined') item.active = 0;
                if(typeof docID != 'undefined' && item.id == docID) item.active = 1;
                if(['text', 'word', 'ppt', 'excel'].indexOf(item.type) !== -1) item.hasAction = false;

                var objectType  = config.currentModule == 'api' && ['project', 'product', 'execution'].indexOf(item.objectType) === false ? item.objectType : item.type;
                var libClass    = ['lib', 'annex', 'api', 'execution'].indexOf(objectType) !== -1 ? 'lib' : '';
                var moduleClass = item.type == 'doc' || item.type == 'apiDoc' ? 'catalog' : '';
                var sortClass   = '';
                if(config.currentMethod != 'view' && ((item.type == 'doc' && canSortDocCatalog) || (item.type == 'apiDoc' && canSortAPICatalog && !release))) sortClass = 'sort-module';

                var hasChild = item.children ? !!item.children.length : false;
                var link     = item.type != 'execution' || item.hasAction ? '###' : '#';
                var $item    = '<a href="' + link + '" style="position: relative" data-has-children="' + hasChild + '" title="' + item.name + '" data-id="' + item.id + '" class="' + libClass + sortClass + '" data-type="' + item.type + '" data-action="' + item.hasAction + '">';

                $item += '<div class="text h-full w-full flex-start overflow-hidden">';
                if((libClass == 'lib' && item.type != 'execution') || (item.type == 'execution' && item.hasAction)) $item += '<i class="before-tree-item icon icon-' + imgObj[item.type] +'-lib"></i>';
                if(['text', 'word', 'ppt', 'excel'].indexOf(item.type) !== -1) $item += '<div class="img-lib" style="background-image:url(static/svg/' + imgObj[item.type] + '.svg)"></div>';
                $item += '<div class="tree-text">';
                $item += item.name;
                $item += '</div>';

                if(libClass == 'lib' && item.versions && item.versions.length)
                {
                    var versionName = '';
                    for(var i = 0; i < item.versions.length; i++)
                    {
                        if(item.versions[i].id == release) versionName = item.versions[i].version;
                    }
                    $item += '<div class="tree-version-trigger" data-id="' +  item.id + '"><div class="text">' + (versionName || versionLang) + '</div><div class="caret"></div></div>';
                }

                var moduleType = config.currentModule == 'api' ? 'api' : 'doc';
                if(objectType) objectType.indexOf('api') === 0 ? 'api' : 'doc';
                if((libClass != 'lib' && hasModulePriv[moduleType]) || (libClass == 'lib' && hasLibPriv[moduleType])) $item += '<i class="icon icon-drop icon-ellipsis-v hidden tree-icon" data-isCatalogue="' + (libClass ? false : true) + '"></i>';
                $item += '</div>';
                $item += '</a>';
                if(item.versions) versionsData[item.id] = item.versions;

                $li.append($item);
                $li.addClass(libClass).addClass(moduleClass).attr('data-order', item.order).attr('data-type', item.type);
                if(item.active) $li.addClass('active');
            },
        });

        if(isFirstLoad) ele.data('zui.tree').collapse();

        var $leaf = ele.find('li.active > a');
        if($leaf.length && $('#fileTree').height() >= $('#sideBar').height() && $($leaf[$leaf.length - 1]).offset().top > $('#sideBar').height()) $('#sideBar')[0].scrollTop = $($leaf[$leaf.length - 1]).offset().top - 200;

        ele.on('click', '.icon-drop', function(e)
        {
            var $icon = $(this);
            $('.icon-drop').addClass('hidden');
            $(this).removeClass('hidden');
            $('#fileTree').find('a.hover').removeClass('hover');
            $('.dropdown-in-tree').remove();
            var isCatalogue = $icon.attr('data-isCatalogue') === 'false' ? false : true;
            var dropDownID  = isCatalogue ? 'dropDownCatalogue' : 'dropDownLibrary';
            var libID       = 0;
            var moduleID    = 0;
            var parentID    = 0;
            var $module     = $icon.closest('a');
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
                hasChildren : hasChildren,
                moduleType  : moduleType
            };

            var dropDown = renderDropdown(option);
            $icon.closest('body').append(dropDown);
            $('.dropdown-in-tree').attr('data-tree-id', $(this).closest('.tree').attr('id'));
            $icon.closest('a').addClass('hover');

            e.stopPropagation();
        }).on('mousemove', 'a', function()
        {
            if($(this).data('type') == 'annex') return;
            if(!$(this).data('action')) return;

            var moduleType = $(this).data('type') == 'api' ? 'api' : 'doc';
            var libClass   = '.' + moduleType + 'LibDorpdown';
            if(!$(this).hasClass('lib')) libClass = '.' + moduleType + 'ModuleDorpdown';

            $(this).find('.icon-drop').removeClass('hidden');
            $(this).addClass('show-icon');
            if($(libClass).find('li').length == 0) return false;

        }).on('mouseout', 'a', function()
        {
            if(!$(this).closest('a').hasClass('hover')) $(this).find('.icon-drop').addClass('hidden');
            $(this).removeClass('show-icon');
        }).on('click', 'a', function()
        {
            if($(this).data('type') == 'execution') return;

            var isLib    = $(this).hasClass('lib');
            var moduleID = $(this).data('id');
            var libID    = 0;

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

            return locatePage(libID, moduleID, $(this).data('type'));
        }).on('mousedown', 'a.sort-module', function()
        {
            visibleSort = true;
            var $element = $(this);
            setTimeout(function()
            {
                if(visibleSort) $element.addClass('dragging-shadow');
            }, 500);
        }).on('mouseup', 'a.sort-module', function()
        {
            visibleSort = false;
            $('a.sort-module').removeClass('dragging-shadow');
        }).on('click', '.tree-version-trigger', function(e)
        {
            $('.dropdown-in-tree').remove();
            var offset = $(this).offset();
            var option = {
                left     : offset.left,
                top      : offset.top + 20,
                versions : versionsData[$(this).data('id')]
            };
            var dropDown = renderDropVersion(option);
            $(this).closest('body').append(dropDown);
            $('#versionSwitcher').find('a[data-id=' + release + ']').parent().addClass('active');

            $('.dropdown-in-tree').attr('data-tree-id', $(this).closest('.tree').attr('id'));
            $(this).closest('a').addClass('hover');

            e.stopPropagation();
        });
    }

    if(Array.isArray(treeData))
    {
        initTree($('#fileTree'), treeData);
    }
    else
    {
        config.currentModule = 'doc';
        config.currentMethod = 'projectspace';
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

    /**
     * Lcate page.
     *
     * @param  int    libID
     * @param  int    moduleID
     * @param  string type
     * @access public
     * @return void
     */
    function locatePage(libID, moduleID, type)
    {
        if(!libID)    libID    = 0;
        if(!moduleID) moduleID = 0;
        linkParams = linkParams.replace('%s', 'libID=' + libID + '&moduleID=' + moduleID);
        if(rawModule == 'api' && rawMethod== 'view') spaceType = 'api';
        if(spaceType != 'api' && rawModule == 'api') linkParams = 'objectID=' + objectID + '&' + linkParams;
        var moduleName = spaceType == 'api' ? 'api' : 'doc';
        var methodName = '';
        if(spaceType == 'api')
        {
            methodName = 'index';
            linkParams =  linkParams.substring(1);
        }
        else if(type == 'annex')
        {
            methodName = 'showFiles';
            linkParams = 'type=' + objectType + '&objectID=' + objectID;
        }
        else if(['text', 'word', 'ppt', 'excel'].indexOf(type) !== -1)
        {
            methodName = 'view';
            linkParams = 'docID=' + moduleID;
        }
        else if(objectType == 'execution')
        {
            moduleName = 'execution';
            methodName = 'doc';
        }
        else
        {
            methodName = spaceMethod[objectType] ? spaceMethod[objectType] : 'teamSpace';
            if(['mine', 'view', 'collect', 'createdby', 'editedby'].indexOf(objectType) !== -1)
            {
                type = ['view', 'collect', 'createdby', 'editedby'].indexOf(type.toLowerCase()) !== -1 ? type.toLowerCase() : 'mine';
                linkParams = 'type=' + type + '&libID=' + libID + '&moduleID=' + moduleID;
            }
            if(type == 'apiDoc') linkParams = linkParams.replace('browseType=&', 'browseType=byrelease&').replace('param=0', 'param=<?php echo isset($release) ? $release : 0;?>');
        }

        location.href = createLink(moduleName, methodName, linkParams);
    }

    $('body').on('click', function()
    {
        $('a.sort-module').removeClass('dragging-shadow');
        var $dropdown = $('.dropdown-in-tree');
        if($dropdown.length)
        {
            var dropdown = $dropdown.data();
            var $hoverItem = $('#' + $dropdown.data('treeId')).find('a.hover');
            if($hoverItem.length)
            {
                $hoverItem.removeClass('hover');
                $hoverItem.find('.icon-drop').addClass('hidden');
            }
            $dropdown.remove();
        }
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

        if($('#docListForm').length > 0)
        {
            var $docListForm = $('#docListForm').data('zui.table');
            $docListForm.fixHeader();
            $docListForm.fixFooter();
        }
    }).on('click', '.dropdown-in-tree li', function(e)
    {
        var item = $(this).data();
        if($(this).hasClass('edit-module'))
        {
            var link = $(this).find('a').data('href');
            if(typeof(moduleData.moduleType) != 'undefined' && moduleData.moduleType == 'api') link = link.replace('doc', 'api');
            new $.zui.ModalTrigger({
                keyboard : true,
                type     : 'ajax',
                url      : link
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
                    var $rootDom = $('[data-id=' + item.libid + ']a.lib');
                    var $li      = $rootDom.parent();
                    moduleData.isUpdate = true;
                    $rootDom.after($input);
                    $li.addClass('open in has-list');
                }
                $input = $rootDom.parent().find('input');
                $input.focus();
                break;
            case 'addCataBro' :
                moduleData.createType = 'same';
                var $input   = $('[data-id=liTreeModal]').html();
                var $rootDom = $('#fileTree li[data-id=' + item.id + ']');
                $rootDom.after($input);
                $rootDom.closest('ul').find('.has-input').css('padding-left', '0');
                $input = $('#fileTree').find('input').addClass('input-bro');
                $input.focus();
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
                $input = $rootDom.find('input');
                $input.focus();
                break;
        }
    }).on('click', '#versionSwitcher a', function()
    {
        var libID      = $(this).closest('#versionSwitcher').data('lib');
        var moduleID   = $(this).data('id');
        var params     = 'libID=' + libID + '&moduleID=0&apiID=0&version=0&release=' + moduleID;
        var methodName = rawMethod;
        if(config.currentModule == 'doc')
        {
            params = linkParams.replace('%s', 'libID=' + libID + '&moduleID=0').replace('browseType=&', 'browseType=byrelease&').replace('param=0', 'param=' + moduleID);
            if(methodName == 'view') params = linkParams.replace('%s', 'libID=' + libID + '&moduleID=0&browseType=byrelease&orderBy=&status,id_desc&param=' + moduleID);
            methodName = objectType + 'Space';
        }
        location.href = createLink(config.currentModule, methodName, params);
    }).on('blur', '.file-tree input.input-tree', function()
    {
        var $input = $(this);
        var $tree  = $input.closest('.tree');
        var value = $input.val();
        if(!value)
        {
            $input.closest('[data-id=insert]').remove();
            $('.file-tree [data-id="liTreeModal"]').remove();
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

            var module = result.module;
            return locatePage(module.root, module.id, 'doc');
        });
    }).on('keydown', '.file-tree input.input-tree', function(e)
    {
        if(e.keyCode == 13) $(this).trigger('blur');
    });

    /* Make modules tree sortable */
    var $treeDom = objectType == 'project' ? $('#projectTree, #executionTree') :$('#fileTree');
    $treeDom.sortable(
    {
        trigger: 'a.sort-module',
        dropToClass: 'sort-to',
        stopPropagation: true,
        nested: true,
        selector: 'li',
        dragCssClass: 'drop-here',
        noShadow: false,
        start: function()
        {
            visibleSort = false;
            $('#dropDownCatalogue').remove();
            $('a.sort-module').removeClass('dragging-shadow');
        },
        canMoveHere: function($ele, $target)
        {
            if($ele && $target && $ele.parent().closest('li').attr('data-id') !== $target.parent().closest('li').attr('data-id')) return false;
        },
        targetSelector: function($ele, $root)
        {
            var $ul = $ele.closest('ul');
            setTimeout(function()
            {
                if($('#fileTree').hasClass('sortable-sorting')) $ul.addClass('is-sorting');
            }, 100);

            return $ul.children('li.catalog');
        },
        always: function()
        {
            $('#fileTree,#fileTree .is-sorting').removeClass('is-sorting');
        },
        finish: function(e)
        {
            visibleSort = false;
            e.target.siblings().find('a.sort-module').removeClass('dragging-shadow');

            if(!e.changed) return;

            var orders     = {};
            var link       = '';
            var module     = e.list.context;
            var moduleType = $(module).attr('data-type');
            $('#fileTree').find("li[data-type='" + moduleType + "'].catalog").each(function()
            {
                var $li = $(this);
                var item = $li.data();
                orders['orders[' + item.id + ']'] = $li.attr('data-order') || item.order;
            });

            var moduleName = moduleType == 'apiDoc' ? 'api' : 'doc';
            link = createLink(moduleName, 'sortCatalog');

            $.post(link, orders, function(data){}).error(function()
            {
                bootbox.alert(lang.timeout);
            });

        }
    });
})
</script>
