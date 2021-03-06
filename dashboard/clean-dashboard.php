<?php

/**
 * Get rid of all existing dashboard widgets dynamically.
 */
function cd_ditch_dashboard_widgets() {
  $active_widgets = get_option('cd_active_widgets', null);

  // If no active widgets (which is never...), bail
  if (!$active_widgets) {
    return;
  }

  // Don't remove selected widgets
  $dont_remove = get_option('cd_remove_which_widgets');
  if ($dont_remove) {
    foreach ($dont_remove as $widget) {
      unset($active_widgets[$widget]);
    }
  }

  // Allow removing/adding of widgets to ditch externally
  $active_widgets = apply_filters('cd_remove_widgets', $active_widgets);

  if (current_user_can('publish_posts')) {
    foreach ($active_widgets as $widget => $values) {
      remove_meta_box($widget, 'dashboard', $values['context']);
    }
  }
}

add_action('wp_dashboard_setup', 'cd_ditch_dashboard_widgets', 1000);

// Welcome Panel
remove_action('welcome_panel', 'wp_welcome_panel');

/**
 * Remove Screen Options and Help on Dashboard.
 */
function cd_remove_screen_options() {

  // Help
  function mytheme_remove_help_tabs($old_help, $screen_id, $screen) {
    $screen->remove_help_tabs();

    return $old_help;
  }

  add_filter('contextual_help', 'mytheme_remove_help_tabs', 999, 3);

  // Screen options
  add_filter('screen_options_show_screen', '__return_false');
}

add_action('admin_head-index.php', 'cd_remove_screen_options');