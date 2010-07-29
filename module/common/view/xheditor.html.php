<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<style>
.saveTemplate {
    background: transparent url(theme/default/images/xheditor/save.gif) no-repeat;
    background-position: center;
}
</style>
<script src='<?php echo $jsRoot;?>jquery/xheditor/xheditor-<?php echo $app->getClientLang();?>.min.js' type='text/javascript'></script>
<script src='<?php echo $jsRoot;?>jquery/xheditor/xheditor_plugins/ubb.min.js' type='text/javascript'></script>
<script language='javascript'>
var smiles = <?php echo json_encode($lang->smilies);?>;
var save   = '<?php echo $lang->save;?>';
var setTemplateTitle = '<?php echo $lang->bug->setTemplateTitle;?>';
var simpleTools = "Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,Removeformat,Separator,Align,List,Outdent,Indent,Separator,Link,Img,Emot,Source,Fullscreen,About";
var tools;

$(function() {
    var allPlugin={
        Save:{
            c:'saveTemplate',
            t:save,
            e:function(){
                content = $("#steps").val();
                jPrompt(setTemplateTitle, '','', function(r)
                {
                    if(!r || !content) return;
                    saveTemplateLink = createLink('bug', 'saveTemplate');
                    steps = $("#steps").val();
                    $.post(saveTemplateLink, {title:r, content:content}, function(data)
                    {
                        $('#tplBox').html(data);
                    });
                });
        }}
    };
    
    $('.xhe').xheditor({
            plugins:allPlugin,
            tools:tools,
            skin:'default', 
            width:'100%', 
            forcePtag:false,
            beforeSetSource:ubb2html,
            beforeGetSource:html2ubb,
            emots:{
                'default':{
                        name:'default',
                            width:22,height:22,line:8, 
                            list:smiles}}});

$('.saveTemplate').css({width:'58px'});
$('.saveTemplate').parent().css({width:'58px'});
});
</script>
