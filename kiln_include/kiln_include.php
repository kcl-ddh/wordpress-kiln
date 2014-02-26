<?php
/*
** Plugin Name: Kiln Include
** Description: Include the contents of a Kiln URL into a WordPress page.
** Version: 1.0
** Author: Jamie Norrish
** License: GPLv3 or later
*/

add_action('admin_init', 'kiln_include_init');
add_action('admin_menu', 'kiln_include_menu');
add_shortcode('kiln_include', 'kiln_include');

function kiln_include ($atts) {
  $options = get_option('kiln_include');
  $url = $options['base_url'] . $atts['url'];
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $username = $options['username'];
  $password = $options['password'];
  if ($username) {
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
  }
  $data = curl_exec($ch);
  if ($data) {
    $contents = $data;
  } else {
    $contents = "[Failed to include material.]";
  }
  return $contents;
}

function kiln_include_init () {
  register_setting('kiln_include_options', 'kiln_include');
}

function kiln_include_menu () {
  add_options_page('Kiln Include plugin options', 'Kiln Include',
                   'manage_options', 'kiln-include-options-menu',
                   'kiln_include_options');
}

function kiln_include_options () {
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }
  ?>
  <div class="wrap">
    <h2>Kiln Include Plugin Settings</h2>

    <form method="post" action="options.php">
    <?php settings_fields('kiln_include_options'); ?>
    <?php $options = get_option('kiln_include'); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">Base URL of Kiln</th>
          <td><input name="kiln_include[base_url]" type="text"
                     value="<?php echo $options['base_url']; ?>" /></td>
        </tr>
        <tr valign="top">
          <th scope="row">Username (for HTTP authentication)</th>
          <td><input name="kiln_include[username]" type="text"
                     value="<?php echo $options['username']; ?>" /></td>
        </tr>
        <tr valign="top">
          <th scope="row">Password (for HTTP authentication)</th>
          <td><input name="kiln_include[password]" type="password"
                     value="<?php echo $options['password']; ?>" /></td>
        </tr>
      </table>

      <p class="submit"><input type="submit" name="submit" id="submit"
                               class="button button-primary"
                               value="<?php _e('Save Changes') ?>" /></p>
    </form>
  </div>
<?php
}
?>