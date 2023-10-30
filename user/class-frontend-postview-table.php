<?php
class FrontendPostViewTable {
    public function __construct() {
        // Frontend-related functionality here
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));
        add_shortcode('postview-table', array($this, 'postview_table_shortcode'));
        add_action('wp_ajax_filter_posts', array($this, 'filter_posts')); 
        add_action('wp_ajax_nopriv_filter_posts', array($this, 'filter_posts')); 
    }

    // Define frontend-related methods here
    public function enqueue_scripts_and_styles() {
        // Enqueue scripts and styles here
        wp_enqueue_script('postview-table-script', plugin_dir_url(__FILE__) . '../assets/js/postview-table.js', array('jquery'), '1.0', true);
        wp_localize_script('postview-table-script', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'selected_columns' => $this->get_selected_columns(),
        ));
        wp_enqueue_style('postview-table-style', plugin_dir_url(__FILE__) . '../assets/css/postview-table.css', array(), '1.0', 'all');

    }

    public function postview_table_shortcode($atts) {
        $show_filter = get_option('show_filter');
        $columns_settings = get_option('columns');
    
        $available_columns = array(
            'title' => 'Post Title',
            'excerpt' => 'Post Excerpt',
            'author' => 'Post Author',
            'categories' => 'Post Categories',
        );
    
        $columns = $selectedColumns = $this->get_selected_columns();

    
        // Start generating the HTML
        $output = '<div class="postview-table">';
    
        if ($show_filter === '1') {
            $output .= '<input type="text" id="search-posts" placeholder="Search">';
        }
    
        $output .= '<table>';
    
        // Table header
        $output .= '<thead><tr>';
        foreach ($columns as $column) {
            $output .= '<th>' . esc_html($available_columns[$column]) . '</th>';
        }
        $output .= '</tr></thead>';
    
        $output .= '<tbody>';
        $args = array(
            'post_type' => 'post', 
            'posts_per_page' => 10, 
        );
    
        $query = new WP_Query($args);
    
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $output .= '<tr>';
                foreach ($columns as $column) {
                    $output .= '<td>' . $this->get_post_data($column) . '</td>';
                }
                $output .= '</tr>';
            }
        } else {
            $output .= '<tr><td colspan="' . count($columns) . '">No posts found</td></tr>';
        }
        $output .= '</tbody>';
    
        $output .= '</table>';
        $output .= '</div>';
    
        wp_reset_postdata();
    
        return $output;
    }
    
    private function get_post_data($column) {
        switch ($column) {
            case 'title':
                return get_the_title();
            case 'excerpt':
                return get_the_excerpt();
            case 'author':
                return get_the_author();
            case 'categories':
                return get_the_category_list(', ');
            default:
                return ''; // Handle unknown columns
        }
    }

    public function filter_posts() {
        $search = sanitize_text_field($_POST['search']);
        $selectedColumns = $this->get_selected_columns();
        
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            's' => $search,
        );

        $query = new WP_Query($args);

        $output_new = '';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $post_title = get_the_title();
                $post_excerpt = get_the_excerpt();
                $post_author = get_the_author();
                $post_categories = get_the_category_list(', ');

                $output_new .= '<tr>';
                if (in_array('title', $selectedColumns)) {
                    $output_new .= '<td>' . esc_html($post_title) . '</td>';
                }
                if (in_array('excerpt', $selectedColumns)) {
                    $output_new .= '<td>' . esc_html($post_excerpt) . '</td>';
                }
                if (in_array('author', $selectedColumns)) {
                    $output_new .= '<td>' . esc_html($post_author) . '</td>';
                }
                if (in_array('categories', $selectedColumns)) {
                    $output_new .= '<td>' . $post_categories . '</td>';
                }
                $output_new .= '</tr>';
            }
        } else {
            $output_new .= '<tr><td colspan="' . count($selectedColumns) . '">No posts found</td></tr>';
        }

        wp_reset_postdata();

        $output = json_encode(array( 'data' => $output_new));
        echo $output;
        wp_die();
    }

    private function get_selected_columns() {
        $selected_columns = array();
        $columns_settings = get_option('columns');

        $available_columns = array(
            'title' => 'Post Title',
            'excerpt' => 'Post Excerpt',
            'author' => 'Post Author',
            'categories' => 'Post Categories',
        );
    
        $selected_columns = array();

        foreach ($available_columns as $column_key => $column_label) {
            if (isset($columns_settings[$column_key]) && $columns_settings[$column_key] === '1') {
                $selected_columns[] = $column_key;
            }
        }
        return $selected_columns;
    }


}
