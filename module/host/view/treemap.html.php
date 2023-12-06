<?php
/**
 * The manage view file of host module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     host
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php css::import($themeRoot . 'zui/treemap/min.css');?>
<?php js::import($jsRoot . 'zui/treemap/min.js');?>
<style>#hostsTreemap{overflow:auto;}</style>
<?php js::set('browseType', $type);?>
<div id='mainMenu' class='clearfix'>
  <div class='pull-left btn-toolbar'>
    <?php foreach($lang->host->featureBar['browse'] as $typeKey => $name):?>
    <?php echo html::a(inlink($typeKey == 'all' ? 'browse' : 'treemap', $typeKey == 'all' ? '' : "typeKey=$typeKey"), "<span class='text'>{$name}</span>", '', "class='btn btn-link' id='{$typeKey}Tab'")?>
    <?php endforeach;?>
    <a href='#' class='btn btn-link querybox-toggle' id='bysearchTab'><i class='icon-search icon'></i> <?php echo $lang->host->byQuery;?></a>
  </div>
</div>
<div id='queryBox' class='cell' data-module='host'></div>
<div id='mainContent' class='main-content'>
  <div id='hostsTreemap'>
    <?php echo $treemap;?>
  </div>
</div>
<script>
$(function()
{
    $('#<?php echo $type?>Tab').addClass('btn-active-text');

    /* Init treemap. */
    $('#hostsTreemap').treemap(
    {
        /* Set icon for node. */
        nodeTemplate: function(node, tree)
        {
            var $node = $('<div class="treemap-node"></div>');
            if(node.type) $node.addClass('treemap-node-' + node.type);
            if(node.hostid) $node.attr('data-hostid', node.hostid);
            $node.append('<a class="treemap-node-wrapper">' + node.text + '</a>');
            return $node;
        },
        onNodeClick: function(node)
        {
            if(!node.children)
            {
                var hostID = node.hostid;
                var url = createLink('host', 'view', "hostID=" + hostID, 'html', true);
                $.modalTrigger({width:1000, type:'iframe', url:url});
            }
        }
    });

    var maxHeight = $(window).height() - $('#header').height() - $('#footer').height() - $('#mainMenu').height() - 110;
    $('#hostsTreemap').height(maxHeight);
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
