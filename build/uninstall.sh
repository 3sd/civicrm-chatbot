if [ -f "$CIVI_SETTINGS" ]; then
  chmod u+w $(dirname "$CIVI_SETTINGS")
  rm -f "$CIVI_SETTINGS"
fi

drupal_uninstall
amp_uninstall
