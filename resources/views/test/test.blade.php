<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .degue{
            width: 400px;
            height: 20px;
            background-color: yellow;
        }
        .show{
            height: 20px;
            background-color: black;
            width: 0px;
        }
    </style>
</head>
<script src="/js/jquery-1.12.4.min.js"></script>
<body>
    <input type="file" id="img">
    <input type="button" name="btn" value="上传">
    <div class="degue">
        <div class="show"></div>
        <span class="text"></span>
    </div>
</body>
</html>
<script>
    $(document).ready(function(){
        size=1024;
        index=1;
        totalPage=0;
        var per=0;
        $("input[name='btn']").click(function(){
            upload(index);
        });

        function upload(index){
            var objfile = document.getElementById("img").files[0];
            var filesize = objfile.size;//图片大小
            totalPage = Math.ceil(filesize/size);
            var filename = objfile.name;//文件名字

            var start = (index-1) * size;//开始位置
            var end = start+size;//结束位置
            var chunk = objfile.slice(start,end);//每页数据
            per =((start/filesize)*100).toFixed(2);
            var form = new FormData();//表单对象
            form.append("file",chunk,filename);
            $.ajax({
                type:"post",
                data: form,
                url : "uploadinfo",
                processData: false,
                contentType: false,//mima类型
                cache:false,
                dataType : "json",
                async:true,//同步
                success:function(msg){
                    if(index < totalPage){
                        index++;
                        per = per+"%";
                        $(".show").css({width:per});
                        $(".text").text(per);
                        upload(index);
                    }else{
                        $(".show").css({width:"100%"});
                        $(".text").text("100%");
                    }
                }
            });
        }
    });
</script>