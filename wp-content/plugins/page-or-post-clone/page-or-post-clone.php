<?php
/**
 * @package Fast Page & Post Duplicator
 * @version 9.3
 */
/*
Plugin Name: Fast Page & Post Duplicator
Plugin URI: https://wordpress.org/plugins/page-or-post-clone/
Description: Post & Page Duplicator allows you to instantly clone any post, page, or custom post type while keeping all metadata, taxonomies and content.
Author: Carlos Fazenda
Version: 9.3
Author URI: http://carlosfazenda.com/
Requires at least: 4.5
Requires PHP: 5.6
Text Domain: page-or-post-clone
*/

/*
 * Duplica o Artigo/Página como draft e redireciona para o editor do Artigo/Página duplicado
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes do plugin
define('CF_CLONE_VERSION', '9.3');
define('CF_CLONE_DB_VERSION', '1.0');
define('CF_CLONE_PLUGIN_FILE', __FILE__);
define('CF_CLONE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CF_CLONE_PLUGIN_URL', plugin_dir_url(__FILE__));

function cf_clone_install_or_update() {
    global $wpdb;

    $installed_version = get_option('cf_clone_db_version');

    if ($installed_version != CF_CLONE_DB_VERSION) {

        $table_name = $wpdb->prefix . 'cf_clone_log';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            original_post_id BIGINT(20) NOT NULL,
            cloned_post_id BIGINT(20) NOT NULL,
            user_id BIGINT(20) NOT NULL,
            date DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY idx_date (date),
            KEY idx_user (user_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option('cf_clone_db_version', CF_CLONE_DB_VERSION);
    }
}
add_action('plugins_loaded', 'cf_clone_install_or_update');

// Log Register
function cf_log_clone($original_id, $new_id, $user_id) {
	global $wpdb;
	$wpdb->insert(
		$wpdb->prefix . 'cf_clone_log',
		array(
			'original_post_id' => $original_id,
			'cloned_post_id'   => $new_id,
			'user_id'          => $user_id,
			'date'             => current_time('mysql'),
		),
		array('%d','%d','%d','%s')
	);
}

function content_clone() {
    global $wpdb;    
    if (! (isset($_GET['post']) || isset($_POST['post']) || (isset($_REQUEST['action']) && 'content_clone' == $_REQUEST['action']))) {
        wp_die('No post to duplicate has been supplied!');
    }
    
    /*
     * Nonce verification
     */
    if (!isset($_GET['clone_nonce']) || !wp_verify_nonce($_GET['clone_nonce'], basename(__FILE__))) {
        return;
    }
 
    /*
     * id do Artigo/Página original
     */
    $post_id = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
    /*
     * conteúdo do Artigo/Página original
     */
    $post = get_post($post_id);
 
    /*
     * O autor do novo Artigo/Página é o utilizador corrente
     */
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;
    
    $allowed_roles = array('administrator', 'editor');
    
    if ($post->post_author == $new_post_author || array_intersect($allowed_roles, $current_user->roles)) {
 
        /*
         * se o Artigo/Página tiver conteúdo, duplica também
         */
        if (isset($post) && $post != null) {
     
            $args = array(
                'comment_status' => $post->comment_status,
                'ping_status'    => $post->ping_status,
                'post_author'    => $new_post_author,
                'post_content'   => $post->post_content,
                'post_excerpt'   => $post->post_excerpt,
                'post_name'      => $post->post_name,
                'post_parent'    => $post->post_parent,
                'post_password'  => $post->post_password,
                'post_status'    => 'draft',
                'post_title'     => $post->post_title,
                'post_type'      => $post->post_type,
                'to_ping'        => $post->to_ping,
                'menu_order'     => $post->menu_order
            );
     
            /*
             * insere o novo Artigo/Página via wp_insert_post()
             */
            $new_post_id = wp_insert_post($args);
     
            /*
             * leva também as taxonomias do Artigo/Página a duplicar
             */
            $taxonomies = get_object_taxonomies($post->post_type); // retorna um array das taxonomias
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }
            
            /*
             * SQL
             */
            $post_meta_infos = $wpdb->get_results(
				$wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%d", $post_id)
			);
            if (count($post_meta_infos)!=0) {
                $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES ";
				$sql_query_sel = array();
                foreach ($post_meta_infos as $meta_info) {
                    $meta_key = sanitize_text_field($meta_info->meta_key);
                    if ($meta_key == '_wp_old_slug') continue;
                    $meta_value = addslashes($meta_info->meta_value);
                    $sql_query_sel[] = $wpdb->prepare("(%d, %s, %s)", $new_post_id, $meta_key, $meta_value);
                }
				if (!empty($sql_query_sel)) {
					$sql_query .= implode(",", $sql_query_sel);
					$wpdb->query($sql_query);
				}
            }

			// Registar o clone na tabela de logs
			cf_log_clone($post_id, $new_post_id, $new_post_author);
     
            /*
             * fim - redireciona para o editor do novo Artigo/Página
             */
            wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
            exit;
        } else {
            wp_die('Post creation failed, could not find original post: ' . $post_id);
        }
    } else {
        wp_die('Sem permissões para clonar este artigo');
    }
}
add_action('admin_action_content_clone', 'content_clone');
 
/*
 * Adiciona link para clonagem
 */
function content_clone_link($actions, $post) {
    $current_user = wp_get_current_user();
    $allowed_roles = array('administrator', 'editor');
    
    if ($post->post_author == $current_user->ID || array_intersect($allowed_roles, $current_user->roles)) {
        if (current_user_can('edit_posts')) {
            $actions['clone'] = '<a href="' . wp_nonce_url('admin.php?action=content_clone&post=' . $post->ID, basename(__FILE__), 'clone_nonce') . '" title="Clone this item" rel="permalink">Clone</a>';
        }
    }
    return $actions;
}
 
add_filter('post_row_actions', 'content_clone_link', 10, 2);
add_filter('page_row_actions', 'content_clone_link', 10, 2);

/**
 * Add a donation banner to the WordPress dashboard (bottom right corner)
 */
function cf_add_donation_banner() {
	
	// Só mostrar para administradores
    if (!current_user_can('administrator')) return;

    // Se já clicou ou dismiss, não mostrar
    if (isset($_COOKIE['cf_donation_notice_dismissed']) && $_COOKIE['cf_donation_notice_dismissed'] === 'true') {
        return;
    }

    ?>
    <div class="cf-donation-modern-notice" style="display:none;">
        <div class="cf-donation-inner">
            <div class="cf-donation-image">
                <img src="https://ps.w.org/page-or-post-clone/assets/icon-128x128.png" alt="Page or Post Clone">
            </div>
            <div class="cf-donation-text">
                <strong>Thank you for using Page or Post Clone!</strong>
                <span>If this plugin has been helpful, please consider supporting its development.</span>
            </div>
        </div>
        <div class="cf-donation-buttons">
            <a href="https://www.paypal.me/carlosfazenda/20" target="_blank" class="button button-primary">Donate $20</a>
            <a href="#" class="cf-notice-dismiss">Not now</a>
        </div>
    </div>

    <script>
    jQuery(function($){
        // Mostrar banner após 1s
        setTimeout(function(){
            $('.cf-donation-modern-notice').fadeIn();
        }, 1000);

        // Dismiss
        $('.cf-donation-modern-notice .cf-notice-dismiss').on('click', function(e){
            e.preventDefault();
            var date = new Date();
            date.setMonth(date.getMonth() + 1); // 1 mês
            document.cookie = "cf_donation_notice_dismissed=true; expires=" + date.toUTCString() + "; path=/";
            $('.cf-donation-modern-notice').fadeOut();
        });

        // Ao clicar no Donate, também dismiss
        $('.cf-donation-modern-notice .button-primary').on('click', function(){
            var date = new Date();
            date.setMonth(date.getMonth() + 1);
            document.cookie = "cf_donation_notice_dismissed=true; expires=" + date.toUTCString() + "; path=/";
            $('.cf-donation-modern-notice').fadeOut();
        });
    });
    </script>
    <?php
}
add_action('admin_footer', 'cf_add_donation_banner');

/* 
 * Menu e Dashboard do Plugin
 */
function cf_clone_add_menu() {
    add_menu_page(
        'Clone Dashboard',
        'Clone Stats',
        'manage_options',
        'cf_clone_dashboard',
        'cf_clone_dashboard_page',
        'dashicons-admin-page',
        25
    );
}
add_action('admin_menu', 'cf_clone_add_menu');

function cf_clone_dashboard_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cf_clone_log';

    // Total de clones
    $total_clones = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

    // Clones por utilizador
    $clones_per_user = $wpdb->get_results("
        SELECT user_id, COUNT(*) as total
        FROM $table_name
        GROUP BY user_id
        ORDER BY total DESC
    ");

    // Últimos 20 clones
    $clones = $wpdb->get_results("
        SELECT * FROM $table_name
        ORDER BY date DESC
        LIMIT 20
    ");

    echo '<div class="wrap">';
    echo '<h1>Clone Dashboard</h1>';
    echo '<p>Monitor all cloning activity and statistics for your WordPress content.</p>';

    // Donation Box no Dashboard
    echo '<div class="cf-dashboard-donation">';
	    echo '<div class="cf-dashboard-donation-inner">';
		echo '<div class="cf-dashboard-donation-image">';
		echo '<img src="https://ps.w.org/page-or-post-clone/assets/icon-128x128.png" alt="Page or Post Clone">';
		echo '</div>';
		echo '<div class="cf-dashboard-donation-text">';
		echo '<strong>💛 Support Page or Post Clone!</strong>';
		echo '<br>';
		echo '<span>Your donations help keep this plugin free, updated and improved. Consider making a contribution today!</span>';
		echo '</div>';
		echo '</div>';
		echo '<div class="cf-dashboard-donation-buttons">';
		echo '<a href="https://www.paypal.me/carlosfazenda/20" target="_blank" class="button button-primary">☕ Donate $20</a>';
		echo '</div>';
    echo '</div>';

    // Gráfico de atividade
    echo '<div style="max-height:280px; margin-bottom: 30px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">';
    echo '<h2 class="cf-dashboard-title">Clone Activity (Last 30 Days)</h2>';
    echo '<canvas id="cloneChart"></canvas>';
    echo '</div>';

    // Estatísticas Gerais
    echo '<div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 20px;">';
    echo '<h2 class="cf-dashboard-title">General Statistics</h2>';
    echo '<table class="wp-list-table widefat fixed striped cf-clone-table">';
    echo '<thead><tr><th style="width: 50%;">Metric</th><th>Value</th></tr></thead>';
    echo '<tbody>';
    echo '<tr><td><strong>Total Clones</strong></td><td>' . esc_html($total_clones) . '</td></tr>';
    echo '<tr><td><strong>Active Users</strong></td><td>' . count($clones_per_user) . '</td></tr>';
    echo '<tr><td><strong>Average per User</strong></td><td>' . ($total_clones > 0 && count($clones_per_user) > 0 ? round($total_clones / count($clones_per_user), 2) : 0) . '</td></tr>';
    echo '</tbody></table>';
    echo '</div>';

    // Clones por utilizador
    echo '<div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 20px;">';
    echo '<h2 class="cf-dashboard-title">Clones by User</h2>';
    echo '<table class="wp-list-table widefat fixed striped cf-clone-table">';
    echo '<thead><tr><th>User</th><th>Email</th><th>Total Clones</th></tr></thead><tbody>';
    foreach($clones_per_user as $row) {
        $user_info = get_userdata($row->user_id);
        echo '<tr>';
        echo '<td>' . ($user_info ? '<strong>' . esc_html($user_info->user_login) . '</strong>' : 'Unknown') . '</td>';
        echo '<td>' . ($user_info ? esc_html($user_info->user_email) : '-') . '</td>';
        echo '<td><span style="background: #0073aa; color: #fff; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">' . esc_html($row->total) . '</span></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '</div>';

    // Últimos 20 clones
    echo '<div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">';
    echo '<h2 class="cf-dashboard-title">Recent Clone Activity (Last 20)</h2>';
    echo '<table class="wp-list-table widefat fixed striped cf-clone-table">';
    echo '<thead><tr><th>Original Post</th><th>Cloned Post</th><th>Author</th><th>Date</th></tr></thead><tbody>';
    foreach($clones as $clone) {
        $orig_post = get_post($clone->original_post_id);
        $new_post  = get_post($clone->cloned_post_id);
        $user_info = get_userdata($clone->user_id);
        echo '<tr>';
        echo '<td>' . ($orig_post ? esc_html($orig_post->post_title) : '<em>Post deleted</em>') . '</td>';
        echo '<td>' . ($new_post ? '<a href="' . get_edit_post_link($clone->cloned_post_id) . '" target="_blank"><strong>' . esc_html($new_post->post_title) . '</strong></a>' : '<em>Post deleted</em>') . '</td>';
        echo '<td>' . ($user_info ? esc_html($user_info->user_login) : 'Unknown') . '</td>';
        echo '<td>' . esc_html(date('d/m/Y H:i', strtotime($clone->date))) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '</div>';

    echo '</div>';

    // Dados para o Chart.js (últimos 30 dias)
    $clones_by_day = $wpdb->get_results("
        SELECT DATE(date) as day, COUNT(*) as total
        FROM $table_name
        WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY DATE(date)
        ORDER BY day ASC
    ");

    $labels = [];
    $data = [];
    foreach($clones_by_day as $row){
        $labels[] = date('d M', strtotime($row->day));
        $data[] = (int)$row->total;
    }

    wp_localize_script(
        'cf-clone-dashboard',
        'cfCloneData',
        [
            'labels' => $labels,
            'data' => $data
        ]
    );
}



/**
 * Enqueue CSS para o banner de doação em todas as páginas admin
 */
function cf_clone_banner_assets() {
    wp_enqueue_style(
        'cf-clone-banner-css',
        CF_CLONE_PLUGIN_URL . 'css/admin-dashboard.css',
        [],
        CF_CLONE_VERSION
    );
}
add_action('admin_enqueue_scripts', 'cf_clone_banner_assets');

/**
 * Enqueue assets específicos do dashboard (Chart.js e JS do gráfico)
 */
function cf_clone_dashboard_assets($hook) {
    if ($hook !== 'toplevel_page_cf_clone_dashboard') return;

    // Chart.js
    wp_enqueue_script(
        'chartjs',
        'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
        [],
        '4.4.0',
        true
    );

    // JS do dashboard
    wp_enqueue_script(
        'cf-clone-dashboard',
        CF_CLONE_PLUGIN_URL . 'js/dashboard.js',
        ['chartjs', 'jquery'],
        CF_CLONE_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'cf_clone_dashboard_assets');

function cf_clone_plugin_row_meta($links, $file) {

    if (plugin_basename(__FILE__) === $file) {

        $donate_link = '<a href="https://www.paypal.me/carlosfazenda/20" target="_blank">❤️ Donate</a>';

        $links[] = $donate_link;
    }

    return $links;
}

add_filter('plugin_row_meta', 'cf_clone_plugin_row_meta', 10, 2);

?>