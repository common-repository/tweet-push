<style>
.fields {width:500px;}
.field_left {width:200px;float:left;font-size:15px;margin-top:10px}
.field_right {width:200px;padding-left:20px;float:left;margin-top:10px}
.clear {clear:both}
</style>
<?php

	if ($_POST['submit-type'] == 'login'){
		//UPDATE LOGIN
		if(($_POST['twitterlogin'] != '') AND ($_POST['twitterpass'] != '')){
			update_option('tweetpush_login', base64_encode($_POST['twitterlogin']));
			update_option('tweetpush_password', base64_encode($_POST['twitterpass']));

		}else{
			echo("<div style='border:1px solid red; padding:20px; margin:20px; color:red;'>You need to provide your twitter login and password!</div>");
		}
	}
	
if ($_POST['submit-type'] == 'options')
	{
	$post_pre=$_POST['pre-new-post'];
	update_option('tweetpush_newpostpre',$post_pre);
	 
	$comment_pre=$_POST['pre-new-comment'];
	update_option('tweetpush_newcommentpre',$comment_pre);
	
	$user_pre=$_POST['pre-new-user'];
	update_option('tweetpush_newuserpre',$user_pre);
	
	$publish_posts=$_POST['publish_new_post']; if ($publish_posts=='1') { update_option('tweetpush_publishnewpost','1'); } else { update_option('tweetpush_publishnewpost','0'); }
	$publish_comments=$_POST['publish_new_comment']; if ($publish_comments=='1') { update_option('tweetpush_publishnewcomment','1'); } else { update_option('tweetpush_publishnewcomment','0'); }
	$publish_new_user=$_POST['publish_new_user']; if ($publish_new_user=='1') { update_option('tweetpush_publishnewuser','1'); } else { update_option('tweetpush_publishnewuser','0'); }
	
	$publish_new_user2=$_POST['publish_new_user2']; if ($publish_new_user2=='1') { update_option('tweetpush_publishnewuser_link','1'); } else { update_option('tweetpush_publishnewuser_link','0'); }
	//echo $publish_posts;
	}
?>
<div class="wrap">
	<h2><?php _e('Tweet Push', tweetpush); ?> </h2>

<div class="wrap">

	
	<form method="post" >
	<div class="fields">
	 	<div class="field_left"><label for="twitterlogin"><?php _e('Twitter Login:', tweetpush); ?></label></div>
		<div class="field_right"><input type="text" name="twitterlogin" id="twitterlogin" value="<?php echo base64_decode(get_option('tweetpush_login')) ?>" /></div>
		 		 
		<div class="field_left"><label for="twitterpass"><?php _e('Twitter Password:', tweetpush); ?></label></div>
		<div class="field_right"><input type="password" name="twitterpass" id="twitterpass" value="" /></div>
		 
		<input type="hidden" name="submit-type" value="login">
		<p class="submit"><input type="submit" name="submit" value="<?php _e('Save Credentials',tweetpush); ?>" /></p>
		
	</div>	
	</div>
	</form>
<form method="post">


<fieldset>
<p>
<input type="checkbox" name="publish_new_post" id="publish_new_post" value="1" <?php if(get_option("tweetpush_publishnewpost")=='1'){ echo "checked=\"true\""; }?> />
<label for="new_post"><?php _e('Send tweet to your Tweeter account when you publish a new post', tweetpush); ?></label>
</p>
</fieldset>


<fieldset>
<p>
<input type="checkbox" name="publish_new_comment" id="publish_new_comment" value="1" <?php if(get_option("tweetpush_publishnewcomment")=='1'){ echo "checked=\"true\""; }?> />
<label for="new_post"><?php _e('Send tweet to your Tweeter account when comments approved', tweetpush); ?></label>
</p>
</fieldset>


<fieldset>
<p>
<input type="checkbox" name="publish_new_user" id="publish_new_user" value="1" <?php if(get_option("tweetpush_publishnewuser")=='1'){ echo "checked=\"true\""; }?> />				<label for="new_post"><?php _e('Send tweet to your Tweeter account when new user registers', tweetpush); ?></label>
</p>
</fieldset>

<fieldeset>
<p>
<input type="checkbox" name="publish_new_user2" id="publish_new_user2" value="1" <?php if(get_option("tweetpush_publishnewuser_link")=='1'){ echo "checked=\"true\""; }?> />
<label for="new_post"><?php _e('Send user profile link,too (Default:http//yoursite/author/USER)', tweetpush); ?></label>
</p>
</fieldset>





<fieldset>
<p>
<label for="new_post"><?php _e('Pre - text for new post: (e.g: New Post: Your post Title; Max 20 chars)', tweetpush); ?></label><br />
<input type="text" name="pre-new-post" id="pre-new-post" size="60" maxlength="20" value="<?php echo(get_option('tweetpush_newpostpre')) ?>" />
&nbsp;&nbsp;
</p>
</fieldset>


<fieldset>
<p>
<label for="new_post"><?php _e('Pre - text for new comment: (e.g: New Comment: Comment Summary; Max 20 chars)', tweetpush); ?></label><br />
<input type="text" name="pre-new-comment" id="pre-new-comment" size="60" maxlength="20" value="<?php echo(get_option('tweetpush_newcommentpre')) ?>" />
&nbsp;&nbsp;
</p>
</fieldset>
<fieldset>
<p>
<label for="new_user"><?php _e('Pre - text for new user: (e.g: New User: USERNAME_HERE; Max 20 chars)', tweetpush); ?></label><br />
<input type="text" name="pre-new-user" id="pre-new-user" size="60" maxlength="20" value="<?php echo(get_option('tweetpush_newuserpre')) ?>" />
&nbsp;&nbsp;
</p>
</fieldset>
	
			
					
		<input type="hidden" name="submit-type" value="options">
		<p class="submit"><input type="submit" name="submit" value="<?php _e('Update Options',tweetpush); ?>" /></p>
		
</div>
</div>