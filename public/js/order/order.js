$(function(){
	$('.changeStaus a').click(function(){
		$(this).addClass('changeting');
		$('#confdelete').dialog({
			title:'Đổi trạng thái đơn hàng',
			modal: true,
			draggable: false,
			buttons:[
			        {
			        	text:"Thay đổi",
			        	class: "btn btn-danger",
			        	click: function(){
			        		ChangeStatus();
				    		  $(this).dialog("close");
				    		 // location.reload();
				    	  }
			        },
			        {
			        	text:"Đóng",
			        	class:"btn",
			        	click:function(){
			        		$(this).dialog("close");
			        	}
			        }
			        ]
			 
		});
			
	});
});
function ChangeStatus(){
	var value = $('#selectSta').val();
	$.post(
			$('.changeting').attr("lnk"),
			{
				value: value
			},
			function(data){
				if(data.code == 1){
					location.reload();
				}
				else{
					alert(2);
				}
			}
	);
}











