<!DOCTYPE html>
<html>
<head>
    <title>登录</title>
    <script src="js/qrcode.min.js"></script>
    <script src="js/jquery-1.12.4.min.js"></script>
</head>
<body>
<div id="qrcode"></div>
</body>
</html>
<script>
    var qrcode = new QRCode('qrcode', {
        text: "{{$token_lists}}",
        width: 256,
        height: 256,
        colorDark : '#000000',
        colorLight : '#ffffff',
        correctLevel : QRCode.CorrectLevel.H
    });

    var token = "{{$token_lists}}";
    $(document).ready(function(){
        num = setInterval(function(){
            $.ajax({
                type:'post',
                url:'logins',
                data:{'token':token},
                success:function(msg){
                    if(msg==1){
                        window.location.href="http://47.107.93.29:8080/index";
                    }
                }
            });
        }, 4000);
    });
</script>