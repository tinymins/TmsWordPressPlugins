<?php
/*
Plugin Name: wp-hatmore-localpic
Plugin URI: http://hatmore.com
Description:1: 自动检测文章中是否存在远程图片,如果存在，就下载到本地，并更正图片src,相同的图片只下载一次
Version: 2.1
Author: tinymins
Author URI: http://zhaiyiming.com
*/
add_action('publish_post', 'newbody');

error_reporting(E_ERROR | E_PARSE);
function newbody($post_ID){
    remove_action('publish_post', 'newbody');
    $post = get_post($post_ID);
    $content = $post->post_content;
    # 匹配图片标签的正则表达式
    $sz_reg_img = '/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i';   
    $matches_img_tag = array();
    $matches_img_url = array();
    preg_match_all( $sz_reg_img, $content, $matches_img_tag );
    
    if( !empty($matches_img_tag) ) { $matches_img_url = array_unique($matches_img_tag[1]); }
    
    if( !empty($matches_img_url) ) {
        foreach( $matches_img_url as $k=>$v ) {
            # 过滤掉本站的资源if( preg_match('/^[^\'"]+(\.[a-z]{3,4})$/i', $v, $matches) ){ 
            if ( strpos($v,$_SERVER['HTTP_HOST'])!==false || !preg_match('/^[a-z]+:\/\/.*$/i', $v) ) { unset( $matches_img_url[$k] ); }
        }
    }
    
    require_once 'wp-hatmore-localpic.class.php';
    $img = new hatmore();
    $img->pid = $post_ID;
    $img->imgoldurl = $matches_img_url;
    
    if(!empty($img->imgoldurl)) {
        $img->upload();
        $tag = array_combine($img->imgoldurl, $img->imgnewurl);
        $new_content = strtr( $content, $tag );
        wp_update_post(array('ID' => $post_ID, 'post_content' => $new_content));//打印输出
    }
    add_action('publish_post', 'newbody');
}
?>