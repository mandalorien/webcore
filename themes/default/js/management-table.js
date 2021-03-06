/**
 * This file is part of Cloud-Partner
 *
 * @license none
 *
 * Copyright (c) 2008-Present, CIIAB
 * All rights reserved.
 *
 * create 2018 by CEOS-IT
 */
$(function() {
	$(document).on("click","tbody tr", function() {
		$("tbody tr").removeClass( "active" );
		$( this ).toggleClass( "active" );
		var IDFocus = $( this ).children()[0].textContent;
		$(".menuTop a").each(function(index) {
			if(index != 0){
				var url = $(this).attr('href');
				var res = url.split("?");
				$(this).attr('href',res[0] + '?param='+ IDFocus);
			}
		});
	});
	
	$(document).on("click",".card-header", function() {
		$(".card-header").removeClass( "active" );
		$( this ).toggleClass( "active" );
		var IDFocus = $( this ).parent().attr('id');
		$(".menuTop a").each(function(index) {
			if(index != 0 && index != 1){
				var url = $(this).attr('href');
				var res = url.split("?");
				$(this).attr('href',res[0] + '?param='+ IDFocus);
			}
		});
	});
});