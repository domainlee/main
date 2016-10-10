$(function(){
	$('.delete a').click(function(){
		$(this).addClass('deleting');
		$('#confdelete').html("<b>Bạn chắc chắn xóa bài viết này ?<b>");
		$('#confdelete').dialog({
			title:"Xóa bài viết",
			modal:true,
			draggable:false,
			buttons:[
				         {
				        	text:"Delete",
				        	class:"btn btn-danger",
				        	click:function(){
				        		fDelete();
				        		$(this).dialog("close");
				        	}
				         },
				         {
				        	 text:"Cancel",
				        	 class:"btn",
				        	 click:function(){
				        		 $(this).dialog("close");
				        	 }
				         }
			         ]
		});
	});
});
function fDelete(){
	$.post(
			$(".deleting").attr("lnk"),
			{},
			function(data){
				if(data.code == 1){
					$('.deleting').parents("tr").remove();
				}
			},"json"
			
	);
}



















