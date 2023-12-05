<?php
/**
 * The createByg view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('tab', $app->tab);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->testcase->createBug;?></h2>
  </div>
  <div class='main' id='resultsContainer'></div>
</div>
<script>
function createBug(obj)
{
    var $form  = $(obj).closest('form');
    var params = $form.data('params');
    var stepIdList = '';
    $form.find('.step .step-id :checkbox').each(function()
    {
        if($(this).prop('checked')) stepIdList += $(this).val() + '_';
    });

    var onlybody    = config.onlybody;
    config.onlybody = 'no';
    var link        = createLink('bug', 'create', params + ',stepIdList=' + stepIdList);
    if(tab == 'my')
    {
        window.parent.$.apps.open(link, 'qa');
    }
    else
    {
        window.open(link, '_blank');
    }
    config.onlybody = onlybody;
}

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

    $('#resultsContainer').click(function(event)
    {
        if(event.target.id.indexOf('checkAll') !== -1)
        {
            var checkAll  = document.getElementById(event.target.id);
            var checkAll  = $(checkAll);
            var isChecked = checkAll.prop('checked');

            checkAll.closest('tbody').children('tr').find('input[type=checkbox]').prop('checked', isChecked);
        }
    });
});
var sessionString = '<?php echo session_name() . '=' . session_id();?>';
</script>
<?php include '../../common/view/footer.lite.html.php';?>
