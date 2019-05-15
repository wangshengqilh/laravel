{{-- 用户登录--}}

@extends('layouts.bst')

@section('content')
    <form class="form-signin" action="/user/login" method="post">
        {{csrf_field()}}
        <h2 class="form-signin-heading">请登录</h2>
        <label for="inputEmail">邮箱</label>
        <input type="email" name="u_email" id="inputEmail" class="form-control" placeholder="@" required autofocus>
        <label for="inputPassword" >密码</label>
        <input type="password" name="u_pass" id="inputPassword" class="form-control" placeholder="***" required>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> 记住密码
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="sub">登录</button>
    </form>
@endsection
<script src="js/jquery-1.12.4.min.js"></script>
<script>
    $('#sub').click(function () {
        var u_email=$("input[name='u_email']").val();
        var u_pass=$("input[name='u_pass']").val();
        $.ajax({
            type:'post',
            data:{'u_email':u_email,'u_pass':u_pass},
            url:'doLogin',
            dataType:'json',
            success:function (msg) {
                if(msg.code==1){
                    alert(msg.msg);
                }else{
                    alert(msg.msg);
                    location.href="https://wsq123.96myshop.cn/user/center";
                }
            }
        });

    })
</script>



