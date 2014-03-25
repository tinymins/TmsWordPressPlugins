<?php
/*
Plugin Name: wp-hatmore-localpic
Plugin URI: http://hatmore.com
Description:1: 自动检测文章中是否存在远程图片,如果存在，就下载到本地，并更正图片src,相同的图片只下载一次
Version: 2.1
Author: if
Author URI: http://hatmore.com
*/
add_action('publish_post', 'newbody');

function newbody($post_ID){
    $reg= '/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i';   
    $temp1 =array();
    $temp2= array();
    remove_action('publish_post', 'newbody');
    $body = get_post($post_ID);
    preg_match_all($reg,$body->post_content,$temp1);
    
    if(!empty($temp1)) $temp2= array_unique($temp1[1]);
  
    if(!empty($temp2)) {
        
        foreach($temp2 as $k=>$v){  
            if (!strpos($v,$_SERVER['HTTP_HOST'])===false) unset($temp2[$k]);
        }
    }
    
    require_once 'wp-hatmore-localpic.class.php';
    $img = new hatmore();
    $img->pid = $post_ID;
    $img->imgoldurl = $temp2;
    
    if(!empty($img->imgoldurl)) {               
        $img->upload();
        $tag = array_combine($img->imgoldurl, $img->imgnewurl);
        $newbody = strtr($body->post_content,$tag);
        wp_update_post(array('ID' => $post_ID, 'post_content' => $newbody));//打印输出
  }
     add_action('publish_post', 'newbody');
}
?>