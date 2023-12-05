<?php
/**
 * The create view file of article module of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     article
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2 class="importData"><i class='icon-import'></i> <?php echo $lang->convert->jira->importJira;?></h2>
  </div>
  <div>
    <div class='form-group'>
      <ul id='resultBox'>
        <li class='text-red hidden'><strong><?php echo $lang->convert->jira->importing;?></strong></li>
      </ul>
    </div>
    <div class='from-group'><?php echo html::a(inlink('importJira', "method=$method&type=user&lastID=0&createTable=true"), $lang->convert->jira->start, '', "class='btn btn-primary' id='execButton'");?></div>
  </div>
</div>
<script>
$(document).ready(function()
{
    $('#execButton').click(function()
    {
        $('#execButton').hide();
        $('#resultBox .text-red').removeClass('hidden');
        $('.importData').html('<?php echo $lang->convert->jira->importingAB;?>');

        $.getJSON($(this).attr('href'), function(response)
        {
            if(response.result == 'finished')
            {
                $('#resultBox').append("<li class='text-success'>" + response.message + "</li>");
                $('#resultBox li.text-red').hide();
                $('.importData').html('<?php echo $lang->convert->jira->imported;?>');
                return false;
            }
            else
            {
                className  = response.type + 'count';
                $typeCount = $('#resultBox .' + className)
                if($typeCount.length == 0)
                {
                    $('#resultBox').append("<li class='text-success'>" + response.message + "</li>");
                }
                else
                {
                    count = parseInt($typeCount.html()) + parseInt(response.count);
                    $typeCount.html(count);
                }

                $('#execButton').attr('href', response.next);
                return $('#execButton').click();
            }
        });
        return false;
    });
})
</script>
