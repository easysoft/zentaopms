<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false): ?>
<style>
body {scrollbar-gutter: stable both-edges;}
::-webkit-scrollbar-thumb {border-radius: 5px;}

#navbar {display: flex; align-items: center; justify-content: center;}
#navbar .nav {display: flex; align-items: center;}
#navbar .nav > li {float: none;}
#navbar .nav > li > a {padding-top: 3px!important; padding-bottom: 3px!important; border-radius: 4px;}
</style>
<script>
$(function()
{
    if(window.parent === window || window.parent.appHeaderStyleUpdated) return;
    const mainHeader = document.getElementById('mainHeader');
    if(!mainHeader) return;
    const style = window.getComputedStyle(mainHeader, null);
    const color = window.getComputedStyle(document.querySelector('#navbar .nav>li:not(.active)>a'), null).color;
    const clientHeaderStyle = {windowControlBtnColor: color, background: style.background, color: color};
    window.parent.appHeaderStyleUpdated = true;
    window.open('xxc://setAppHeaderStyle/zentao-integrated/' + encodeURIComponent(JSON.stringify(clientHeaderStyle)), '_blank');
});
</script>
<?php endif; ?>
