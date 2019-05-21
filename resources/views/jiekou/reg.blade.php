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
        <td>用户名</td>
        <td><input type="text" name="name"></td>
    </tr>
    <tr>
        <td>密码</td>
        <td><input type="password" name="pass1"></td>
    </tr>
    <tr>
        <td>确认密码</td>
        <td><input type="password" name="pass2"></td>
    </tr>
    <tr>
        <td>邮箱</td>
        <td><input type="email" name="email"></td>
    </tr>
        <tr>
            <td></td>
            <td><button class="reg">注册</button></td>
        </tr>
    </table>
</form>
</body>
</html>
<script src="{{ asset('js/jquery-1.12.4.min.js') }}"></script>
<script>
    $('.reg').click(function(){
        var name=$("input[name='name']").val();
        var pass1=$("input[name='pass1']").val();
        var pass2=$("input[name='pass2']").val();
        var email=$("input[name='email']").val();
        $.ajax({
            url:'/jiekou/regadd',
            data:{'name':name,'pass1':pass1,'pass2':pass2,'email':email},
            type:'post',
            dataType:'json',
            success:function (data) {
                if(data.code==0){
                    alert(data.msg);
                    location.href="/jiekou/login";
                }else{
                    alert(data.msg);

                }
            }
        });
    });
</script>