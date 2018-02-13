CIVI_CORE="${WEB_ROOT}/sites/all/modules/civicrm"
CIVI_FILES="${WEB_ROOT}/sites/default/files/civicrm"
CIVI_TEMPLATEC="${CIVI_FILES}/templates_c"
CIVI_DOMAIN_NAME="Chatbot demo"
CIVI_DOMAIN_EMAIL="\"Chatbot demo\" <chatbot@example.org>"
CIVI_SETTINGS="${WEB_ROOT}/sites/default/civicrm.settings.php"
CIVI_UF="Drupal"

amp_install
drupal_install

drush user-create chat --password=chat
drush vset site_name "Chatbot demo site"

drush scr "$SITE_CONFIG_DIR/install/frontpage.php"

drush vset site_frontpage "node/1"

civicrm_install

drush en -y civicrm pathauto
drush dis -y overlay shortcut

cv ext:enable org.civicrm.shoreditch
cv ext:enable chatbot
