<?php
class hatmore{
    
    public  $pid = '';
    public  $imgoldurl=array();
    public  $imgnewurl=array();


    public function upload(){
        
        foreach ($this->imgoldurl as $v){
             $pkwall = array('user-agent'=>'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
             
             if(!preg_match('/^([^\'"]+)(\.[a-z]{3,4})$\b/i',$v)){ 
                 $v.='.png';
             }
             
	     $get = wp_remote_get($v,$pkwall);
	     $type = wp_remote_retrieve_header( $get, 'content-type' );
        
	     $mirror = wp_upload_bits(rawurldecode(basename($v)), '', wp_remote_retrieve_body( $get ) );
	
	     $attachment = array(
		  'post_title'=> basename( $v ),
		  'post_mime_type' => $type
		);	
	$attach_id = wp_insert_attachment( $attachment, $mirror['file'], $this->pid);
	$attach_data = wp_generate_attachment_metadata( $attach_id, $v );
	
	wp_update_attachment_metadata( $attach_id, $attach_data );
	set_post_thumbnail($this-pid, $attach_id);
	
        $this->imgnewurl[] = $mirror[url];
    }}
}
?>