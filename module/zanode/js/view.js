var ifm = document.getElementById("vncIframe");
ifm.height=$('.vnc-detail').css('height')
$('.vnc-mask').click(function(){
    window.open(vncLink)
})
$('.vnc-mask').css('width', $('.vnc-detail').css('width'));
$('.vnc-mask').css('height', $('.vnc-detail').css('height'));