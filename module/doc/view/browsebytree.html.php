<style>
#treeActions {margin-top: -8px}
#treeActions .btn {color: #114f8e; display: block; float: right; padding: 5px 7px;}
#treeActions .btn:hover,
#treeActions .btn:focus,
#treeActions .btn:active {color: #2e6dad; background-color: #ddd;}
#docTree {margin-bottom: 0}
#docTree > .tree-action-item {display: none!important}
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
#docTree.tree ul > li.item-type-doc > .tree-item-wrapper {padding-left: 20px; margin-left: -25px;}
#docTree.tree ul > li.item-type-doc > .tree-item-wrapper:hover {background-color: #edf3fe}
#docTree.tree ul > li.item-type-doc > .tree-item-wrapper:before {left: 0; z-index: 2}
#docTree.tree li>.list-toggle {top: 2px}
#docTree.tree li:before {display: none}
#docTree .doc-info {display: none;}
#docTree .doc-info > div {display: inline-block; margin: 0 5px;}
#docTree .doc-info > div.buttons {position: relative; top: 2px}
#docTree .item-type-doc:hover .doc-info {display: inline-block; margin-left:10px;}
#docTree .icon-dir {position: relative; top: 1px;}
#docTree li.open .icon-dir:before {content: '\e6f0'}
#docTree.tree ul > li.item-type-doc > .tree-item-wrapper .icon-file {position: relative; left: -3px; display: inline-block; color: #2e6dad}
#docTree.tree ul > li.item-type-doc > .tree-item-wrapper > a {cursor: pointer;}
</style>
<div id='featurebar'>
  <ul class='nav'>
    <li><a href='javascript:history.go(-1);'><?php echo $lang->goback?></a></li>
  </ul>
  <div class='actions'>
    <div class="btn-group">
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class='icon icon-branch'></i> <?php echo $lang->doc->browseTypeList['tree']?> <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <li><?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-list'></i> {$lang->doc->browseTypeList['list']}");?></li>
        <li><?php echo html::a('javascript:setBrowseType("bymenu")', "<i class='icon icon-th'></i> {$lang->doc->browseTypeList['menu']}");?></li>
        <li><?php echo html::a('javascript:setBrowseType("bytree")', "<i class='icon icon-branch'></i> {$lang->doc->browseTypeList['tree']}");?></li>
      </ul>
    </div>
    <div class="btn-group">
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class='icon icon-cog'></i> <?php echo $lang->actions?> <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <?php
        if(common::hasPriv('doc', 'editLib')) echo '<li>' . html::a(inlink('editLib', "rootID=$libID"), $lang->doc->editLib, '', "data-toggle='modal' data-type='iframe' data-width='800px'") . '</li>';
        if(common::hasPriv('doc', 'deleteLib')) echo '<li>' . html::a(inlink('deleteLib', "rootID=$libID"), $lang->doc->deleteLib, 'hiddenwin') . '</li>';
        ?>
        <?php if(common::hasPriv('tree', 'browse')) echo '<li>' . html::a($this->createLink('tree', 'browse', "rootID=$libID&view=doc"), $lang->doc->manageType) . '</li>';?>
        <li><?php echo html::a(inlink('ajaxFixedMenu', "libID=$libID&type=" . ($fixedMenu ? 'remove' : 'fixed')), $fixedMenu ? $lang->doc->removeMenu : $lang->doc->fixedMenu, "hiddenwin");?></li>
      </ul>
    </div>
    <?php common::printIcon('doc', 'create', "libID=$libID&moduleID=$moduleID");?>
  </div>
</div>
<div class='main'>
  <div class='panel'>
    <div class='panel-body'>
      <?php if($tree):?>
      <ul id='docTree' class='tree-lines'></ul>
      <?php else:;?>
      <?php echo $lang->pager->noRecord?>
      <?php if(common::hasPriv('doc', 'create')) echo html::a(inlink('create', "libID=$libID&moduleID=$moduleID"), $lang->doc->create)?>
      <?php endif;?>
    </div>
  </div>
</div>

<script>
var libID = <?php echo $libID?>;
$(function()
{
    var data = $.parseJSON('<?php echo helper::jsonEncode4Parse($tree, JSON_HEX_QUOT | JSON_HEX_APOS);?>');
    <?php if($tree):?>
    var $tree = $('#docTree');
    $tree.tree(
    {
        name: 'docTree',
        initialState: 'preserve',
        data: data,
        itemWrapper: true,
        itemCreator: function($li, item)
        {
            $li.toggleClass('tree-toggle', item.type !== 'doc').closest('li').addClass('item-type-' + item.type);
            if(item.type === 'doc')
            {
                var $info = $('<div class="doc-info clearfix"/>');
                $info.append($('<div class="buttons"/>').html(item.buttons));
                $li.find('.tree-item-wrapper').append('<span><i class="text-muted icon icon-file"></i> </span>').append($('<a>').attr({href: item.url}).text(item.title)).append($info);
            }
            else
            {
                $li.find('.tree-item-wrapper').addClass('tree-toggle').append($('<div><i class="icon icon-dir icon-folder-close-alt text-muted"></i> ' + (item.title || item.name) + '</div>'));
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
    <?php endif;?>
});
</script>
