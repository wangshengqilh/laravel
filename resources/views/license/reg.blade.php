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
<form onsubmit="return false">
    <table>
        <tr>
            <td>名称</td>
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
            <td>类型</td>
            <td><input type="text" name="type"></td>
        </tr>
        <tr>
            <td>住所</td>
            <td><input type="text" name="home"></td>
        </tr>
        <tr>
            <td>法定代表人</td>
            <td><input type="text" name="user"></td>
        </tr>
        <tr>
            <td>注册资本</td>
            <td><input type="text" name="num"></td>
        </tr>
        <tr>
            <td>营业期限</td>
            <td><input type="text" name="endtime"></td>
        </tr>
        <tr>
            <td>经营范围</td>
            <td><input type="text" name="scope"></td>
        </tr>
        {{--<tr>--}}
            {{--<td>文件上传</td>--}}
            {{--<td><input type="file" name="img" id="img"></td>--}}
        {{--</tr>--}}
        <tr>
            <td></td>
            <td><button class="reg">注册</button></td>
            {{--<td><input type="hidden" id="imgs"></td>--}}
        </tr>
    </table>
</form>
</body>
</html>
<script src="{{ asset('js/jquery-1.12.4.min.js') }}"></script>
<script src="{{asset('js/ajaxfileupload.js')}}"></script>
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