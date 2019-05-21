<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form>
    <table>
        <tr>
            <td>名称</td>
            <td><input type="text" name="name"></td>
        </tr>
        <tr>
            <td>密码</td>
            <td><input type="password" name="pass"></td>
        </tr>
        <tr>
            <td></td>
            <td><button class="login">登录</button></td>
        </tr>
    </table>
</form>
</body>
</html>
<script src="{{ asset('js/jquery-1.12.4.min.js') }}"></script>
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