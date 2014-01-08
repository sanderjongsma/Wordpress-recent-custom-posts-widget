<?php

class Widget extends WP_Widget {

    public function __construct() 
    {
        parent::__construct(
            'recent_posts', // Base ID
            'Recent Posts', // Name
            array( 'description' => __( 'Recent custom post types', 'scaffold' ), 
        ));
    }

    public function widget( $args, $instance ) 
    {
        extract( $args );

        $title = apply_filters( 'widget_title', $instance['title'] );
        $all = $instance['all'];
        $all_url = $instance['all_url'];

        $query = new WP_Query( array(
            'post_type'     => $instance['recent_post_type'],
            'showposts'     => $instance['number'],
            'nopaging'      => 0,
            'post_status'   => 'publish'
        ));

        if ( $query->have_posts() ) : ?>

            <?php echo $before_widget; ?>

            <?php if ( $title ) echo $before_title . $title . $after_title; ?>
                <ul>
                    <?php  while ( $query->have_posts() ) : $query->the_post(); ?>
                        <li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
                    <?php endwhile; ?>
                    <?php if ( !empty($all) || !empty($all_url) ) : ?>
                        <li><a href=<?php echo $all_url; ?>><?php echo $all; ?></a></li>
                    <?php endif; ?>
                </ul>

            <?php echo $after_widget;

            wp_reset_postdata();

        endif;
    }

    public function update( $new_instance, $old_instance ) 
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['recent_post_type'] = $new_instance['recent_post_type'];
        $instance['number'] = (int) $new_instance['number'];
        $instance['all'] = strip_tags( $new_instance['all'] );
        $instance['all_url'] = strip_tags( $new_instance['all_url'] );

        return $instance;
    }

    public function form( $instance )
    {
        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'New title', 'text_domain' );
        $recent_post_type = isset( $instance[ 'recent_post_type' ] ) ? $instance[ 'recent_post_type' ] : '';
        $all = isset( $instance[ 'all' ] ) ? $instance[ 'all' ] : __( 'View all', 'text_domain' );
        $all_url = isset( $instance[ 'all_url' ] ) ? $instance[ 'all_url' ] : __( 'Url', 'text_domain' );

        if ( !isset( $instance['number'] ) || !$number = (int) $instance['number'] ) $number = 5;

        $post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' ); ?>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('recent_post_type'); ?>">Post type:</label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'recent_post_type' ); ?>" name="<?php echo $this->get_field_name( 'recent_post_type' ); ?>"> 
                <?php foreach ( $post_types as $post_type ) {
                    echo '<option value="' . $post_type->name . '"' . ( $post_type->name == $recent_post_type ? ' selected="selected"' : '' ) . '>' . $post_type->labels->name . "</option>";
                } ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>">Number of posts: </label>
            <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'all' ); ?>">View all title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'all' ); ?>" name="<?php echo $this->get_field_name( 'all' ); ?>" type="text" value="<?php echo esc_attr( $all ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'all_url' ); ?>">View all url:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'all_url' ); ?>" name="<?php echo $this->get_field_name( 'all_url' ); ?>" type="text" value="<?php echo esc_attr( $all_url ); ?>" />
        </p>
                       
        <?php 
    }

}
