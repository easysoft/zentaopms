<?php
/**
 * The browse view file of score module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     score
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
    <div class='heading'><?php echo html::icon($lang->icons['score']);?> <?php echo $lang->score->record;?></div>
    <div class='actions'>
        <?php echo html::a(inLink('reset'), "<i class='icon-refresh'></i> " . $lang->score->reset, '', "class='btn'");?>
    </div>
</div>
<table class='table tablesorter'>
    <thead>
    <tr class='colhead'>
        <th><?php echo $lang->score->id;?></th>
        <th><?php echo $lang->score->account;?></th>
        <th><?php echo $lang->score->model;?></th>
        <th><?php echo $lang->score->method;?></th>
        <th><?php echo $lang->score->type;?></th>
        <th><?php echo $lang->score->score;?></th>
        <th><?php echo $lang->score->time;?></th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($scores))foreach($scores as $score):?>
        <tr class='text-center'>

        </tr>
    <?php endforeach;?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan='7'>
            <?php $pager->show();?>
        </td>
    </tr>
    </tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>