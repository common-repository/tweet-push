<?php
/*
Plugin Name: Tweet Push
Plugin URI: http://barisatasoy.com/
Description: Sends user registrations,comments and posts to your Twitter account.Super Simple!
Version: 0.1
Author: Barış Atasoy
Author URI: http://barisatasoy.com
*/

/*  Copyright 2009 Barış Atasoy  (email : b_atasoy@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'tweetpush', 'wp-content/plugins/'.$plugin_dir.'/languages', $plugin_dir.'/languages' ); 
function tweetpush($post_id)
{
$isok=get_option('tweetpush_publishnewpost');
if ($isok=='1') // WILL PUBLISH NEW POSTS THEN!
{	
global $wpdb;
 // GET THE POST TITLE,URL
$post_title=$_POST['post_title'];
$post_url=get_permalink($post_id);
// MAKE URL...THINY!
$tiny_url=getTinyUrl($post_url);
// CONSTRUCT TWITTER MESSAGE
$new_post_pre=get_option('tweetpush_newpostpre');
// TINYURL SIZE,POST PR E SIZE
	$size1=strlen($new_post_pre);$size2=strlen($tiny_url);
	
	$post_title_size_twitter=140-($size1+$size2);
	$post_title_size_real=strlen($post_title);

// SHORTEN POST TITLE IF NECESSARY
if ($post_title_size_real>$post_title_size_twitter)
	{
	$post_title_ps=utf8_str_splitter($post_title,$post_title_size_twitter-4);
	$post_title=$post_title_ps[0]."...";
	}
	
// CONSTRUCT FINAL FORMAT
$twitter_new_post=$new_post_pre.$post_title." ".$tiny_url;
// GET USER & PASS
$username=base64_decode(get_option('tweetpush_login'));
$password=base64_decode(get_option('tweetpush_password'));
// CHECK SEND CONDITIONS
// since save_post can fire twice and for added backwards and future compatibility,
// we simply dont rely on these hooks. We check posts database to determine posts' status.
$is_published=$wpdb->get_var("SELECT post_status FROM $wpdb->posts WHERE ID=$post_id");
if ($is_published=="publish") {send_to_twitter($twitter_new_post,$username,$password);}

}
}

function tweetpush_comment($comment_id,$comment_status)
{
 	$isok2=get_option('tweetpush_publishnewcomment');

if ($isok2=='1') // WILL PUBLISH NEW COMMENTS THEN!
{
global $wpdb;
// GET USER & PASS

$username=base64_decode(get_option('tweetpush_login'));
$password=base64_decode(get_option('tweetpush_password'));

// GET COMMENT DATA
$comment=$wpdb->get_row("SELECT comment_post_ID,comment_content,comment_approved FROM $wpdb->comments WHERE comment_ID=$comment_id");

if ($comment->comment_approved='1')
//if ($comment_status='approve')
{
// TINYURL LINK
$comment_link=getTinyUrl(get_comment_link($comment->comment_ID));
// GET COMMENT PRE
$comment_pre=get_option('tweetpush_newcommentpre');
// SHORTEN COMMENT CONTENT IF NECESSARY

	$size1=strlen($comment_pre);
	$size2=strlen($comment_link);
	
	$comment_size_twitter=140-($size1+$size2);
	$comment_size_real=strlen($comment->comment_content);

	
	if ($comment_size_real>$comment_size_twitter)
	{
	$comment_ps=utf8_str_splitter($comment->comment_content,$comment_size_twitter-4);
	$comment_body=$comment_ps[0]."...";
	}
	
// CONSTRUCT IT

$twitter_comment=$comment_pre.$comment_body." ".$comment_link;
send_to_twitter($twitter_comment,$username,$password);
}
}
}

function tweetpush_newuser($user_ID)
{
 	$isok3=get_option('tweetpush_publishnewuser');

if ($isok3=='1') // WILL PUBLISH NEW USERS THEN!
{
global $wpdb;

// GET USER & PASS
$username=base64_decode(get_option('tweetpush_login'));
$password=base64_decode(get_option('tweetpush_password'));

// GET USER DATA
$newuser=$wpdb->get_row("SELECT user_login,display_name FROM $wpdb->users WHERE ID=$user_ID");

$newuser_pre=get_option('tweetpush_newuserpre');
$newuser_name=$newuser->display_name;


if (get_option('tweetpush_publishnewuser_link')=='1')
{
// CONSTRUCT USER LINK
$u_url=get_bloginfo('url')."/author/".$newuser->user_login;
$newuser_profile_link=getTinyUrl($u_url);
}
else
{
$newuser_profile_link="";
}
$newuser_tweet=$newuser_pre.$newuser_name." ".$newuser_profile_link;
send_to_twitter($newuser_tweet,$username,$password);
}

}

// FUNCTIONS HERE

function getTinyUrl($url) {
    $tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=".$url);
    return $tinyurl;
}
function utf8_strlen2($str){return mb_strlen($str);}
function utf8_str_splitter($str, $split_len = 1) {if ( !preg_match('/^[0-9]+$/',$split_len) || $split_len < 1 ) {return FALSE;}
    $len = utf8_strlen($str);
    if ( $len <= $split_len ) {return array($str);}
    preg_match_all('/.{'.$split_len.'}|[^\x00]{1,'.$split_len.'}$/us', $str, $ar);
    return $ar[0];
}

function send_to_twitter($message,$username,$password)
{
$out="POST http://twitter.com/statuses/update.json HTTP/1.1\r\n"
  ."Host: twitter.com\r\n"
  ."Authorization: Basic ".base64_encode("$username:$password")."\r\n"
  ."Content-type: application/x-www-form-urlencoded\r\n"
  ."Content-length: ".strlen ("status=$message")."\r\n"
  ."Connection: Close\r\n\r\n"
  ."status=$message";

$fp = fsockopen ('twitter.com', 80);
fwrite ($fp, $out);
fclose ($fp);
}
// ADMIN PAGE FUNCTIONS
function tweetpush_admin() {
    if (function_exists('add_management_page')) {
		 add_management_page('Tweet Push', 'Tweet Push', 8, __FILE__, 'tweetpush_load_admin');
    }
 }

function tweetpush_load_admin() {
    include(dirname(__FILE__).'/tweet_push_manager.php');
}
add_action ( 'save_post', 'tweetpush');
add_action('admin_menu', 'tweetpush_admin');
add_action('wp_set_comment_status', 'tweetpush_comment');
add_action('user_register', 'tweetpush_newuser');
?>