<?php
/**
 * The tableEngine view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <?php
    $MyISAMCount    = 0;
    $engineListHtml = '';
    foreach($tableEngines as $tableName => $engine)
    {
        if($engine != 'InnoDB') $MyISAMCount ++;
        $engineListHtml .= "<li data-table='$tableName'>" . sprintf($lang->admin->engineInfo, $tableName, $engine) . "</li>\n";
    }
    ?>
    <h2>
    <?php if($MyISAMCount > 0):?>
    <?php printf($lang->admin->engineSummary['hasMyISAM'], $MyISAMCount);?>
    <?php echo html::a('###', $lang->admin->changeEngine, '', "class='btn btn-sm changeEngine btn-primary' onclick='changeAllEngines()'");?>
    <?php else:?>
    <?php print($lang->admin->engineSummary['allInnoDB']);?>
    <?php endif;?>
    </h2>
  </div>
  <div>
    <ul id='engineBox'><?php echo $engineListHtml?></ul>
  </div>
</div>
<?php js::set('changingTable', $lang->admin->changingTable);?>
<?php js::set('hasMyISAM', $lang->admin->engineSummary['hasMyISAM']);?>
<script>
var changeFails = 0;
/**
 * Change all table engines.
 *
 * @access public
 * @return void
 */
function changeAllEngines()
{
    var $engineBox = $('#engineBox');
    $('.btn.changeEngine').hide();
    $engineBox.empty();

    changeTableEngine();
}

/**
 * Ajax change table engine.
 *
 * @access public
 * @return void
 */
function changeTableEngine()
{
    var $engineBox = $('#engineBox');
    var link       = createLink('admin', 'ajaxChangeTableEngine');
    $.ajax(
    {
        type: "GET",
        url: link,
        success: function(response)
        {
            response = JSON.parse(response);
            if(response == null || response.result == 'finished')
            {
                $engineBox.append("<div class='text-success'><?php echo $lang->admin->changeFinished?></div>");
                $engineBox.append("<div class='btn btn-sm'><a href='javascript:location.reload()'><?php echo $lang->refresh;?></a></div>");
                if(changeFails == 0) $('#mainContent .main-header h2').html("<?php echo $lang->admin->engineSummary['allInnoDB']?>");
                if(changeFails != 0) $('#mainContent .main-header h2').html(hasMyISAM.replace('%s', changeFails));
            }
            else
            {
                table = response.thisTable;
                if($engineBox.find('[data-table=' + table + ']').length == 0) $engineBox.append("<li data-table='" + table + "'>" + changingTable.replace('%s', table) + "</li>");
                $engineBox.find('[data-table=' + table + ']').html(response.message);
                if(response.result == 'success') $engineBox.find('[data-table=' + table + ']').addClass('text-success');
                if(response.result == 'fail') $engineBox.find('[data-table=' + table + ']').addClass('text-warning');

                nextTable = response.nextTable;
                if(nextTable && $engineBox.find('[data-table=' + nextTable + ']').length == 0) $engineBox.append("<li data-table='" + nextTable + "'>" + changingTable.replace('%s', nextTable) + "</li>");

                if(response.result == 'fail') changeFails += 1;
                $(document).scrollTop($(document).height());

                changeTableEngine();
            }
        },
        error: function()
        {
            changeTableEngine();
        }
    })
}
</script>
<?php include '../../common/view/footer.html.php';?>
