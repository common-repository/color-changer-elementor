<?php
/**
 * The template for Settings.
 *
 * This is the template that edit form settings
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap ccel-wrap">
    <h1 class="wp-heading-inline"><?php echo __('Elementor Color Changer', 'color-changer-elementor'); ?></h1>
    <br /><br />
    <div class="ccel-body">
        <div class="postbox" style="display: block;">
            <div class="postbox-header">
                <h3><?php echo __('How this works?', 'color-changer-elementor'); ?></h3>
            </div>
            <div class="inside">

                <div class="ccel_multilingual-about">

                    <div class="ccel_multilingual-about-info">
                        <div class="top-content">
                            <p class="plugin-description">
                                <?php echo __('Plugin will autodetect colors used in elementor on different pages', 'color-changer-elementor'); ?>
                            </p>
                            <p class="plugin-description">
                                <?php echo __('Then you will be able to change this colors globally on all pages', 'color-changer-elementor'); ?>
                            </p>
                            <p class="plugin-description ccel-alert">
                                <?php echo __('Color changes are not reversable, so we strongly recomment that you make backup before such color changing!', 'color-changer-elementor'); ?>
                            </p>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <p class="ccel_colors-container">

        <?php foreach($color_changes as $source_color => $new_color): ?>

        <p class="alert alert-success"><span style="color:<?php echo esc_attr($source_color); ?>"><?php echo esc_html($source_color); ?></span> >> <span style="color:<?php echo esc_attr($new_color); ?>"><?php echo esc_html($new_color); ?></span></p>

        <?php endforeach;?>

        <form method="POST" action="<?php echo admin_url('admin.php?page=ccel'); ?>" novalidate="novalidate">
        <table class="wp-list-table widefat fixed striped table-view-list pages">
<?php

foreach($colors_detected as $color_source => $color_detected)
{
    echo '<tr>';
    echo '<td><div style="display:inline-block;width:20px;background-color:'.esc_attr($color_source).'">&nbsp;</div> '.esc_html($color_source).'</td>';
    echo '<td><div style="display:inline-block;">&nbsp;&nbsp;&nbsp;Change to:&nbsp;&nbsp;&nbsp;</div> </td> <td><input type="text" name="color_'.esc_attr($color_source).'"  value="'.esc_attr(substr($color_detected, 0, 7)).'" class="ccel-color-field" data-default-color="'.esc_attr(substr($color_detected, 0, 7)).'" /><textarea name="color_'.esc_attr($color_source).'_or" class="hidden">'.esc_html($color_source).'</textarea></td>';
    echo '</tr>';
}

?>
        </table>
<br />
        <input type="submit" class="button action" name="table_action" onclick="return getConfirmation();" value="Change colors">
</form>
        </p>
        
    </div>
</div>

<script>

jQuery(document).ready(function($){
    $('.ccel-color-field').wpColorPicker();
});

</script>

<?php 

wp_enqueue_style( 'wp-color-picker' ); 
wp_enqueue_script( 'ccel-script-handle', COLOR_CHANGER_ELEMENTORT_URL.'admin/js/script.js', array( 'wp-color-picker' ), false, true );?>

<style>




</style>

<?php $this->view('general/footer', $data); ?>