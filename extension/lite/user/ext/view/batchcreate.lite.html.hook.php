<?php if(!empty($properties['user'])):?>
<?php
$userMaxCount = $properties['user']['value'];
$userCount    = $this->dao->select("COUNT('*') as count")->from(TABLE_USER)
    ->where('deleted')->eq(0)
    ->beginIF($this->config->edition != 'open')->andWhere("visions")->ne('lite')->fi()
    ->fetch('count');
js::set('userCount', $userCount);
js::set('userMaxCount', $userMaxCount);
js::set('noticeUserCreate', str_replace('%maxcount%', $userMaxCount, $lang->user->noticeUserCreate));

if($this->config->edition != 'open')
{
    $liteCount    = $this->dao->select("COUNT('*') as count")->from(TABLE_USER)->where('deleted')->eq(0)->andWhere("visions")->eq('lite')->fetch('count');
    $liteMaxCount = $properties['lite']['value'];
    js::set('liteCount', $liteCount);
    js::set('liteMaxCount', $liteMaxCount);
    js::set('noticeFeedbackCreate', str_replace('%maxcount%', $liteMaxCount, $lang->user->noticeFeedbackCreate));
}
?>
<script>
$(function()
{
    $('#submit').click(function()
    {
        var allUserCount = parseInt(userCount);
        var allLiteCount = parseInt(liteCount);
        var lastVision; 
        
        $('[id^="account"]').each(function()
        {
            if($(this).val())
            {
                var i       = parseInt($(this).attr('id').replace(/[^0-9]/ig, ''));
                var visions = $('select[id="visions' + i + '"]').val();
                
                if($.inArray("ditto", visions) == '-1') lastVision = visions;

                visions = lastVision;
                
                if(visions == undefined) visions = ['lite'];               
                
                var index   = $.inArray('lite', visions);
              
                if(index != '-1' && visions.length == '1')
                {
                    allLiteCount += 1;
                }
                else
                {
                    allUserCount += 1;
                }
            }
        });

        if(allUserCount > userMaxCount)
        {
            alert(noticeUserCreate.replace('%usercount%', allUserCount));
            return false;
        }
    
        if(allLiteCount > liteMaxCount)
        {
            alert(noticeFeedbackCreate.replace('%usercount%', allLiteCount));
            return false;
        }
    })
})
</script>
<?php endif;?>
