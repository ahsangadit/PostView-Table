<?php
class AdminPostViewTable {
    public function __construct() {
        // Admin-related functionality here
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'initialize_settings'));
        // ...
    }

    public function add_admin_menu() {
        add_menu_page(
            'PostView Table Settings',
            'PostView Table',
            'manage_options',
            'postview_table_settings',
            array($this, 'render_settings_page'),
            'dashicons-admin-generic'
        );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h2>PostView Table Settings</h2>
            <p>Customize the settings for the PostView Table plugin.</p>
            <p><strong>Shortcode: [postview-table]</p>
            <form method="post" action="options.php">
                <?php settings_fields('postview_table_group'); ?>
                <?php do_settings_sections('postview_table'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function initialize_settings() {
        register_setting('postview_table_group', 'show_filter');
        register_setting('postview_table_group', 'columns');

        add_settings_section('postview_table_section', 'General Settings', array($this, 'render_section_info'), 'postview_table');
        add_settings_field('show_filter', 'Show Filter', array($this, 'render_show_filter_field'), 'postview_table', 'postview_table_section');
        add_settings_field('columns', 'Columns to Display', array($this, 'render_columns_field'), 'postview_table', 'postview_table_section');
    }

    public function render_section_info() {
        echo 'Customize the settings for the PostView Table plugin';
    }

    public function render_show_filter_field() {
        $show_filter = get_option('show_filter');
        echo '<label><input type="checkbox" name="show_filter" value="1" ' . checked(1, $show_filter, false) . ' /> Show Filter</label>';
    }

    public function render_columns_field() {
        $columns = get_option('columns');
        $available_columns = array('title', 'excerpt', 'author', 'categories');

        foreach ($available_columns as $column) {
            echo '<label><input type="checkbox" name="columns[' . $column . ']" value="1" ' . checked(1, $columns[$column], false) . ' />' . ucfirst($column) . '</label><br>';
        }

    }



}
