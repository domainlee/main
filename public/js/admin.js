$(function(){
	$('#datepicker1').datepicker();
	$('#datepicker2').datepicker();
	dataGrid();
	function dataGrid(){
		$('.dg').each(function(){
			if($(this).find('tr:first th:first').find('input.cb').length > 0){
				if($(this).attr('id')){
					checkAll($(this).attr('id'), 0);
				}
			}
	        $(this).find('tr').each(function(i, tr){
	            $(tr).find('td').each(function(j, td){
	                var rowspan = $(td).attr('rowspan');
	                if (rowspan){
	                    var next = $(tr);
	                    for(var k = 1; k <= rowspan; k++){
	                        next.attr('trid', i);
	                        next.addClass('trid-'+i);
	                        next = next.next();
	                    }
	                    return;
	                }
	            });
	            $(tr).hover(
	                function(){
	                    var trid = $(this).attr('trid');
	                    if (trid) $('tr.trid-'+trid).addClass('h');
	                },
	                function(){
	                    var trid = $(this).attr('trid');
	                    if (trid) $('tr.trid-'+trid).removeClass('h');
	                }
	            );
	        });
		});
	}
	
});


















