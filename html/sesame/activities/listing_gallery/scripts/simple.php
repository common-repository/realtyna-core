<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>

<script type="text/javascript">
    
        function wpl_load_gallery(id) {
            
            var loader = Realtyna.ajaxLoader.show(wplj('#wpl_gallery_container'+id), 'normal', 'center', true, '#fff', 3, {
                backgroundColor: 'rgba(255,255,255,0)'
            });
            
            var regim_id =  jQuery(this).data('id');
            
            var data = {"id":id,"action":"load_gallery_properties",'secure_nonce':'<?php echo wp_create_nonce( "secure_nonce" ); ?>'};
            
            jQuery.ajax({
                url : '<?php echo admin_url( "admin-ajax.php" ); ?>',
                type : 'POST',
                data : data,
                success : function( html ) {
                    jQuery("#wpl_gallery_container"+id).html(html);
                    Realtyna.ajaxLoader.hide(loader);
                },
                fail : function( err ) {
                    console.log('Erorr!');
                }
            });
      
            return false;
        }

</script>
