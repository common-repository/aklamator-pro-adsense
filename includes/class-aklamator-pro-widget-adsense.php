<?php
class Wp_widget_aklamatorPro extends WP_Widget {

    private $default = array(
        'supertitle' => '',
        'title' => '',
        'content' => '',
    );

    public $widget_data;

    public function __construct() {
        // widget actual processes
        parent::__construct(
            'wp_widget_aklamatorPro', // Base ID
            'Aklamator Digital PR Pro', // Name
            array( 'description' => __( 'Display Aklamator Widgets in Sidebar')) // Args
        );

        $this->widget_data = get_option('aklamatorWidgetsPro');

    }

    function widget( $args, $instance ) {
        extract($args);
        if($instance['widget_id'] == '' || $instance['widget_id'] == 'none')
            return;

        $supertitle_html = '';
        if ( ! empty( $instance['supertitle'] ) ) {
            $supertitle_html = sprintf( __( '<span class="super-title">%s</span>', 'envirra' ), $instance['supertitle'] );
        }

        $title_html = '';
        if ( ! empty( $instance['title'] ) ) {
            $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
            $title_html = $supertitle_html.$title;
        }

        echo $before_widget;
        if ( $instance['title'] ) echo $before_title . $title_html . $after_title;


        if (strlen($instance['widget_id']) > 7)
            echo $instance['widget_id'];
        else
            echo AklamatorWidgetPro::init()->show_widget(do_shortcode($instance['widget_id']));


        echo $after_widget;
    }


    function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, $this->default );

        $supertitle = strip_tags( $instance['supertitle'] );
        $title = strip_tags( $instance['title'] );
        $content = $instance['content'];
        $widget_id = isset($instance['widget_id'])?$instance['widget_id']:"";


        if(!empty($this->widget_data) || ($this->widget_data->flag && !empty($this->widget_data->data))): ?>

            <!-- title -->
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (text shown above widget):','envirra-backend'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>

            <!-- Select - dropdown -->
            <label for="<?php echo $this->get_field_id('widget_id'); ?>"><?php _e('Widget:','envirra-backend'); ?></label>
            <select id="<?php echo $this->get_field_id('widget_id'); ?>" name="<?php echo $this->get_field_name('widget_id'); ?>">
                <?php foreach ( $this->widget_data->data as $item ): ?>
                    <option <?php echo ($widget_id == stripslashes(htmlspecialchars_decode($item->uniq_name)))? 'selected="selected"' : '' ;?> value="<?php echo addslashes(htmlspecialchars($item->uniq_name)); ?>"><?php echo $item->title; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <br>
            <br>
        <?php else :?>
            <br>
            <span style="color:red">Please make sure that you configured Aklamator plugin correctly</span>
            <a href="<?php echo admin_url(); ?>admin.php?page=aklamator-pro-adsense">Click here to configure Aklamator plugin</a>
            <br>
            <br>
        <?php endif;

    }
}