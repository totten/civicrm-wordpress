<?php

/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 */

// This file must not accessed directly.
if (!defined('ABSPATH')) {
  exit;
}

?><!-- assets/templates/metaboxes/metabox.options.menusinglepage.php -->
<?php

/**
 * Before MenuSinglePage section.
 *
 * @since 6.12
 */
do_action('civicrm/metabox/menusinglepage/pre');

?>
<div class="menusinglepage_notice notice notice-error inline" style="background-color: #f7f7f7; display: none;">
  <p></p>
</div>

<p><?php esc_html_e('When displaying a custom form with a single-page access-token, the user is partially logged-in. The user can view the current page, but other pages require extra authentication.', 'civicrm'); ?></p>
<p><?php esc_html_e('In this context, the system may display the administrative menu-bar to certain users. This correctly indicates that the user is authenticated for the current page-view, but it wrongly implies that they have a full session. You may choose whether to allow or suppress this menu-bar.', 'civicrm'); ?></p>

<label for="menusinglepage" class="screen-reader-text"><?php esc_html_e('Suppress Menu for Single Page Tokens', 'civicrm'); ?></label>
<select name="menusinglepage" id="menusinglepage">
  <option value="yes"<?php echo $selected_yes; ?>><?php esc_html_e('Suppress Menu Bar', 'civicrm'); ?></option>
  <option value="no"<?php echo $selected_no; ?>><?php esc_html_e('Allow Default Menu Bar', 'civicrm'); ?></option>
</select>

<p class="submit">
  <?php submit_button(esc_html__('Saved', 'civicrm'), 'primary hide-if-no-js', 'civicrm_menusinglepage_submit', FALSE, $options_ajax); ?>
  <?php submit_button(esc_html__('Update', 'civicrm'), 'primary hide-if-js', 'civicrm_menusinglepage_post_submit', FALSE, $options_post); ?>
  <span class="spinner"></span>
</p>
<br class="clear">
<?php

/**
 * After MenuSinglePage section.
 *
 * @since 5.34
 */
do_action('civicrm/metabox/menusinglepage/post');
