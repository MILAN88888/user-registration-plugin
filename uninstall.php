<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die('Error!!, You cant Access!!');
}

class Uninstallregistration
{
    /**
     * Function to drop table while uninstalling
     * 
     */
    function drop_table()
    {
        global $wpdb;
        global $table_prefix;
        $table = $table_prefix . 'registers';
        $sql = "DROP TABLE $table";
        $wpdb->query($sql);
    }
}

// If class is exit the create object of class
if (class_exists('uninstallregistration')) {
    $unreg = new uninstallregistration();
    $unreg->drop_table();
}
