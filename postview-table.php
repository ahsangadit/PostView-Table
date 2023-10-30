<?php
/*
Plugin Name: PostView Table
Version: 1.0
Author: Ahsan Amin
*/

// Load the admin and frontend classes
require_once(plugin_dir_path(__FILE__) . 'admin/class-admin-postview-table.php');
require_once(plugin_dir_path(__FILE__) . 'user/class-frontend-postview-table.php');

// Initialize the admin and frontend classes
new AdminPostViewTable();
new FrontendPostViewTable();
