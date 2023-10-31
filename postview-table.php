<?php
/*
Plugin Name: PostView Table
Description: The PostView Table WordPress plugin allows you to display posts in a customizable table format on your WordPress site. It provides options to control the number of items, choose which columns to display, and enable a search filter for easy post retrieval.
Version: 1.0
Author: Ahsan Amin
*/

// Load the admin and frontend classes
require_once(plugin_dir_path(__FILE__) . 'admin/class-admin-postview-table.php');
require_once(plugin_dir_path(__FILE__) . 'user/class-frontend-postview-table.php');

// Initialize the admin and frontend classes
new AdminPostViewTable();
new FrontendPostViewTable();
