<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="{{ asset('js/jquery-1.12.4.min.js') }}"></script>
    <script src="{{asset('js/ajaxfileupload.js')}}"></script>
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
<div class="container">
<form enctype="" onsubmit="return false" class="form-signin">
    <h2 class="form-signin-heading">Please reg</h2>
    <table>
        <tr>
            <td class="lead">名称</td>
            <td><input type="text" name="name" class="form-control"></td>
        </tr>
        <tr>
            <td class="lead">密码</td>
            <td><input type="password" name="pass1" class="form-control"></td>
        </tr>
        <tr>
            <td class="lead">确认密码</td>
            <td><input type="password" name="pass2" class="form-control"></td>
        </tr>
        <tr>
            <td class="lead">类型</td>
            <td><input type="text" name="type" class="form-control"></td>
        </tr>
        <tr>
            <td class="lead">住所</td>
            <td><input type="text" name="home" class="form-control"></td>
        </tr>
        <tr>
            <td class="lead">法定代表人</td>
            <td><input type="text" name="user" class="form-control"></td>
        </tr>
        <tr>
            <td class="lead">注册资本</td>
            <td><input type="text" name="num" class="form-control"></td>
        </tr>
        <tr>
            <td class="lead">营业期限</td>
            <td><input type="text" name="endtime" class="form-control"></td>
        </tr>
        <tr>
            <td class="lead">经营范围</td>
            <td><input type="text" name="scope" class="form-control"></td>
        </tr>
        {{--<tr>--}}
            {{--<td>文件上传</td>--}}
            {{--<td><input type="file" name="img" id="img"></td>--}}
        {{--</tr>--}}
        <tr>
            <td></td>
            <td><button class="reg btn btn-lg btn-primary btn-block">注册</button></td>
            {{--<td><input type="hidden" id="imgs"></td>--}}
        </tr>
    </table>
</form>
</div>
</body>
</html>
<script>
    $(function () {
        $('.reg').click(function(){
            var name=$("input[name='name']").val();
            var pass1=$("input[name='pass1']").val();
            var pass2=$("input[name='pass2']").val();
            var type=$("input[name='type']").val();
            var home=$("input[name='home']").val();
            var user=$("input[name='user']").val();
            var num=$("input[name='num']").val();
            var endtime=$("input[name='endtime']").val();
            var scope=$("input[name='scope']").val();
            // var imgs=$("#imgs").val();
            $.ajax({
                url:'/license/regadd',
                data:{'name':name,'pass1':pass1,'pass2':pass2,'type':type,'home':home,'user':user,'num':num,'endtime':endtime,'scope':scope},
                type:'post',
                dataType:'json',
                success:function (data) {
                    if(data.code==0){
                        alert(data.msg);
                        location.href="/license/login";
                    }else{
                        alert(data.msg);
                    }
                }
            });
            // $("#img").change(function(){
            //     alert(1);
            //     var url="/license/img";
            //     $.ajaxFileUpload({
            //         type:"post",
            //         url:url,
            //         secureuri:false,
            //         fileElementId:"img",
            //         dataType:"json",
            //         success:function(msg){
            //             $('#imgs').val(msg);
            //         }
            //     });
            // });
            return false;
        });
    });
</script>