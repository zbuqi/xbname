<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>批量更新域名备案</title>
    <script type="text/javascript" src="/js/jquery-3.6.3.min.js"></script>
</head>
<body>
<div class="ids"><?php foreach($ids as $item): ?>{{ $item }},<?php endforeach ?></div>
    <script type="text/javascript">
        $(function(){
            var ids = $(".ids").html();
            var ids = ids.split(',');
            for(var i=0; i<ids.length; i++){
                if(ids[i]){
                   $.ajax({
                       url: '/update/beian/tmp_name/' + ids[i],
                       type: 'GET',
                       success: function(data){
                           console.log(data);
                       },
                       error : function(){
                           alert("错误");
                       }
                   });
                }
            }
        });
    </script>
</body>
</html>
