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
<form class="form-signin">
    <h2 class="form-signin-heading">Please sign in</h2>
    <table>
        <tr>
            <td class="lead">名称</td>
            <td><input type="text" name="name" class="form-control"></td>
        </tr>
        <tr>
            <td class="lead">密码</td>
            <td><input type="password" name="pass" class="form-control"></td>
        </tr>
        <tr>
            <td></td>
            <td><button class="login btn btn-lg btn-primary btn-block">登录</button></td>
        </tr>
    </table>
</form>
</div>
</body>
</html>
<script>
    $('.login').click(function(){
        var name=$("input[name='name']").val();
        var pass=$("input[name='pass']").val();
        $.ajax({
            url:'/license/logins',
            data:{'name':name,'pass':pass},
            type:'post',
            dataType:'json',
            success:function (data) {
                if(data.code==0){
                    alert(data.msg);
                    location.href="/license/center";
                }else{
                    alert(data.msg);
                }
            }
        });
        return false;
    });
</script>