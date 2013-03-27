<?php

include_once(dirname(__FILE__) . '/includes/bootstrap.inc');

function bootstrap_form_system_theme_settings_alter(&$form, $form_state, $form_id = NULL) {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  $form['themedev'] = array(
    '#type'          => 'fieldset',
    '#title'         => t('Theme development settings'),
  );

  $form['themedev']['bootstrap_rebuild_registry'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Rebuild theme registry on every page.'),
    '#default_value' => theme_get_setting('bootstrap_rebuild_registry'),
    '#description'   => t('During theme development, it can be very useful to continuously <a href="!link">rebuild the theme registry</a>.') . '<div class="alert alert-error">' . t('WARNING: this is a huge performance penalty and must be turned off on production websites. ') . l('Drupal.org documentation on theme-registry.', 'http://drupal.org/node/173880#theme-registry'). '</div>',
  );

  $form['cdn'] = array(
    '#type'          => 'fieldset',
    '#title'         => t('Theme cdn settings'),
  );

  $form['cdn']['cdn_bootstrap'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Use CDN to load in the bootstrap files'),
    '#default_value' => theme_get_setting('cdn_bootstrap'),
    '#description'   => t('Use cdn (a third party hosting server) to host the bootstrap files, Bootstrap Theme will not use the local CSS files anymore and instead the visitor will download them from ') . l('bootstrapcdn.com', 'http://bootstrapcdn.com')
                        .'<div class="alert alert-error">' . t('WARNING: this technique will give you a performance boost but will also make you dependant on a third party who has no obligations towards you concerning uptime and service quality.') . '</div>',
  );

  $form['cdn']['cdn_jquery'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Use CDN to load in a newer version of jQuery using the no-conflict solution.'),
    '#default_value' => theme_get_setting('cdn_jquery'),
    '#description'   => t('Use cdn to host the latest version of jquery and load the newer version using the ') . l('no-conflict', 'http://api.jquery.com/jQuery.noConflict/') . t(' solution.')
                          . '<div class="alert alert-error">' .
                            ('WARNING: this technique will load 2 versions of jQuery, which is bad for front-end performance and adds an extra whopping 90kb (not gziped) to your download (aka not mobile friendly).
                             Also this solution uses CDN and this will make you dependant on a third party who has no obligations towards you concerning uptime and service quality.') . '</div>',
  );
}

