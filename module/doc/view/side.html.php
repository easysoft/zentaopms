<?php
$sideLibs = array();
foreach($lang->doclib->tabList as $libType => $typeName) $sideLibs[$libType] = $this->doc->getLimitLibs($libType);
$allModules = $this->loadModel('tree')->getDocStructure();

$sideSubLibs = array();
$sideSubLibs['product']   = $this->doc->getSubLibGroups('product', array_keys($sideLibs['product']));
$sideSubLibs['execution'] = $this->doc->getSubLibGroups('execution', array_keys($sideLibs['execution']));
if($this->methodName != 'browse')
{
    $browseType = '';
    $moduleID   = '';
}
if(empty($type)) $type = 'product';
$sideWidth = common::checkNotCN() ? '270' : '238';
?>
<div class="side-col" style="width:<?php echo $sideWidth;?>px" data-min-width="<?php echo $sideWidth;?>">
    <div class="cell" id="<?php echo $type;?>">
      <div id='title'>
        <li class='menu-title'><?php echo $this->lang->doc->menuTitle;?></li>
        <?php
        $canEditLib    = common::hasPriv('doc', 'editLib');
        $canManageBook = common::hasPriv('doc', 'manageBook');
        $canManageMenu = common::hasPriv('tree', 'browse');
        $canEditLib    = common::hasPriv('doc', 'editLib');
        $canDeleteLib  = common::hasPriv('doc', 'deleteLib');
        if($type != 'book' and ($canManageMenu or $canEditLib or $canDeleteLib))
        {
            echo "<div class='menu-actions'>";
            echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");
            echo "<ul class='dropdown-menu dropdown-menu-doc pull-left'>";
            if($canManageMenu)
            {
                echo '<li>' . html::a($this->createLink('tree', 'browse', "rootID=$libID&view=doc", '', true), '<i class="icon-cog-outline"></i> ' . $this->lang->doc->manageType, '', "class='iframe'") . '</li>';
                echo "<li class='divider'></li>";
            }
            if($canEditLib) echo '<li>' . html::a($this->createLink('doc', 'editLib', "rootID=$libID"), '<i class="icon-edit"></i> ' . $lang->doc->editLib, '', "class='iframe'") . '</li>';
            if($canDeleteLib) echo '<li>' . html::a($this->createLink('doc', 'deleteLib', "rootID=$libID"), '<i class="icon-trash"></i> ' . $lang->doc->deleteLib, 'hiddenwin') . '</li>';
            echo '</ul></div>';
        }

        if($type == 'book' and ($canEditLib or $canManageBook))
        {
            echo "<div class='menu-actions'>";
            echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");
            echo "<ul class='dropdown-menu dropdown-menu-book pull-left'>";
            if($canEditLib) echo '<li>' . html::a($this->createLink('doc', 'editLib', "rootID=$libID"), '<i class="icon-edit"></i> ' . $lang->doc->editBook, '', "class='iframe'") . '</li>';
            if($canManageBook) echo '<li>' . html::a($this->createLink('doc', 'manageBook', "bookID=$libID"), '<i class="icon-cog-outline"></i> ' . $lang->doc->manageBook) . '</li>';
            echo '</ul></div>';
        }
        ?>
      </div>
      <?php if(!$moduleTree):?>
      <hr class="space">
      <div class="text-center text-muted tips">
        <?php echo $type == 'book' ? $lang->doc->noChapter : $lang->doc->noModule;?>
      </div>
      <hr class="space">
      <?php endif;?>
      <?php if($type == 'book'):?>
      <?php include './bookside.html.php';?>
      <?php else:?>
      <?php echo $moduleTree;?>
      <?php endif;?>
    </div>
<script>
$(function()
{
    if($.cookie('docSideType'))
    {
        var type = $.cookie('docSideType');
        var $tabs = $('#mainRow .side-col .tabs');
        if($tabs.find('.tab-content .tab-pane#' + type).length >0)
        {
            $tabs.find('.nav-tabs li').removeClass('active');
            $tabs.find('.nav-tabs li a[href="#' + type + '"]').parent().addClass('active');
            $tabs.find('.tab-content .tab-pane').removeClass('active');
            $tabs.find('.tab-content .tab-pane#' + type).addClass('active');
        }
        $.cookie('docSideType', '');
        $('#mainRow .side-col .side-footer #orderLib').toggleClass('hidden', (type == 'product' || type == 'execution'));
    }

    $('#mainRow .side-col .tabs .nav-tabs li a').click(function()
    {
        var href     = $(this).attr('href');
        var canOrder = !(href.indexOf('product') > 0 || href.indexOf('execution') > 0);
        if(!canOrder)
        {
            $(this).closest('.side-col').find('.side-footer #orderLib').addClass('hidden');
            $(this).closest('.side-col').find('.side-footer #saveOrder').addClass('hidden');
        }

        var $orderLib  = $(this).closest('.side-col').find('.side-footer #orderLib');
        var $saveOrder = $(this).closest('.side-col').find('.side-footer #saveOrder');

        var execute = false;
        $(this).on('shown.zui.tab', function()
        {
            if(!execute)
            {
                var $tabPane   = $('#mainRow .side-col .tabs .tab-content .tab-pane.active');
                if($tabPane.find('.libs-group.sort').length > 0 && canOrder)
                {
                    $orderLib.addClass('hidden');
                    $saveOrder.removeClass('hidden');
                    execute = true;
                }
                if($tabPane.find('.libs-group.sort').length == 0 && canOrder)
                {
                    $orderLib.removeClass('hidden');
                    $saveOrder.addClass('hidden');
                    execute = true;
                }
            }
        });
    });

    $('#orderLib').click(function()
    {
        var $tabPane = $('#mainRow .side-col .tabs .tab-content .tab-pane.active');
        var type     = $tabPane.attr('id');
        $.get(createLink('doc', 'sort', "type=" + type), function(data)
        {
            $tabPane.html(data);
            $tabPane.closest('.side-col').find('.side-footer #orderBox #orderLib').addClass('hidden');
            $tabPane.closest('.side-col').find('.side-footer #orderBox #saveOrder').removeClass('hidden');
            $tabPane.find('.libs-group.sort').sortable(
            {
                trigger:  '.lib',
                selector: '.lib',
            });
        });
    });
});

function saveOrder()
{
    var $tabPane  = $('#mainRow .side-col .tabs .tab-content .tab-pane.active');
    var type      = $tabPane.attr('id');
    var orders    = {};
    var orderNext = 1;

    $tabPane.find('.libs-group.sort .lib').not('.files').not('.addbtn').each(function()
    {
        orders[$(this).data('id')] = orderNext ++;
    });

    $.post(createLink('doc', 'sort'), orders, function(data)
    {
        if(data.result == 'success')
        {
            $.cookie('docSideType', type);
            return location.reload();
        }
        else
        {
            bootbox.alert(data.message);
        }
    }, 'json');
}
</script>
</div>
