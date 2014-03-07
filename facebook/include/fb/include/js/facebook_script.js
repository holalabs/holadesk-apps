var fb_url = App.ajaxurl;

var fbStatusList = 'fbStatusList';

$('#fb_displayHomeBtn').live('click', function(event) {
	event.preventDefault();
	displayFacebookHome();
});

$('#fb_displayFeedBtn').live('click', function(event) {
	event.preventDefault();
	displayFacebookFeed();
});

$('#fb_displayPostsBtn').live('click', function(event) {
	event.preventDefault();
	displayFacebookPosts();
});

$('#fb_displayFriendsBtn').live('click', function(event) {
	event.preventDefault();
	displayFriendsPictures();
});

$('#fbDisplayMoreBtn').live('click', function(event) {
	event.preventDefault();
	var url = $('body').data('next_paging');
	displayFeed({'url':url, 'append':'1'})
});

function postView() {
	$('div.ui-navbar').hide();
	$('div#fbStatusList').hide();
	$('div#fbStatusList_more').hide();
	$('#iframe').show();
}

function displayFacebookHome() {
	var criteria = {};
	criteria.ftype='home';
	var img = '<img src="' + fb_url + 'include/graph/icons/ajax-loader.gif" style="margin-bottom:10px;">';
	$('#'+fbStatusList).prepend(img);
	displayFeed(criteria);
}

function displayFacebookFeed() {
	var criteria = {};
	criteria.ftype='feed';
	var img = '<img src="' + fb_url + 'include/graph/icons/ajax-loader.gif" style="margin-bottom:10px;">';
	$('#'+fbStatusList).prepend(img);
	displayFeed(criteria);
}

function displayFacebookPosts() {
	var criteria = {};
	criteria.ftype='posts';
	var img = '<img src="' + fb_url + 'include/graph/icons/ajax-loader.gif" style="margin-bottom:10px;">';
	$('#'+fbStatusList).prepend(img);
	displayFeed(criteria);
}

function displayFeed(criteria) {
	var ftype = criteria.ftype;
	var append = criteria.append;
	
	$.ajax({
	  type: 'POST',
	  url: fb_url + 'process.php?q=facebookView',
	  dataType: 'json',
	  data: 'criteria=' + JSON.stringify(criteria),
	  success: function(msg){
	  	
	  	if(msg.previous_paging!='') $('body').data('previous_paging', msg.previous_paging);
	  	if(msg.next_paging!='') $('body').data('next_paging', msg.next_paging);
	  	
	  	if(append=='1') $('#'+fbStatusList).append(msg.display);
	  	else $('#'+fbStatusList).html(msg.display);
	  	
	  	if(msg.display=='' || msg.display==null) $('#fbDisplayMoreBtn').css('display','none');
	  	else $('#fbDisplayMoreBtn').css('display','block');
	  	
	  	$('.prettyDate').prettyDate();
	  }
	});
}

function displayFriendsPictures() {
	var criteria = '{"ftype":"12"}';
	var img = '<img src="' + fb_url + 'include/graph/icons/ajax-loader.gif" style="margin-bottom:10px;">';
	
	$('#fbDisplayMoreBtn').css('display','none');
	$('#'+fbStatusList).prepend(img);
	
	$.ajax({
	  type: 'POST',
	  url: fb_url + 'process.php?q=facebookView',
	  data: 'criteria=' + criteria,
	  success: function(msg){
	  	$('#'+fbStatusList).html(msg);
	  }
	});
}
