<style>
#docTree {margin-bottom: 0}
#docTree > .tree-action-item {display: none!important}
#docTree li > .tree-item-wrapper > .tree-toggle {display: block}
#docTree li > .tree-item-wrapper:hover {background-color: #edf3fe; cursor: pointer; margin-left: -20px; padding-left: 20px;}
#docTree li {line-height: 30px; padding-top: 0; padding-bottom: 0}
#docTree > li.open > .tree-item-wrapper {border-bottom: none}
#docTree.tree li.has-list.open > ul:after {top: -10px; bottom: 17px; display: none}
#docTree.tree ul > li.has-list:after {top: 14px}
#docTree.tree ul > li:after {top: 13px}
#docTree.tree ul > li:before {display: block; content: ' '; border: none; border-left: 1px dotted #999; top: -11px; bottom: 12px; left: -11px; position: absolute; width: auto; height: auto}
#docTree.tree ul > li:last-child:before {bottom: auto; height: 25px}
#docTree.tree ul > li > .tree-item-wrapper:before {display: block; content: ' '; position: absolute; width: 7px; height: 7px; top: 10px; left: 8px; background-color: #ddd}
#docTree.tree ul > li.has-list > .tree-item-wrapper:before {display: none;}
#docTree.tree ul > li.item-type-doc > .tree-item-wrapper {padding-left: 15px; margin-left: -15px;}
#docTree.tree ul > li.item-type-doc > .tree-item-wrapper:hover {background-color: #edf3fe}
#docTree.tree ul > li.item-type-doc > .tree-item-wrapper:before {left: 0; z-index: 2}
#docTree.tree li>.list-toggle {top: 2px}
#docTree.tree li:before {display: none}
#docTree .doc-info {display: none;}
#docTree .doc-info > div {display: inline-block; margin: 0 5px;} 
#docTree .doc-info > div.buttons {position: relative; top: 2px}
#docTree .item-type-doc:hover .doc-info {display: inline-block; margin-left:10px;}
</style>
<div id='featurebar'>
  <ul class='nav'><li><?php echo $lang->doc->browseTypeList['tree']?></li></ul>
  <div class='actions'>
    <div class="btn-group">
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class='icon icon-tags'></i> <?php echo $lang->doc->browseTypeList['tree']?> <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <li><?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-list'></i> {$lang->doc->browseTypeList['list']}");?></li>
        <li><?php echo html::a('javascript:setBrowseType("bymenu")', "<i class='icon icon-th'></i> {$lang->doc->browseTypeList['menu']}");?></li>
        <li><?php echo html::a('javascript:setBrowseType("bytree")', "<i class='icon icon-tags'></i> {$lang->doc->browseTypeList['tree']}");?></li>
      </ul>
    </div>
    <?php common::printIcon('doc', 'create', "libID=$libID&moduleID=$moduleID&product=0&prject=0&from=doc");?>
  </div>
</div>
<div class='main'>
  <div class='panel'>
    <div class='panel-heading'>
      <i class='icon icon-folder-close-alt'></i> <strong><?php echo $libName;?></strong>
	  <div class='panel-actions pull-right'>
        <?php common::printLink('doc', 'editLib', "rootID=$libID", $lang->doc->editLib, '', "class='btn btn-sm' data-toggle='modal' data-type='iframe' data-width='600px'");?>
        <?php common::printLink('doc', 'deleteLib', "rootID=$libID", $lang->doc->deleteLib, 'hiddenwin', "class='btn btn-sm'");?>
        <?php common::printLink('tree', 'browse', "rootID=$libID&view=doc", $lang->doc->manageType, '', "class='btn btn-sm'");?>
        <?php echo html::a(inlink('ajaxFixedMenu', "libID=$libID&type=" . ($fixedMenu ? 'remove' : 'fixed')), $fixedMenu ? $lang->doc->removeMenu : $lang->doc->fixedMenu, "hiddenwin", "class='btn btn-sm'");?>
	  </div>
    </div>
    <div class='panel-body'>
      <ul id='docTree' class='tree-lines'></ul>
    </div>
  </div>
</div>

<script>
var libID = <?php echo $libID?>;
$(function()
{
    var data = $.parseJSON('<?php echo helper::jsonEncode4Parse($tree, JSON_HEX_QUOT | JSON_HEX_APOS);?>');
    var $tree = $('#docTree');
    $tree.tree(
    {
        name: 'docTree',
        initialState: 'preserve',
        data: data,
        itemWrapper: true,
        actions:
        {
            add:
            {
                title: '<?php echo $lang->doc->create ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-sitemap"></i>',
                templateInList: false,
                linkTemplate: '<?php echo helper::createLink('tree', 'edit', "moduleID={0}&type=doc"); ?>'
            },
        },
        itemCreator: function($li, item)
        {
            $li.toggleClass('tree-toggle', item.type !== 'doc').closest('li').addClass('item-type-' + item.type);
            if(item.type === 'doc')
            {
                $li.append('<span class="text-muted">#' + item.id + ' </span>').append($('<a>').attr({href: item.url}).text(item.title));
                var $info = $('<div class="doc-info clearfix"/>');
                $info.append($('<div class="buttons"/>').html(item.buttons));
                $li.append($info);
            }
            else
            {
                $li.append($('<span class="tree-toggle"><i class="icon icon-bookmark-empty text-muted"></i> ' + (item.title || item.name) + '</span>'));
            }
        }
    });

    $('[data-toggle="tooltip"]').tooltip({container: 'body'});

    var tree = $tree.data('zui.tree');

    // Expand all nodes when user visit at first time of this day.
    if(!tree.store.time || tree.store.time < (new Date().getTime() - 24 * 40 * 60 * 1000))
    {
        tree.show($('.item-type-doc').parent().parent());
    }
});
</script>
