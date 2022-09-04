<?php
class MK_Date_Posts_Widget extends WP_Widget {
    public function __construct() {
        $widget_options = array(
            'classname' => 'mk_date_posts_widget',
            'description' => esc_html__('Вывод постов с актуальной датой', 'mk-date-posts'),
        );
        parent::__construct( 'MK_Date_Posts_Widget', 'Записи с датой', $widget_options );
    }

    public function widget( $args, $instance ) {
        $custom_date = get_post_meta( get_the_ID(), '_custom_date_meta_key', false );
        
        $widget_posts_count = (int)$instance[ 'widget_posts_count' ];
        $widget_posts = get_posts( array ( 
            'meta_key' => '_mk_date_posts_meta_key',
            'meta_value'        => $custom_date,
            'meta_query' => array(
                'relation'  => 'AND',
                array(
                    'key'       => '_mk_date_posts_meta_key',
                    'value'     => date( 'Y-m-d' ),
                    'compare'   => '>',
                    'type'      => 'DATE'
                ),
            ),
            'numberposts' => $widget_posts_count,
        ));        

        echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; ?>
        <?php if($widget_posts) : ?>
        <h3><?php esc_html_e( 'Топ 5 новостей', 'mk-date-posts' ) ?></h3>
        <ul class="mk_date_posts_title_list">	
        <?php         
        foreach($widget_posts as $post) : 
            setup_postdata($post);
        ?>			
            <li>
                <span>
                    <h6 class='mk_date_posts_title'>
                        <a href="<?php the_permalink(); ?>" class="text-dark"><?php echo $post->post_title; ?></a>
                    </h6>
            </li>
            <?php endforeach; wp_reset_postdata(); endif; ?>
        </ol>        
        <?php echo $args['after_widget'];
    }

    public function form( $instance ) {
        $widget_posts_count = ! empty( $instance['widget_posts_count'] ) ? $instance['widget_posts_count'] : 0;
        ?>   
        <p>
            <p><label for="<?php echo $this->get_field_id( 'widget_posts_count' ); ?>">Posts count:</label></p>
            <input class="small-text" type="number" id="<?php echo $this->get_field_id( 'widget_posts_count' ); ?>"
                name="<?php echo $this->get_field_name( 'widget_posts_count' ); ?>" value="<?php echo esc_attr( $widget_posts_count ); ?>" /><br>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'widget_posts_count' ] = strip_tags( $new_instance[ 'widget_posts_count' ] );

        return $instance;
    }
}