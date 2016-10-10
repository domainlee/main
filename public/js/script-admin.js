;jQuery.fn.outerHTML=function(s){return (s) ? this.before(s).remove() : jQuery("<div>").append(this.eq(0).clone()).html();};
;(function(f,h,$){var a='placeholder' in h.createElement('input'),d='placeholder' in h.createElement('textarea'),i=$.fn,c=$.valHooks,k,j;if(a&&d){j=i.placeholder=function(){return this};j.input=j.textarea=true}else{j=i.placeholder=function(){var l=this;l.filter((a?'textarea':':input')+'[placeholder]').not('.placeholder').bind({'focus.placeholder':b,'blur.placeholder':e}).data('placeholder-enabled',true).trigger('blur.placeholder');return l};j.input=a;j.textarea=d;k={get:function(m){var l=$(m);return l.data('placeholder-enabled')&&l.hasClass('placeholder')?'':m.value},set:function(m,n){var l=$(m);if(!l.data('placeholder-enabled')){return m.value=n}if(n==''){m.value=n;if(m!=h.activeElement){e.call(m)}}else{if(l.hasClass('placeholder')){b.call(m,true,n)||(m.value=n)}else{m.value=n}}return l}};a||(c.input=k);d||(c.textarea=k);$(function(){$(h).delegate('form','submit.placeholder',function(){var l=$('.placeholder',this).each(b);setTimeout(function(){l.each(e)},10)})});$(f).bind('beforeunload.placeholder',function(){$('.placeholder').each(function(){this.value=''})})}function g(m){var l={},n=/^jQuery\d+$/;$.each(m.attributes,function(p,o){if(o.specified&&!n.test(o.name)){l[o.name]=o.value}});return l}function b(m,n){var l=this,o=$(l);if(l.value==o.attr('placeholder')&&o.hasClass('placeholder')){if(o.data('placeholder-password')){o=o.hide().next().show().attr('id',o.removeAttr('id').data('placeholder-id'));if(m===true){return o[0].value=n}o.focus()}else{l.value='';o.removeClass('placeholder');l==h.activeElement&&l.select()}}}function e(){var q,l=this,p=$(l),m=p,o=this.id;if(l.value==''){if(l.type=='password'){if(!p.data('placeholder-textinput')){try{q=p.clone().attr({type:'text'})}catch(n){q=$('<input>').attr($.extend(g(this),{type:'text'}))}q.removeAttr('name').data({'placeholder-password':true,'placeholder-id':o}).bind('focus.placeholder',b);p.data({'placeholder-textinput':q,'placeholder-id':o}).before(q)}p=p.removeAttr('id').hide().prev().attr('id',o).show()}p.addClass('placeholder');p[0].value=p.attr('placeholder')}else{p.removeClass('placeholder')}}}(this,document,jQuery));
;function addEvent(e,t,n){if(e.addEventListener){e.addEventListener(t,n,false)}else{if(!n.$$guid)n.$$guid=addEvent.guid++;if(!e.events)e.events={};var r=e.events[t];if(!r){r=e.events[t]={};if(e["on"+t]){r[0]=e["on"+t]}}r[n.$$guid]=n;e["on"+t]=handleEvent}}function removeEvent(e,t,n){if(e.removeEventListener){e.removeEventListener(t,n,false)}else{if(e.events&&e.events[t]){delete e.events[t][n.$$guid]}}}function handleEvent(e){var t=true;e=e||fixEvent(((this.ownerDocument||this.document||this).parentWindow||window).event);var n=this.events[e.type];for(var r in n){this.$$handleEvent=n[r];if(this.$$handleEvent(e)===false){t=false}}return t}function fixEvent(e){e.preventDefault=fixEvent.preventDefault;e.stopPropagation=fixEvent.stopPropagation;return e}addEvent.guid=1;fixEvent.preventDefault=function(){this.returnValue=false};fixEvent.stopPropagation=function(){this.cancelBubble=true};
;(function($){var h=$.scrollTo=function(a,b,c){$(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=$(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

var nfs = '.'; // numberFormatSign
var nfsReg = new RegExp('\\.','g');
var lang = 'vn';
$(function(){
	formControls();
	dataGrid();
	nbHint();
	reportTabs();
    initAlert();
//    cloudPrinting();
});

function cloudPrinting(){
//	$("#cloudPrinting").change(function(){
//		setCookie("cloudPrinting", document.getElementById("cloudPrinting").checked, 365);
//	});
}

function hd(){
	$('.ddbox .top, .ddmn').hover(
		function(){
			$(this).addClass('h');
			$(this).find('ul.sub').addClass('h');
		},
		function(){
			$(this).removeClass('h');
			$(this).find('ul.sub').removeClass('h');
		}
	);
	if(jQuery().treeview) {
		$("#amn .navigation").treeview({
			collapsed: false,
			unique: false,
			persist: "location"
		});
	}
}

function menu() {
	leftTreeviewHeight();
	if(jQuery().dropDownMenu) {
		$('.ddMenu').dropDownMenu();
	}
	$('.menus').each(function(){
		$(this).find('.menu:first').addClass('first');
		$(this).find('.menu:last').addClass('last');
	});
	$('.menu h3').each(function(){
		$(this).append("<span class='bgr'></span>");
	});
	// add nice scroll to menu
	$('.header .navigation').slimScroll({
		height: $(window).height() - 80,
		width: 200,
		start: $('.subnav li.active').length ? $('.subnav li.active') : 'top'
	});
	// init menu searchable
	var menuList = new List('smartMenu', {
		listClass: 'navigation',
		searchClass: 'smartMenuSearch',
		valueNames: ['smartMenuName', 'smartMenuNameNormal']
	});
	// focus search on menu hover
	$('.allmenu').hover(
		function(){
			$(this).find('.subnav').show();
			$(this).find('.smartMenuSearch').focus();
		},
		function(){
			$(this).find('.subnav').hide();
		}
	);
	// init menu by cookie
	if ($.cookie('menu') == 1){
		showAllMenu();
	}
	// init click for touch
	$('.allmenu > a').click(function(){
		if ($.cookie('menu') == 1){
			$.cookie('menu', 0, {path: '/', expires: 7});
		}else{
			$.cookie('menu', 1, {path: '/', expires: 7});
		}
		var parent = $(this).parent();
		parent.toggleClass('h');
		parent.find('.subnav').toggleClass('stickme');
		if (!parent.find('.subnav').hasClass('stickme')){
			parent.find('.subnav').hide();
		}
		parent.find('.smartMenuSearch').focus();
		$('#amain').toggleClass('narrow');
		$('.ft').toggleClass('narrow');
		$(window).scroll(function(){
			if ($(window).scrollTop() > 40){
				$('.stickme').css({
					'top': 0,
					'padding-bottom': 40
				});
			}else{
				$('.stickme').css({
					'top': 40
				});
			}
		});
		stickHeader();
		return false;
	});
	// init some inputs with clear icon
	$(document).on('propertychange keyup input paste', 'input.hasClear', function(){
		if ($(this).val().length){
			$(this).next('.inputClear').show();
		}else{
			$(this).next('.inputClear').hide();
		}
	}).on('click', '.inputClear', function() {
		$(this).hide().prev('input').val('').focus();
		menuList.search();
	});
	//init icons ribbon
	$('.ribbon a').each(function(i, a){
        $(a).before('<i class="icon-th-list"></i>');
	});
}

function expandReportMenu(){
	$('.tabWr .navigation').toggleClass('expanded').find('li').unbind('mouseenter mouseleave');
}

function showAllMenu(){
	$('.allmenu').addClass('h');
	$(window).scroll(function(){
		if ($(window).scrollTop() > 40){
			$('.stickme').css({
				'top': 0,
				'padding-bottom': 40
			});
		}else{
			$('.stickme').css({
				'top': 40
			});
		}
	});
}

function jqEffects() {
	/*$('.juiTabs').tabs();
	$('.jbtn').each(function(){
		$(this).button();
	});
	$('.juiBtnSet input.rd').each(function(){
		$(this).parent('label').attr('for', $(this).attr('id'));
		$(this).parent('label').before($(this));
	});
	$('.juiBtnSet').buttonset();
	$('.juiBtnSet').css('padding', '0 5px 0 0');*/
}

function listItems(){
	$('ul').each(function(){
		$(this).children('li.first').removeClass('first');
		$(this).children('li.last').removeClass('last');
		$(this).children('li:first').addClass('first');
		$(this).children('li:last').addClass('last');
	});
	$('.list').each(function(){
		$(this).children('.item.first').removeClass('first');
		$(this).children('.item.last').removeClass('last');
		$(this).children('.item:first').addClass('first');
		$(this).children('.item:last').addClass('last');
	});
}

function nbHint(){
	$("form:not('.fFilter') .tb.nb").each(function(){
		addNbHint($(this));
	});
	$("form:not('.fFilter')").on('keyup', '.tb.nb',function(){
		addNbHint($(this));
		$(this).siblings('span.nbHint').html(formatDecimal($(this).val()));
	});
}

function addNbHint(e){
	if(!$(e).siblings('span.nbHint').length){
		$(e).after(
			"<span class='nbHint' style='margin:0 8px'>"+formatDecimal($(e).val())+"</span>"
		);
	}
}

function btnAppend(){
	$('.btn, .btnBig').each(function(){
		//$(this).append("<span class='bgr'></span>");
	});
	$('.btns').each(function(){
		$(this).find('.btn:first').addClass('first');
		$(this).find('.btn:last').addClass('last');
	});
	$('.btn.submit').click(function(){
		var form = $(this).parents('form');
		if(checkForm(form) && !$(this).hasClass('clked')){
			$(this).addClass('clked');
			var storeIds = "";
			$('tr:gt(0)').each(function(){
				storeIds += $(this).attr('id');
			});
			$('#storeIds').val(storeIds);
			$(form).submit();
		}
	});
}

function formControls() {
	$('.fsm .tb').keydown(function(e){
		var form = $(this).parents('form');
		if(e.which == 13 && checkForm(form)){
			$(form).submit();
		}
	});
    //bindJQueryDatepicker();
    bindBootstrapDatepicker();
}

function bindBootstrapDatepicker(){
    $('.tb.date').each(function(i, ele){
        $(ele).bootstrapDP({
            language: 'vi',
            format: 'dd/mm/yyyy',
            todayBtn: 'linked'
        });
        $(ele).after('<i class="icon-calendar"></i>');
    });
}

function bindJQueryDatepicker(){
    $.datepicker.setDefaults($.datepicker.regional['en']);
    if(lang != 'en'){
        $.datepicker.setDefaults($.datepicker.regional[lang]);
    }
    $('.tb.date').each(function(){
        var option = {
            firstDay: 1,
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'dd/mm/yy'
        };
        if($(this).attr('yearRange')){
            option.yearRange = $(this).attr('yearRange');
        }
        if($(this).attr('minDate')){
            option.minDate = $(this).attr('minDate');
        }
        if($(this).attr('maxDate')){
            option.maxDate = $(this).attr('maxDate');
        }
        if($(this).attr('defaultDate')){
            option.defaultDate = $(this).attr('defaultDate');
        }
        $(this).datepicker(option);
    });
}

/**
 * datagrid's effects
 */
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
	stickHeader();
}

function stickHeader(){
	$('table.dg').each(function(i, table){
		if ($(table).hasClass('notStickHeader')){
			return;
		}
		var theadClone = $(table).find('thead').clone(true);
		var stickyHeader =  $('<div></div>').addClass('stickyHeader hide');
		stickyHeader.append($('<table class="dg tbClone table table-bordered table-condensed" cellspacing=0 cellpadding=0></table>')).find('table').append(theadClone);
		if ($(table).next().hasClass('stickyHeader')){
			$(table).next().remove();
		}
		$(table).after(stickyHeader);
		
		var tableHeight = $(table).height();
		var tableWidth = $(table).width() + Number($(table).css('padding-left').replace(/px/ig,"")) + Number($(table).css('padding-right').replace(/px/ig,"")) + Number($(table).css('border-left-width').replace(/px/ig,"")) + Number($(table).css('border-right-width').replace(/px/ig,""));
		
		var headerCells = $(table).find('thead th');
		var headerCellHeight = $(headerCells[0]).height();
		
		var no_fixed_support = false;
		if (stickyHeader.css('position') === "absolute") {
			no_fixed_support = true;
		}
		
		var stickyHeaderCells = stickyHeader.find('th');
		stickyHeader.css('width', tableWidth);
		
		for (i=0; i<headerCells.length; i++) {
			var headerCell = $(headerCells[i]);
			var cellWidth = headerCell.width();
			$(stickyHeaderCells[i]).css('width', cellWidth);
		}
		
		$(window).scroll(function() {
			var currentPosition = $(window).scrollTop();
			var cutoffTop = $(table).offset().top;
			var cutoffBottom = tableHeight + cutoffTop - headerCellHeight;
			
			if (currentPosition > cutoffTop && currentPosition < cutoffBottom) {
				stickyHeader.removeClass('hide');
				if (no_fixed_support) {
					stickyHeader.css('top', currentPosition + 'px');
				}
			}else {
				stickyHeader.addClass('hide');
			}
		});
	});
}

function checkAll(tableId, colIndex){
	var cbId = $('#' + tableId + ' tr:first th:eq(' +colIndex+ ')').find('input.cb').attr('id');
	var tableRows = $('#' +tableId+ ' tr:gt(0)');

	tableRows.each(function(rowIndex){
		selectRow(tableId, rowIndex+1, colIndex);
	});

	$('#' + cbId).click(function(){
		if($(this).attr('checked')){
			tableRows.each(function(rowIndex){
				$(this).find('td:eq(' +colIndex+ ')').find('input.cb').attr('checked','checked');
				if($(this).find('td:eq(' +colIndex+ ')').find('input.cb').length > 0) {
					$(this).addClass('s');
				}
			});
		} else {
			tableRows.each(function(){
				$(this).find('td:eq(' +colIndex+ ')').find('input.cb').removeAttr('checked');
				$(this).removeClass('s');
			});
		}
	});
}

function selectRow(tableId, rowIndex, colIndex){
	$('#' +tableId+ ' tr:eq(' +rowIndex+ ') td:eq(' +colIndex+ ')').find('input.cb').click(function(){
		if($(this).attr('checked')){
			$('#' +tableId+ ' tr:eq(' +rowIndex+ ')').addClass('s');
		} else {
			$('#' +tableId+ ' tr:eq(' +rowIndex+ ')').removeClass('s');
		}
	});
}

function addOverLay(){
	var size = "width:" + $(window).width() + 'px;';
	size += "height:" + $(window).height() + 'px';
	$('body').append("<div class='overlay ui-widget-overlay' style='" + size + "'></div>");
}

function removeOverLay(){
	$('body').find("div.overlay").remove();
}

function buildUri(uri){
	return uri; /** @todo enable locale */
	//return '/' + locale + uri;
}

function formatDecimal(n){
	n += '';
	if(!$.trim(n)){
		return '';
	}
	// /^\d+$/
	if(/^-{0,1}\d*\.{0,1}\d+$/.test(n)){
		var result = '';
		while(n.length > 3){
			result = nfs + n.substr(n.length-3, 3) + result;
			n = n.substring(0, n.length-3);
		}
		return (n + result).replace('-' + nfs, '-');
	} else {
		return '';
	}
}

function isInt(i) {
	return /^\d+$/.test(i);
}

function isFloat(i){
	return /^[+-]?\d+(\.\d+)?$/.test(i);
}

function checkForm(f){
	$(f).find('label.required').each(function(){
		checkRequired($(this).next('.tb, .ta, select'));
	});
	$(f).find('.tb.required, select.required').each(function(){
		checkRequired($(this));
	});
	$(f).find('.tb.int').each(function(){
		checkInt($(this));
	});
	if($(f).find('.error').length){
		$(f).find('.error:first').focus();
		return false;
	}
	return true;
}

function checkRequired(e){
	if(!$.trim(e.val())){
		e.addClass('error');
	} else {
		e.removeClass('error');
	}
}

function checkInt(e){
	if(e.val()){
		if(!isInt(e.val())){
			e.addClass('error');
		} else {
			e.removeClass('error');
		}
	}
}

function setImportLocation(ele, url){
	var target = $(ele).val()
	if (target){
		window.location.href = url + target
	}
}

function FormRequired(form, options) {
	var formsRequired = false;
	var options = options || {};

	if ($(form) && $(form).attr('id')) {
		$(form).find(':input').each(function(i, input) {
			$(input).change(function() {
				formsRequired = true;
			});
		});
	}

	addEvent(window, 'load', function() {
		window.onbeforeunload = function() {
			if (formsRequired) {
				return options.message || '';
			}
		}
	});
}

function setLocation(url) {
	window.location.href = url;
}

function reportTabs(){
	$(".btn-group.tabs .dropdown-menu a.active").each(function(){
		$(this).parents('.dropdown').find('a.dropdown-toggle').addClass('active');
	});
}

function formReset() {
	window.location.href = window.location.href.split('?')[0];
}

function randomString(strLength)
{
	var strLength = strLength ? strLength : 6;
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < strLength; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function randomNumberString(strLength)
{
	var strLength = strLength ? strLength : 6;
    var text = "";
    var possible = "0123456789";

    for( var i=0; i < strLength; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function initAlert(){
    inventoryAlert();

    setInterval(function(){
        inventoryAlert();
    }, 1000*60*5); // 5 minutes
}

function inventoryAlert(){
    $.get('/inventory/inventory/alert', [], function(data){
        if (typeof data === 'object' && data.data > 0){
            var tmp = $('#warningList').find('#inventoryNote');
            if (tmp.length){
                tmp.find('.badge').text(data.data);
            }else{
                alertIncrement();
                var li = '<li id="inventoryNote">'+
                    '<a href="/inventory/inventory/index?safetyStock=1"><div class="clearfix">'+
                    '<span class="">Nguyên liệu cần nhập</span>'+
                    '<span class="badge badge-warning">'+data.data+'</span>'+
                    '</div></a></li>';
                $('#warningList').append(li);
            }
        }
    });
}

function alertIncrement(){
    var node = $('#warningNumber');
    var bell = $('#warningNumber').parent().find('i');
    var count = node.text() ? parseInt(node.text()) : 0;

    node.text(count + 1);
    bell.addClass('icon-animated-bell');
    setTimeout(function(){
        bell.removeClass('icon-animated-bell');
    }, 1000*60*1); // 1 minute
}

function setCookie(c_name,value,exdays)
{
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value = escape(value)
			+ ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
	document.cookie = c_name + "=" + c_value;
}
function getCookie(c_name)
{
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1) {
		c_start = c_value.indexOf(c_name + "=");
	}
	if (c_start == -1) {
		c_value = null;
	} else {
		c_start = c_value.indexOf("=", c_start) + 1;
		var c_end = c_value.indexOf(";", c_start);
		if (c_end == -1) {
			c_end = c_value.length;
		}
		c_value = unescape(c_value.substring(c_start, c_end));
	}
	return c_value;
}