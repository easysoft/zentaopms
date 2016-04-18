<?php
/**
 * The task view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: task.html.php 4894 2013-06-25 01:28:39Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include './taskheader.html.php';
js::set('moduleID', $moduleID);
js::set('productID', $productID);
?>

<div class='main'>
  <div class='panel'>
    <div class='panel-heading'>
      <i class="icon icon-folder-close-alt"></i> <strong><?php echo $project->name ?></strong>
    </div>
    <div class='panel-body no-padding'>
      <ul id='projectTree'></ul>
    </div>
  </div>
</div>

<?php js::set('replaceID', 'taskList')?>
<script
var projectID = <?php echo $projectID?>;>
$('#project<?php echo $projectID;?>').addClass('active')
$('#treeTab').addClass('active');

$(function()
{
    var data = $.parseJSON('<?php echo json_encode($tree);?>');
    console.log('DATA', data);
    var $tree = $('#projectTree');
    $tree.tree(
    {
        initialState: 'preserve',
        data: data,
        itemWrapper: true,
        actions:
        {
            add:
            {
                title: '<?php echo $lang->project->batchWBS ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-sitemap"></i>',
                templateInList: '<a href="javascript:;"><i class="icon icon-sitemap"></i> <?php echo $lang->project->batchWBS ?></a>'
            },
        },
        itemCreator: function($li, item)
        {
            $li.closest('li').addClass('item-type-' + item.type);
            if(item.type === 'product')
            {
                $li.append($('<span class="tree-toggle"><i class="icon icon-cube text-muted"></i> ' + item.title + '</span>'));
            }
            else if(item.type === 'story')
            {
                $li.append('<span class="tree-toggle"><i class="icon icon-lightbulb text-muted"></i> </span>').append($('<a>').attr({href: item.url}).text('#' + item.storyId + ' ' + item.title).css('color', item.color));
            }
            else if(item.type === 'task')
            {
                $li.append('<span class="pri' + item.pri + '">' + item.pri + '</span> ').append($('<a>').attr({href: item.url}).text('#' + item.id + ' ' + item.title).css('color', item.color));
            }
            else
            {
                $li.append($('<span class="tree-toggle"><i class="icon icon-bookmark-empty text-muted"></i> ' + (item.title || item.name) + '</span>'));
            }
        }
    });

    $tree.find('[data-toggle="tooltip"]').tooltip();
});
</script>
<?php include '../../common/view/footer.html.php';?>
