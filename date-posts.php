<?php 
/**
 * Plugin Name: MK Date Posts
 * Description: Adds a date field and shows posts with the actual date in the sitebar
 * Author URI:  https://telegram.im/@Arsama_mk
 * Author:      Mamikon
 * Text Domain: mk-date-posts
 * Requires PHP: 5.4
 * Requires at least: 4.0
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version:     1.0
 */

require_once( plugin_dir_path( __FILE__ ) . 'class-mk-date-posts-widget.php' );

register_activation_hook( __FILE__, 'mk_date_posts_plugin_activate' );

add_action( 'add_meta_boxes', 'mk_date_posts_post_meta_box' );

function mk_date_posts_post_meta_box() {
    add_meta_box(
        'mk_date_posts_post_meta_box',
        esc_html__('Выберите дату поста', 'mk-date-posts'),
        'mk_date_posts_post_meta_box_cb',
        'post'
    );
}

function mk_date_posts_post_meta_box_cb( $post_obj ) {
    wp_nonce_field( 'mk_date_posts_action', 'mk_date_posts_noncename' );
    $custom_date = get_post_meta( $post_obj->ID, '_mk_date_posts_meta_key', true );
    ?>

    <table class="meta-table" style="border-collapse: collapse; width:35%; text-align: center">
        <tbody>
            <tr>
                <th>
                    <label for="mk_date_posts_meta_key">
                       <?php esc_html_e( 'Дата поста', 'mk-date-posts' ); ?>
                    </label>    
                    <td>
                        <input name="mk_date_posts_meta_key" type="date" value="<?php echo esc_attr( $custom_date ); ?>">
                    </td>                   
                </th>
            </tr>
        </tbody>
    </table>
<?php }

function mk_date_posts_post_meta_box_save( $post_id ) {

   if ( ! isset( $_POST['mk_date_posts_noncename'] ) && ! wp_verify_nonce( 'mk_date_posts_noncename', 'mk_date_posts_action' ) ) {
       return;
   }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }	

    if( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['mk_date_posts_meta_key'] ) && $_POST['mk_date_posts_meta_key'] !== '' ) {
       update_post_meta(
          $post_id,
          '_mk_date_posts_meta_key',
          $_POST['mk_date_posts_meta_key']
       );
    }

    else {
        delete_post_meta( $post_id, '_mk_date_posts_meta_key' );
    }
 }
 
add_action( 'save_post', 'mk_date_posts_post_meta_box_save' );

function mk_date_posts_sidebar() {
	register_widget( 'MK_Date_Posts_Widget' );
}
 
add_action( 'widgets_init', 'mk_date_posts_sidebar' );