if(site_url==undefined){var site_url = '';}
$(document).ready(function(){
	$("ul.sf-menu").supersubs({minWidth:16,maxWidth:27,extraWidth:1}).superfish();
	$('.tooltip').tooltip({track: true,delay: 2,showURL: false,showBody: " - "});
	$('.img_tooltip').tooltip({delay: 0,track: true,showURL: false,bodyHandler:function(){return jQuery("<img/>").attr("src", this.rel);}});
	$('.picture_wrapper').hover(function(){$(this).children('.title').slideDown('fast');},function(){$(this).children('.title').slideUp('fast');});

	loadCookieSetts();
	$('.partners_right li:first-child').css({"border-top":0});
	$('.partners_right li:last-child').css({"border-bottom":0});
	$('#us ul li:first-child').css({"border-top":0});
	$('#us ul li:last-child').css({"border-bottom":0});
	jQuery.get
	(
		site_url+"/wallpapers/get_frontpage_footer_walls"+'/'+new Date().getTime(),
		function(html){
			$('#footer_cols').html(html);
			$('#footer_cols ul li:first-child a').css({"border-top":0});
			$('#footer_cols ul li:last-child a').css({"border-bottom":0});
			$('.img_tooltip').tooltip({delay: 0,track: true,showURL: false,bodyHandler:function(){return jQuery("<img/>").attr("src", this.rel);}});
		}
	);

	$('#s_tabs li:first-child a').css({"border-left":"1px solid #000"});

	$("#sortable_sections").sortable({ containment: 'parent', axis: 'y', opacity: 0.6, stop: function(event, ui) {
		showUpdate();
		$.post
		(
			site_url+'/admin/change_widgets',
			jQuery('#sections_frm').serialize(),
			function (html){
				refresh();
			}
		);
	} });

});
function loadCookieSetts(){
	// cookie options
	var cookie_options = { path: '/', expires: 10 };

	if($.cookie('fcw')!='hidden'){$('#fcw').removeClass("hidden");$('#cc > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');}
	if($.cookie('tr')!='hidden'){$('#tr').removeClass("hidden");$('#ac > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');}
	if($.cookie('tw')!='hidden'){$('#tw').removeClass("hidden");$('#tc > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');}
	if($.cookie('us')!='hidden'){$('#us').removeClass("hidden");$('#uc > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');}
	if($.cookie('pw')!='hidden'){$('#pw').removeClass("hidden");$('#pc > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');}
	if($.cookie('cocw')!='hidden'){$('#cocw').removeClass("hidden");$('#coc > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');}
	$('#cc').click(function(){
		if($('#fcw').hasClass("hidden")){
			$('#fcw').removeClass("hidden");
			$.cookie('fcw', null, cookie_options);
			$('#cc > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');
		}
		else {
			$('#fcw').addClass("hidden");
			$.cookie('fcw', 'hidden', cookie_options);
			$('#cc > span.c4').addClass('ui-icon-triangle-1-w');
		}
	});
	$('#ac').click(function(){
		if($('#tr').hasClass("hidden")){
			$('#tr').removeClass("hidden");
			$.cookie('tr', null, cookie_options);
			$('#ac > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');
		}
		else {
			$('#tr').addClass("hidden");
			$.cookie('tr', 'hidden', cookie_options);
			$('#ac > span.c4').addClass('ui-icon-triangle-1-w');
		}
	});
	$('#tc').click(function(){
		if($('#tw').hasClass("hidden")){
			$('#tw').removeClass("hidden");
			$.cookie('tw', null, cookie_options);
			$('#tc > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');
		}
		else {
			$('#tw').addClass("hidden");
			$.cookie('tw', 'hidden', cookie_options);
			$('#tc > span.c4').addClass('ui-icon-triangle-1-w');
		}
	});
	$('#uc').click(function(){
		if($('#us').hasClass("hidden")){
			$('#us').removeClass("hidden");
			$.cookie('us', null, cookie_options);
			$('#uc > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');
		}
		else {
			$('#us').addClass("hidden");
			$.cookie('us', 'hidden', cookie_options);
			$('#uc > span.c4').addClass('ui-icon-triangle-1-w');
		}
	});
	$('#pc').click(function(){
		if($('#pw').hasClass("hidden")){
			$('#pw').removeClass("hidden");
			$.cookie('pw', null, cookie_options);
			$('#pc > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');
		}
		else {
			$('#pw').addClass("hidden");
			$.cookie('pw', 'hidden', cookie_options);
			$('#pc > span.c4').addClass('ui-icon-triangle-1-w');
		}
	});
	$('#coc').click(function(){
		if($('#cocw').hasClass("hidden")){
			$('#cocw').removeClass("hidden");
			$.cookie('cocw', null, cookie_options);
			$('#coc > span.c4').removeClass('ui-icon-triangle-1-w').addClass('ui-icon-triangle-1-s');
		}
		else {
			$('#cocw').addClass("hidden");
			$.cookie('cocw', 'hidden', cookie_options);
			$('#coc > span.c4').addClass('ui-icon-triangle-1-w');
		}
	});
}
function changebg(newbg){
	showUpdate();
	jQuery.get
	( 
		site_url+'/admin/changebg/'+newbg,
		function(html){
			refresh();
		}
	);
}
function ShowTab(T){if(TabSize != null){maxSize = TabSize + 1;} else {maxSize = 7;}for(i=1;i<maxSize;i++){document.getElementById("div" + i).style.display = "none";document.getElementById("tab" + i).className = "";}document.getElementById("div" + T).style.display = "";document.getElementById("tab" + T).className = "active";}
function myPopup2(linkURL,wid,hght,mytitle) {GB_show(mytitle,linkURL,hght,wid);return false;}
function makeactive(tab){document.getElementById("tab1").className = "tab";document.getElementById("tab2").className = "tab";document.getElementById("tab3").className = "tab";document.getElementById("tab"+tab).className = "tab selected";}
function SelectOptions(id){var rel = document.getElementById(id);for (i=0; i < rel.options.length; i++) {rel.options[i].selected = true;}}
function hidediv(id) {if (document.getElementById){document.getElementById(id).style.display = 'none';}else{if(document.layers){document.id.display = 'none';}else{document.all.id.style.display = 'none';}}}
function showdiv(id) {if (document.getElementById){document.getElementById(id).style.display = 'block';}else{if (document.layers){document.id.display = 'block';}else{document.all.id.style.display = 'block';}}}
function MM_jumpMenu(targ,selObj,restore){eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");if (restore) selObj.selectedIndex=0;}
function CheckUncheckAll(frm) {var form = document.getElementById(frm);count = form.elements.length;for (i=0;i<count;i++){if(form.elements[i].checked == 1){form.elements[i].checked = 0;}else {form.elements[i].checked = 1;}}}
function mark_options(id,s){var t = document.getElementById(id);if (!t){return;}var rb = t.getElementsByTagName('input');for (var r = 0; r < rb.length; r++){if (rb[r].id.substr(rb[r].id.length-1) == s){rb[r].checked = true;}}}
function neonixToggle(itemID,linkID,anchorID,tSwitch){if (document.getElementById && navigator.userAgent.indexOf('Opera') == -1){var itemEL = document.getElementById(itemID);var linkEL = document.getElementById(linkID);itemEL.className = itemEL.className == 'TG_visible' ? 'TG_hidden':'TG_visible';}if (anchorID.length != 0){;document.location.href = '#' + anchorID;}}
function dialog(width,height,title,modal,iframe,url){
	var wrapper = 'DJddfhSHS34';
	jQuery('<div id="' + wrapper + '"></div>').appendTo("body");
	$("#"+wrapper).dialog({
		title: title,
		bgiframe: true,
		height: height,
		width: width,
		modal: true
	});
	
	if(iframe){
		jQuery("#"+wrapper).html("<iframe frameborder='0' hspace='0' src='"+url+"' name='TB_iframeContent"+Math.round(Math.random()*1000)+"' allowtransparency='true' style='padding:0!important;margin:0!important;width:100%;height:100%;background:transparent!important' ></iframe>");
	}
	
	jQuery(".ui-dialog").bind('dialogclose', function(event) {
		close_dialog (wrapper);
	});
}
function close_dialog (wrapper){
	$("#"+wrapper).remove ();
	jQuery(".ui-dialog").remove ();
}
function switch_display_type(new_order){
	showUpdate();
	jQuery.ajax
	(
			{
				type: 'GET',
				url: site_url+'/welcome/change_display_type/'+new_order+'/'+new Date().getTime(),
				dataType: 'html',
				cache: false,
				success: refresh
			}
	);
}
function delete_comment(id){
	showUpdate();
	jQuery.get
	( 
		site_url+'/comments/delete/'+id, 
		function(html){
			refresh();
		}
	);
}
function approve_comment(id){
	showUpdate();
	jQuery.get
	( 
		site_url+'/comments/approve/'+id, 
		function(html){
			refresh();
		}
	);
}
function ajax_form(form,site_url,link_id){
	jQuery('#' + link_id).slideUp("slow");
	showUpdate();
	jQuery.post
	( 
		site_url, 
		jQuery('#' + form).serialize(), 
		function(html){
			jQuery('#' + link_id).html(html);
			jQuery('#' + link_id).slideDown("slow");
			hideUpdate();
		}
	);
}
function ajax_add_inputs(form,site_url,link_id){
	jQuery('#' + link_id).slideUp("slow");
	showUpdate();
	jQuery.post
	( 
		site_url, 
		jQuery('#' + form).serialize(), 
		function(html){
			jQuery('#' + link_id).append(html);
			jQuery('#' + link_id).slideDown("slow");
			hideUpdate();
		}
	);
}
function ajax_mass_options(send_to,form){
	showUpdate();
	jQuery.post
	( 
		send_to, 
		jQuery('#' + form).serialize(), 
		function(html){
			refresh();
		}
	);
}
function ajax_paginate(c_url,link_id){showUpdate();jQuery('#' + link_id).load("" + c_url + "", hideUpdate);}
function adult_confirm(c_url,link_id){jQuery('#' + link_id).load("" + c_url + "", self.parent.tb_remove);}
function add_class(el_id,new_class){return jQuery('#' + el_id).addClass(new_class);}
function remove_class(el_id,new_class){return jQuery('#' + el_id).removeClass(new_class);}
function showUpdate() {
	var image_path = base_url + "templates/" + active_template + "/images/loadingAnimation.gif";

	$('<div id="loading-overlay"/>').appendTo(document.body)
	.css({
		borderWidth: 0, margin: 0, padding: 0,
		position: 'fixed', top: 0, left: 0, right:0, bottom: 0,
		background:'#000',
		opacity:0.5,
		'z-index':9999
	});

	var half_w_width = ($("#loading-overlay").width()/2)-104;
	var half_w_height = ($("#loading-overlay").height()/2)-6;

	$('<div id="loading-bar"/>').appendTo(document.body)
	.css({
		borderWidth: 0, margin: 0, padding: 0,
		position: 'fixed', top: half_w_height, left: half_w_width,
		width:'208px',
		height:'13px',
		opacity:1,
		'z-index':9999,
		backgroundImage:'url('+image_path+')',
		backgroundRepeat:'no-repeat',
		backgroundPosition:'0 0'
	});
}
function hideUpdate() {$("#loading-overlay").remove();$("#loading-bar").remove();}
function refresh(){location.reload();}
function filterNonNumeric(field) {
	var result = new String();
	var numbers = "0123456789";
	var chars = field.value.split(""); // create array
	for (i = 0; i < chars.length; i++) {
		if (numbers.indexOf(chars[i]) != -1) result += chars[i];
	}
	if (field.value != result) field.value = result;
}
Array.prototype.count = function(){return this.length;};
function jquery_slide_down(el_id){jQuery('#' + el_id).slideDown("slow");}
function jquery_slide_up(el_id){jQuery('#' + el_id).slideUp("slow");}
function add_file_input(){
	$('#ws_wallpapers_file').append('<input type="file" id="wallpapers[]" name="wallpapers[]" class="element file" value="" />');
}