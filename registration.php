<?php

/**
 * Plugin Name:       registration
 * Plugin URI:        https:milankumarchaudhary.com.np
 * Description:       Create Register Table and List of Registers
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Milan Kumar Chaudhary
 * Author URI:        https:milankumarchaudhary.com.np
 */
defined('ABSPATH') or die('error!! You cant access');

class registration
{
    /**
     * Constructor of the class
     * 
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'registration_menu'));
        add_filter('get_user_name_filter', array($this, 'extract_user_name'), 10, 1);

        //for fontend
        add_action('wp_enqueue_scripts', array($this, 'load_stylesheet_script'));

        //for admin or backend
        add_action('admin_enqueue_scripts', array($this, 'load_stylesheet_script'));
        add_action('send_mail_action', array($this, 'send_mail'), 10, 1);
        add_filter('wp_mail_content_type', array($this, 'set_my_mail_content_type'));
        add_action('phpmailer_init', array($this, 'send_smtp_email'), 10, 3);
        add_action('wp_ajax_rating_filter', array($this, 'rating_filter'));
        add_action('wp_ajax_latest_filter', array($this, 'latest_filter'));

        add_shortcode('registration_form_code', array($this, 'registration_form'));
        add_shortcode('registration_list_code', array($this, 'register_list'));
        add_action('plugin_loaded', array($this, 'registration_plugin_load_text_domain'));
    }

    /**
     * Function to load text domain
     * 
     */
    public function registration_plugin_load_text_domain()
    {

        load_plugin_textdomain('registration', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Function for config. of phpmailer
     * 
     * @var $phpmailer is phpmailer object
     */
    function send_smtp_email($phpmailer)
    {
        $phpmailer->isSMTP();
        $phpmailer->SMTPDebug = 2;
        $phpmailer->CharSet  = "utf-8";
        $phpmailer->Host       = 'smtp.gmail.com';
        $phpmailer->Port       = '465';
        $phpmailer->SMTPSecure = 'ssl';
        $phpmailer->SMTPAuth   = true;
        $phpmailer->Username   = '' ;// 
        $phpmailer->Password   = ''; //app password removed for security reasion
        $phpmailer->From    = ''; 
    }

    /**
     * Function to set the content type of mail message
     * 
     */
    function set_my_mail_content_type()
    {
        return "text/html";
    }

    /**
     * Function fo sendmail to registered user
     * 
     * @var $email is email address of user
     */
    function send_mail($email)
    {
        $to = $email;
        $subject = "Successful Registration";
        $message = "Congratulation!! Your email $email is successfully registerd!!";
        wp_mail($to, $subject, $message);
    }

    /**
     * Function for loading stylesheet and Scripts
     * 
     */
    function load_stylesheet_script()
    {
        wp_register_style('style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css');
        wp_enqueue_style('style');
        wp_register_script('register_script', WP_PLUGIN_URL . '/registration/templates/js/register.js', array('jquery'), '1.0.0', true);
        wp_localize_script('register_script', 'myscript', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_enqueue_script('jquery');
        wp_enqueue_script('register_script');
    }
    /**
     * Function for list of menu in registration plugin
     * 
     */
    function registration_menu()
    {
        add_menu_page('Registration', 'Registration Form', 'manage_options', 'register_slug', array($this, 'registration_form'), plugins_url('icon.png', __FILE__));
        add_submenu_page('register_slug', 'Register List', 'Register List', 'manage_options', 'register_list_slug', array($this, 'register_list'));
    }

    /**
     * Function to extract user name from email
     * 
     * @var $email is user email
     * 
     * @return string is user name
     */
    function extract_user_name($email): string
    {
        $userName = strstr($email, '@', true);
        return $userName;
    }

    /**
     * Function to render Form template.
     * 
     */
    function registration_form()
    {
        if (isset($_POST['register_nonce_field']) || wp_verify_nonce($_POST['register_nonce_field'], 'register_nonce_action')) {
            if (isset($_POST['register'])) {
                global $wpdb;
                global $table_prefix;
                $table = $table_prefix . 'registers';

                //Accessing data and sanitizing
                $email = sanitize_email($_POST['email']);

                //applying filter to get user name from email
                $userName = apply_filters('get_user_name_filter', $email);

                $firstName = sanitize_text_field($_POST['firstname']);
                $lastName = sanitize_text_field($_POST['lastname']);
                $fullName = "$firstName $lastName";
                $pass = sanitize_text_field($_POST['password']);
                $pass_sh = password_hash($pass, PASSWORD_BCRYPT);
                $review = sanitize_text_field($_POST['review']);
                $reviewRating = sanitize_text_field($_POST['review_rating']);
                $sql = $wpdb->prepare("SELECT id FROM $table WHERE email = %s", $email);
                $id = $wpdb->get_results($sql);
                if (empty($id)) {
                    $wpdb->insert($table, array(
                        'firstname' => $firstName,
                        'lastname' => $lastName,
                        'user_name' => $userName,
                        'email' => $email,
                        'password' => $pass_sh,
                        'review' => $review,
                        'rating' => $reviewRating,
                        'register_date' => date('Y-m-d')
                    ), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));

                    // costume action hook to send mail
                    do_action('send_mail_action', $email);

                    _e('<div class="alert alert-success" role="alert">
            Registration Successfully !!
            </div>');
                } else {
                    _e('<div class="alert alert-success" role="alert">
            Provided Email ' . $email . ' is already Registered!!
            </div>');
                }
            }
        }
        // ob_start();
        include_once('templates/costum-register.php');
        // return ob_get_clean();  
    }

    /**
     * Function to render the List of Registerd User
     * 
     */
    function register_list()
    {
        global $wpdb;
        global $table_prefix;
        $table = $table_prefix . 'registers';
        $sql = $wpdb->prepare("SELECT * FROM $table");
        $register_lists = $wpdb->get_results($sql);

        // ob_start();
        include_once('templates/costum-register-list.php');
        // return ob_get_clean();
    }

    /**
     * Function to Filter the latest order like asending and descenting
     * 
     */
    function latest_filter()
    {
        global $wpdb;
        global $table_prefix;
        $table = $table_prefix . 'registers';
        $type = sanitize_text_field($_POST['type']);
        if ($type == 'latest') {
            $sql = $wpdb->prepare("SELECT firstname, lastname, email, review, rating, register_date FROM $table ORDER BY register_date DESC");
        } elseif ($type == 'oldest') {
            $sql = $wpdb->prepare("SELECT firstname, lastname, email, review, rating, register_date FROM $table ORDER BY register_date ASC");
        } else {
            $sql = $wpdb->prepare("SELECT * FROM $table");
        }
        $res = $wpdb->get_results($sql);
        foreach ($res as $list) {
            _e('<div class="card" style="width: 18rem;">
            <div class="card-header">' . $list->firstname . ' ' . $list->lastname . '</div>
            <div class="card-body">
              <h6 class="card-title">Review :-</h6>
              <p>' . $list->review . '</p>
              <p>Rating :- ' . $list->rating . '</p>
            </div>
            <div class="card-footer">' . $list->email . '</div>
          </div>');
        }
        // wp_send_json($res);
        wp_die();
    }

    /**
     * Function to filter according to rating of review
     * 
     */
    function rating_filter()
    {
        global $wpdb;
        global $table_prefix;
        $table = $table_prefix . 'registers';
        $rating = sanitize_text_field($_POST['rating']);
        if ($rating >= 1) {
            $sql = $wpdb->prepare("SELECT firstname, lastname, email, review, rating FROM $table WHERE rating = %s", $rating);
        } else {
            $sql = $wpdb->prepare("SELECT * FROM $table");
        }
        $res = $wpdb->get_results($sql);
        foreach ($res as $list) {
            _e('<div class="card" style="width: 18rem;">
            <div class="card-header">' . $list->firstname . ' ' . $list->lastname . '</div>
            <div class="card-body">
              <h6 class="card-title">Review :-</h6>
              <p>' . $list->review . '</p>
              <p>Rating :- ' . $list->rating . '</p>
            </div>
            <div class="card-footer">' . $list->email . '</div>
          </div>');
        }
        // wp_send_json($res);
        wp_die();
    }

    /**
     * Function to create register table in database
     * 
     */
    function create_table()
    {
        global $wpdb;
        global $table_prefix;
        $table = $table_prefix . 'registers';
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `firstname` varchar(50) NOT NULL,
            `lastname` varchar(50) NOT NULL,
            `user_name` varchar(100) NOT NULL,
            `email` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `review` text NOT NULL,
            `rating` int(11) NOT NULL,
            `register_date` date NOT NULL,
            PRIMARY KEY (id)
          )";
        require_once(ABSPATH . "wp-admin/includes/upgrade.php");
        dbDelta($sql);
        return;
    }

    /**
     * Function to truncate the table when the plugin deactivated
     * 
     */
    function truncate_table()
    {
        global $wpdb;
        global $table_prefix;
        $table = $table_prefix . 'registers';
        $sql = "TRUNCATE $table";
        $wpdb->query($sql);
        return;
    }

    /**
     * Function to active the plugin
     * 
     */
    function register_activate()
    {
        $this->create_table();
        $this->registration_menu();
        flush_rewrite_rules();
    }

    /**
     * Function to deactive the plugin
     * 
     */
    function register_deactivate()
    {
        //If you want to make table empty while deactivating the plugin 
        $this->truncate_table();
        flush_rewrite_rules();
    }
}

// If class is exit the create object of class
if (class_exists('registration')) {
    $registration = new registration();
}

//activation and deactivation hook for plugin
register_activation_hook(__FILE__, array($registration, 'register_activate'));
register_deactivation_hook(__FILE__, array($registration, 'register_deactivate'));
