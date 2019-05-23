<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="{{ asset('js/jquery-1.12.4.min.js') }}"></script>
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
<div class="container">
    <h1 class="form-signin-heading">欢迎来到个人中心</h1>
    <button class="qd btn btn-lg btn-primary btn-block" style="width: 100px">签到</button><br>
</div>>
</body>
</html>
<script>
    $('.qd').click(function(){
        $.ajax({
            url:'/license/qd',
            type:'get',
            dataType:'json',
            success:function (data) {
                if(data.code==0){
                    alert(data.msg);
                }else{
                    alert(data.msg);
                }
            }
        });
    });
</script>