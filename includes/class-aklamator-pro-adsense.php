<?php

class AklamatorWidgetPro
{


    private static $instance = null;

    /**
     * Get singleton instance
     */
    public static function init()
    {

        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public $aklamator_url;
    public $api_data;
    public $api_data_table;

    public function __construct()
    {

        $this->aklamator_url = "https://aklamator.com/";
        $this->application_id = get_option('aklamatorProApplicationID');

        $this->hooks();

    }


    private function hooks(){

        add_filter( 'plugin_row_meta', array($this, 'aklamatorPro_plugin_meta_links'), 10, 2);
        add_filter( "plugin_action_links_".AKLA_PRO_PLUGIN_NAME, array($this, 'aklamatorPro_plugin_settings_link') );

        add_action( 'admin_menu', array($this, "adminMenu") );
        add_action( 'admin_init', array($this, "setOptions") );
        add_action( 'admin_enqueue_scripts', array($this, 'load_custom_wp_admin_style_script') );
        add_action( 'after_setup_theme', array($this, 'vw_setup_vw_widgets_init_aklamatorPro') );

        if ($this->application_id != "")
            add_filter('the_content', array($this, 'bottom_of_every_postPro'));

        /*
        * Adds featured images from posts to your site's RSS feed output,
        */
        if(get_option('aklamatorProFeatured2Feed')) {
            add_filter('the_excerpt_rss', 'akla_pro_featured_images_in_rss', 1000, 1);
            add_filter('the_content_feed', 'akla_pro_featured_images_in_rss', 1000, 1);
        }

    }

    function setOptions()
    {
        register_setting('aklamatorPro-options', 'aklamatorProApplicationID');
        register_setting('aklamatorPro-options', 'aklamatorProPoweredBy');
        register_setting('aklamatorPro-options', 'aklamatorProFeatured2Feed');
        register_setting('aklamatorPro-options', 'aklamatorProSingleWidgetID');
        register_setting('aklamatorPro-options', 'aklamatorProPageWidgetID');
        register_setting('aklamatorPro-options', 'aklamatorProSingleWidgetTitle');
        register_setting('aklamatorPro-options', 'aklamatorPRAdsenseCategory');
        // Ads codes
        register_setting('aklamatorPro-options', 'aklamatorProAds');
        register_setting('aklamatorPro-options', 'aklamatorProAds2');
        register_setting('aklamatorPro-options', 'aklamatorProAds3');
        // Custom ads name
        register_setting('aklamatorPro-options', 'aklamatorProAds1Name');
        register_setting('aklamatorPro-options', 'aklamatorProAds2Name');
        register_setting('aklamatorPro-options', 'aklamatorProAds3Name');

    }

    /*
     * Activation Hook
     */

    function set_up_optionsPro(){
        add_option('aklamatorProApplicationID', '');
        add_option('aklamatorProPoweredBy', '');
        add_option('aklamatorProFeatured2Feed', 'on');
        add_option('aklamatorProSingleWidgetID', '');
        add_option('aklamatorProPageWidgetID', '');
        add_option('aklamatorProSingleWidgetTitle', '');
        add_option('aklamatorPRAdsenseCategory', '');

        // Ads codes
        add_option('aklamatorProAds', '');
        add_option('aklamatorProAds2', '');
        add_option('aklamatorProAds3', '');
        add_option('aklamatorWidgetsPro', '');

        // Custom Ads names
        add_option('aklamatorProAds1Name', '');
        add_option('aklamatorProAds2Name', '');
        add_option('aklamatorProAds3Name', '');
    }

    /*
     * Uninstall Hook
     */

    function aklamatorPro_uninstall() {
        delete_option('aklamatorProApplicationID');
        delete_option('aklamatorProPoweredBy');
        delete_option('aklamatorProFeatured2Feed');
        delete_option('aklamatorProSingleWidgetID');
        delete_option('aklamatorProPageWidgetID');
        delete_option('aklamatorProSingleWidgetTitle');
        delete_option('aklamatorPRAdsenseCategory');
        // Ads codes
        delete_option('aklamatorProAds');
        delete_option('aklamatorProAds2');
        delete_option('aklamatorProAds3');
        delete_option('aklamatorWidgetsPro');
        // Custom Ad names
        delete_option('aklamatorProAds1Name');
        delete_option('aklamatorProAds2Name');
        delete_option('aklamatorProAds3Name');

    }
    public function adminMenu() {
        add_menu_page('Aklamator Digital PR', 'Aklamator PR Pro', 'manage_options', 'aklamator-pro-adsense', array($this, 'createAdminPage'), AKLA_PRO_PLUGIN_URL. 'images/aklamator-icon.png');

    }

    function load_custom_wp_admin_style_script($hook) {

        if ( 'toplevel_page_aklamator-pro-adsense' != $hook ) {
            return;
        }

        /*
         * We are calling api only when we at this plugin page, not for all other pages
         */

        if ($this->application_id !== '') {


            $this->api_data_table = $this->addNewWebsiteApi();  // Fetch data via aklamator API
            
            $this->api_data = unserialize(serialize($this->api_data_table));

        }

        $this->populate_with_defaults();

        if($this->api_data->data){
            update_option('aklamatorWidgetsPro', $this->api_data); // Update widgets data "Appearance->widgets"
        }else{
            update_option('aklamatorWidgetsPro', array()); // Reset
        }


        // Load necessary css files
        wp_enqueue_style('custom-wp-admin', AKLA_PRO_PLUGIN_URL . 'assets/css/admin-style.css', false, '1.0.0' );
        wp_enqueue_style('dataTables-plugin', AKLA_PRO_PLUGIN_URL . 'assets/dataTables/jquery.dataTables.min.css', false, '1.10.5', false );

        // Load script files
        wp_enqueue_script('dataTables_plugin', AKLA_PRO_PLUGIN_URL . 'assets/dataTables/jquery.dataTables.min.js', array('jquery'), '1.10.5', true );
        wp_register_script('my_custom_akla_script', AKLA_PRO_PLUGIN_URL . 'assets/js/main.js', array('jquery'), '1.0', true);

        $data = array(
            'site_url' => $this->aklamator_url
        );
        wp_localize_script('my_custom_akla_script', 'akla_vars', $data);
        wp_enqueue_script('my_custom_akla_script');

    }

    private function populate_with_defaults()
    {
        /* Add new items to the end of array data*/


        if (get_option('aklamatorProAds') !== '') {

            if (get_option('aklamatorProAds1Name') != "") {
                $title = get_option('aklamatorProAds1Name');
            } else {
                $title = 'Ad 1 code';
            }

            $this->api_data->data[] = (object)array('title' => $title, 'uniq_name' => stripslashes(htmlspecialchars_decode(get_option('aklamatorProAds'))));
        }
        if (get_option('aklamatorProAds2') !== '') {

            if (get_option('aklamatorProAds2Name') != "") {
                $title = get_option('aklamatorProAds2Name');
            } else {
                $title = 'Ad 2 code';
            }
            $this->api_data->data[] = (object)array('title' => $title, 'uniq_name' => stripslashes(htmlspecialchars_decode(get_option('aklamatorProAds2'))));
        }
        if (get_option('aklamatorProAds3') !== '') {

            if (get_option('aklamatorProAds3Name') != "") {
                $title = get_option('aklamatorProAds3Name');
            } else {
                $title = 'Ad 3 code';
            }
            $this->api_data->data[] = (object)array('title' => $title, 'uniq_name' => stripslashes(htmlspecialchars_decode(get_option('aklamatorProAds3'))));

        }

        $this->api_data->data[] = (object)array('title' => 'Do not show', 'uniq_name' => 'none');


        if(isset($this->api_data->data) && isset($this->api_data->flag) && $this->api_data->flag) {

            if (get_option('aklamatorProSingleWidgetID') !== 'none') {

                if (get_option('aklamatorProSingleWidgetID') == '') {
                    if ($this->api_data->data[0] && $this->api_data->data[0]->uniq_name != 'none') {
                        update_option('aklamatorProSingleWidgetID', $this->api_data->data[0]->uniq_name);
                    }
                }
            }


            if (get_option('aklamatorProPageWidgetID') != 'none') {

                if (get_option('aklamatorProPageWidgetID') == '') {
                    if ($this->api_data->data[0] && $this->api_data->data[0]->uniq_name != 'none') {
                        update_option('aklamatorProPageWidgetID', $this->api_data->data[0]->uniq_name);
                    }
                }
            }
        }

    }

    public function getSignupUrl()
    {
        $user_info =  wp_get_current_user();

        return $this->aklamator_url . 'login/application_id?utm_source=wordpress&utm_medium=wpadsense&e=' . urlencode(get_option('admin_email')) .
        '&pub=' .  preg_replace('/^www\./','',$_SERVER['SERVER_NAME']).
        '&un=' . urlencode($user_info->user_login). '&fn=' . urlencode($user_info->user_firstname) . '&ln=' . urlencode($user_info->user_lastname) .
        '&pl=pro-adsense&return_uri=' . admin_url("admin.php?page=aklamator-pro-adsense");

    }

    private function addNewWebsiteApi()
    {

        if (!is_callable('curl_init')) {
            return;
        }

        $service     = $this->aklamator_url . "wp-authenticate/user";
        $p['ip']     = $_SERVER['REMOTE_ADDR'];
        $p['domain'] = site_url();
        $p['source'] = "wordpress";
        $p['AklamatorApplicationID'] = $this->application_id;
        
        $aklamatorfeedAppend = "";
        if(get_option('aklamatorPRAdsenseCategory') != -1 && get_option('aklamatorPRAdsenseCategory') != "")
        {
            $aklamatorfeedAppend = '&cat=' . get_option('aklamatorPRAdsenseCategory');
        }
        $p['aklamatorfeedURL'] = site_url() . '?feed=rss2' . $aklamatorfeedAppend;


        $client = curl_init();

        curl_setopt($client, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($client, CURLOPT_HEADER, 0);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_URL, $service);

        if (!empty($p)) {
            curl_setopt($client, CURLOPT_POST, count($p));
            curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($p));
        }

        $data = curl_exec($client);

        if (curl_error($client)!="") {
            $this->curlfailovao=1;
        } else {
            $this->curlfailovao=0;
        }
        curl_close($client);

        $data = json_decode($data);

        return $data;

    }

    function bottom_of_every_postPro($content){


        /*  we want to change `the_content` of posts, not pages
            and the text file must exist for this to work */

        if (is_single()) {
            $widget_id = get_option('aklamatorProSingleWidgetID');
            if($widget_id == "none")
                return $content;
        } elseif (is_page()) {
            $widget_id = get_option('aklamatorProPageWidgetID');
            if($widget_id == "none")
                return $content;

        } else {

            /*  if `the_content` belongs to a page or our file is missing
                the result of this filter is no change to `the_content` */

            return $content;
        }

        $title = "";
        if (get_option('aklamatorProSingleWidgetTitle') !== '') {

            $title .= "<h2>" . get_option('aklamatorProSingleWidgetTitle') . "</h2>";
        }
        if (strlen($widget_id) > 7) {
            return $content . $title . stripslashes(htmlspecialchars_decode($widget_id)) . "</br>";

        } else if(strlen($widget_id) == 7) {
            /*  append the text file contents to the end of `the_content` */
            return $content . $title . $this->show_widget($widget_id);
        }else
            return $content;

    }

    public function show_widget($widget_id){

        $code  = '<!-- Start Aklamator Widget -->';
        $code .= '<div id="akla'.$widget_id.'"></div>';
        $code .= '<script>(function(d, s, id) ';
        $code .= '{ var js, fjs = d.getElementsByTagName(s)[0];';
        $code .= 'if (d.getElementById(id)) return;';
        $code .= 'js = d.createElement(s); js.id = id;';
        $code .= 'js.src = "'.$this->aklamator_url.'widget/'.$widget_id.'";';
        $code .= 'fjs.parentNode.insertBefore(js, fjs);';
        $code .= '}(document, \'script\', \'aklamator-'.$widget_id.'\'))</script>';
        $code .= '<!-- end -->';
        return $code;

    }

    /*
     * Adds featured images from posts to your site's RSS feed output,
     */

    function akla_pro_featured_images_in_rss($content){
        global $post;
        if (has_post_thumbnail($post->ID)) {
            $featured_images_in_rss_size = 'thumbnail';
            $featured_images_in_rss_css_code = 'display: block; margin-bottom: 5px; clear:both;';
            $content = get_the_post_thumbnail($post->ID, $featured_images_in_rss_size, array('style' => $featured_images_in_rss_css_code)) . $content;
        }
        return $content;
    }

    /*
     * Add rate and review link in plugin section
     */
    function aklamatorPro_plugin_meta_links($links, $file) {
        $plugin = AKLA_PRO_PLUGIN_NAME;
        // create link
        if ($file == $plugin) {
            return array_merge(
                $links,
                array('<a href="https://wordpress.org/support/plugin/aklamator-pro-adsense/reviews" target=_blank>Please rate and review</a>')
            );
        }
        return $links;
    }

    /*
     * Add setting link on plugin page
     */
    function aklamatorPro_plugin_settings_link($links) {
        $settings_link = '<a href="admin.php?page=aklamator-pro-adsense">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    //Init widget section
    function vw_setup_vw_widgets_init_aklamatorPro() {
        add_action( 'widgets_init', array($this, 'vw_widgets_init_aklamatorPro' ));
    }

    function vw_widgets_init_aklamatorPro() {
        register_widget( 'Wp_widget_aklamatorPro' );
    }

    public function createAdminPage() {
        require_once AKLA_PRO_PLUGIN_DIR ."views/admin-page.php";
    }


}

