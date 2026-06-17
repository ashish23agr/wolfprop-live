jQuery(document).ready(function(){
	jQuery("#mobileselect").select2();
    jQuery('#selectall').click(function(){
        if(jQuery(this).is(':checked')){
            jQuery('table.wp-list-table.widefat.fixed.striped.posts tr td .on-all-select').attr('checked',true);
        }else{
            jQuery('table.wp-list-table.widefat.fixed.striped.posts tr td .on-all-select').attr('checked',false);
        }
    });
});

