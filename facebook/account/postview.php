<?php
include('../include/webzone.php');
include('include/presentation/header.php');
?>
<link rel="stylesheet" href="<?php echo $GLOBALS['app_url']; ?>/include/libs/jquery.mobile-1.1.0.min.css" />
<style type="text/css">
input {
	float:right;
	display: block;
	margin: .5em 5px;
	-moz-box-shadow: 0 1px 4px rgba(0,0,0,.3);
	-webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, .3);
	box-shadow: 0 1px 4px rgba(0, 0, 0, .3);
	text-align: center;
	cursor: pointer;
	position: relative;
	-webkit-background-clip: padding-box;
	-moz-background-clip: padding;
	background-clip: padding-box;
	-moz-border-radius: 1em;
	-webkit-border-radius: 1em;
	border-radius: 1em;
	font-family: Helvetica,Arial,sans-serif;
	text-decoration: none;
	border: 1px solid #F2C43D;
	background: #FCEDA7;
	font-weight: bold;
	color: #111;
	text-shadow: 0 1px 0 white;
	background-image: -webkit-gradient(linear,left top,left bottom,from(#F8D94C),to(#FADB4E));
	background-image: -webkit-linear-gradient(#F8D94C,#FADB4E); 
	background-image: -moz-linear-gradient(#F8D94C,#FADB4E);
	background-image: -ms-linear-gradient(#F8D94C,#FADB4E);
	background-image: -o-linear-gradient(#F8D94C,#FADB4E);
	background-image: linear-gradient(#F8D94C,#FADB4E);
	font-size: 17px;
	padding: .55em 40px .5em;
	min-width: .75em;
	display: block;
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap;
	position: relative;
	zoom: 1;
}
</style>
<script type='text/javascript'>
function showNormalView() {
	window.parent.$('div.ui-navbar').show();
	window.parent.$('div#fbStatusList').show();
	window.parent.$('div#fbStatusList_more').show();
	window.parent.$('#iframe').hide();
}
</script>

<form style="width: 290px; height: 300px; margin: auto;" action="post.php" method="post">
  <textarea style="width: 100%; height: 120px;" name="post" placeholder="Share somemthing on Facebook..." required autofocus></textarea>
  <div style="width: 100%">
  	  <input type="submit" value="Post" />
	  <a onclick="showNormalView()" style="float:right;" class="ui-btn ui-btn-corner-all ui-shadow ui-btn-down-c ui-btn-up-c"><span class="ui-btn-inner ui-btn-corner-all"><span class="ui-btn-text">Cancel</span></span></a>
  </div>
</form>

<?php
$f1 = new Facebook_class();
$f1->loadJsSDK();
include('include/presentation/footer.php');
?>
