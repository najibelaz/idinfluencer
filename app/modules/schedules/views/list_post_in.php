<table class="table">
    <thead>
        <tr>
            <th>Profile</th>
            <th>Caption</th>
            <th>Image</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<script>
$(document).ready(function(){
    $data=[];
    $i=0;
    $.get('<?=cn("schedules/getlistProfileInstagram")?>',function($users,status) {
        $.each($.parseJSON($users), function(index,user){
            console.log(user.ids);
            if($i%4==0){
                setTimeout(function(){}, 12000);
            }
            $.get('<?=cn("schedules/getFeedInstagram/")?>'+user.ids,function($feeds,statusF) {
                $.each($.parseJSON($feeds), function(indexf,feed){
                    console.log(feed);
                });
                setTimeout(function(){}, 12000);
            });
            $i++;
        });
    });
});
</script>