{{-- 用户登录--}}

@extends('layouts.bst')

@section('content')
    <form class="form-signin" action="/user/login" method="post">
        {{csrf_field()}}
        <label for="inputEmail">用户名：</label>
        <input type="email" name="is_login" id="inputEmail" class="form-control" placeholder="@" required autofocus>
        <label for="inputPassword" >密码：</label>
        <input type="password" name="u_pass" id="inputPassword" class="form-control" placeholder="***" required>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">登&nbsp;&nbsp;&nbsp;&nbsp; 录</button>
    </form>
@endsection