<?php
/**
 * The assigntome block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
#assigntomeBlock .nav > li:not(.active) > a {padding: 8px 12px; color: #838A9D; border-color: #eee;}
#assigntomeBlock .nav > li:not(.open) > a {color: #838A9D;}
</style>
<div id='assigntomeBlock'>
  <ul class="nav nav-secondary">
    <?php $isFirstTab = true;?>
    <?php $printMore  = false;?>
    <?php $i          = 0;?>
    <?php $maxItem    = common::checkNotCN() ? 6 : 8;?>
    <?php foreach($hasViewPriv as $type => $bool):?>
    <?php if($i <= $maxItem):?>
    <li<?php if($isFirstTab) {echo ' class="active"';}?>>
        <a data-tab href='#assigntomeTab-<?php echo $type;?>' onClick="changeLabel('<?php echo $type;?>')">
        <?php echo $type == 'review' ? $lang->my->audit : $lang->block->availableBlocks->$type;?>
        <span class='label label-light label-badge label-assignto <?php echo $type . "-count "; echo $isFirstTab ? '' : 'hidden'; $isFirstTab = false ?>'><?php echo $count[$type];?></span>
      </a>
    </li>
    <?php else:?>
    <?php if(!$printMore):?>
    <?php $printMore = true;?>
    <li>
      <a class="dropdown-toggle moreBtn" data-toggle="dropdown" onClick="changeLabel('more')"><?php echo $lang->more;?><span class="caret"></span></a>
        <ul class="dropdown-menu">
    <?php endif;?>
          <li<?php if($isFirstTab) {echo ' class="active"';}?>>
            <a data-tab href='#assigntomeTab-<?php echo $type;?>' class='<?php echo "$type"?>' onClick="changeMoreBtn('<?php echo $type;?>', this);">
              <?php echo $type == 'review' ? $lang->my->audit : $lang->block->availableBlocks->$type;?>
              <span class='label label-light label-badge label-assignto <?php echo $type . "-count "; echo $isFirstTab ? '' : 'hidden'; $isFirstTab = false ?>'><?php echo $count[$type];?></span>
            </a>
          </li>
    <?php endif;?>
    <?php $i ++;?>
    <?php endforeach;?>
    <?php if($printMore) echo '</ul></li>';?>
  </ul>
  <div class="tab-content">
    <?php $isFirstTab = true; ?>
    <?php foreach($hasViewPriv as $type => $bool):?>
    <div class="tab-pane<?php if($isFirstTab) {echo ' active'; $isFirstTab = false;}?>" id="assigntomeTab-<?php echo $type?>">
      <?php include "{$type}block.html.php";?>
    </div>
    <?php endforeach;?>
  </div>
</div>
<style>
#assigntomeBlock {position: relative;}
#assigntomeBlock .block-todoes {padding-top: 10px}
#assigntomeBlock > .nav {position: absolute; top: -41px; left: 120px;}
#assigntomeBlock .block-todoes .todoes-form{top: -50px;}
</style>
<script>
/**
 * Change label.
 *
 * @param  string type
 * @access public
 * @return void
 */
function changeLabel(type)
{
    var $moreBtn  = $('#assigntomeBlock .moreBtn');
    if($moreBtn.length > 0 && type != 'more')
    {
        $moreBtn.html("<?php echo $lang->more;?>" + "<span class='caret'></span>");
    }

    if(type != 'more')
    {
        $('.label-assignto').addClass('hidden');
        $('.' + type + '-count').removeClass('hidden');
    }
}

/**
 * Change more button.
 *
 * @param string type
 * @param object label
 * @access public
 * @return void
 */
function changeMoreBtn(type, label)
{
    $('.label-assignto').addClass('hidden');
    $('.' + type + '-count').removeClass('hidden');

    var $moreBtn = $('#assigntomeBlock .moreBtn');
    var text     = $(label).html();

    text += '<span class="caret"></span>';
    $moreBtn.html(text);

    setTimeout(function()
    {
        $moreBtn.parent().addClass('active');
    }, 100);
}

$('#assigntomeBlock').closest('.panel').attr('data-fixed', 'main');
</script>
