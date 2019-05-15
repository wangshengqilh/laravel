<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="/js/jquery-1.12.4.min.js"></script>
    <title>Document</title>
</head>
<body>
<form action="">
    {{csrf_field()}}
        接口：<input type="text" name="jk"><br>
        访问次数：<input type="text" name="num"><br>
        过期时间：<input type="text" name="time"><br>
    <button type="submit" id="sub">提交</button>
</form>
</body>
</html>
<script src="/js/jquery-1.12.4.min.js"></script>
<script>
    $('#sub').click(function () {
        var jk=$("input[name='jk']").val();
        var num=$("input[name='num']").val();
        var time=$("input[name='time']").val();
        $.ajax({
            type:'post',
            data:{'jk':jk,'num':num,'time':time},
            url:'she',
            dataType:'json',
            success:function () {
                alert(成功);
            }
        });

    });
</script>