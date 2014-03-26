<?php
class hatmore{
    public  $pid = '';
    public  $imgoldurl=array();
    public  $imgnewurl=array();
    public function upload(){
        foreach ( $this->imgoldurl as $v ){
            $pkwall = array('user-agent'=>'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
            
            $file_ext_name = '.png';
            $matches = array();
            if( preg_match('/^[^\'"]+(\.[a-z]{3,4})$/i', $v, $matches) ){ 
                $file_ext_name = $matches[1];
            }
            
            $response = wp_remote_get( $v, $pkwall );
            $response_mime = wp_remote_retrieve_header( $response, 'content-type' );
            
            // $upload = wp_upload_bits(rawurldecode(basename($v)), '', wp_remote_retrieve_body( $response ) );
            $upload = wp_upload_bits( date("YmdHis").mt_rand(1000,9999).$file_ext_name, null, wp_remote_retrieve_body( $response ) );
            
            $attachment = array(
                'post_title'=> basename( $v ),
                'post_mime_type' => $response_mime
            );
            $attach_id = wp_insert_attachment( $attachment, $upload['file'], $this->pid );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $v );
            
            wp_update_attachment_metadata( $attach_id, $attach_data );
            # 如果没有缩略图则设置当前图片为文章缩略图。（文章第一张图片）
            if(!has_post_thumbnail($this->pid)) { set_post_thumbnail($this->pid, $attach_id); }
        
            $this->imgnewurl[] = $upload['url'];
        }
    }
}
?>