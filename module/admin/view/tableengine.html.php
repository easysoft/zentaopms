<?php
/**
 * The tableEngine view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
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
<script>
/**
 * Change all table engines.
 *
 * @access public
 * @return void
 */
function changeAllEngines()
{
    var tables = [];
    var $engineBox = $('#engineBox');
    $engineBox.find('li').each(function()
    {
        tables.push($(this).attr('data-table'));
    });

    $('.btn.changeEngine').hide();
    $engineBox.empty();
    for(i in tables);
    $.each(tables, function(_, table)
    {
        changeEngine(table);
    });
    $engineBox.append("<div><a href='javascript:location.reload();' class='btn'>{$lang->refresh}</a>"?></div>");
}

/**
 * Change one engine.
 *
 * @param  string $table
 * @access public
 * @return void
 */
function changeEngine(table)
{
    var $engineBox = $('#engineBox');
    var link = createLink('admin', 'ajaxChangeTableEngine', 'table=' + table);

    if($engineBox.find('[data-table=' + table + ']').length == 0) $engineBox.append("<div data-table='" + table + "'>" + changingTable.replace('%s', table) + "</div>");
    $.ajax(
    {
        type: "GET",
        url: link,
        success: function(response)
        {
            response = JSON.parse(response);
            $engineBox.find('[data-table=' + table + ']').html(response.message).addClass('text-success');
        },
        error: function()
        {
            changeEngine(table);
        }
    })
}
</script>
<?php include '../../common/view/footer.html.php';?>
