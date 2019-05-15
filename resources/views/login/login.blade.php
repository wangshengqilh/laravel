@extends('layouts.bst')
@section('content')
    <style>
        .form-control{
            width: 280px;
        }
        .dl{
            width: 280px;
            text-align: center;
        }
    </style>
    <div class="center-block">
        <h1 class="bg-primary dl">登录</h1>
        <form action="/logininfo" method="post">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">账号：</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="account number" name="account">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">密码：</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="pwd">
            </div>
            <button type="submit"  class="btn btn-default">登录</button>
            <a href="/users"  class="btn btn-default">注册</a>
        </form>
    </div>
@endsection