<?php
/**
 * The createByg view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='main' id='resultsContainer'>
</div>
</form>
<script>
$(function()
{
    $('#resultsContainer').load("<?php echo $this->createLink('testtask', 'results', "runID={$runID}&caseID=$caseID&version=$version");?> #casesResults", function()
    {
        $('.result-item').click(function()
        {
            var $this = $(this);
            $this.toggleClass('show-detail');
            var show = $this.hasClass('show-detail');
            $this.next('.result-detail').toggleClass('hide', !show);
            $this.find('.collapse-handle').toggleClass('icon-chevron-down', !show).toggleClass('icon-chevron-up', show);;
        });

        $(".step-group input[type='checkbox']").click(function()
        {
            var $next  = $(this).closest('tr').next();
            while($next.length && $next.hasClass('step-item'))
            {
                var isChecked = $(this).prop('checked');
                $next.find("input[type='checkbox']").prop('checked', isChecked);
                $next = $next.next();
            }
        });

        $('#casesResults table caption .result-tip').html($('#resultTip').html());

        $('tr').remove('#result-success');
        $('tr:first').addClass("show-detail");
        $('#tr-detail_1').removeClass("hide");
    });
});
<?php
$sessionString  = $config->requestType == 'PATH_INFO' ? '?' : '&';
$sessionString .= session_name() . '=' . session_id();
?>
var sessionString = '<?php echo $sessionString;?>';
</script>
<?php include '../../common/view/footer.lite.html.php';?>
