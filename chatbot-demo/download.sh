#!/bin/bash

# Local
git_cache_setup "https://github.com/3sd/civicrm-chatbot.git" "$CACHE_DIR/3sd/civicrm-chatbot.git"
git_cache_setup "https://github.com/3sd/io.3sd.dummysms.git" "$CACHE_DIR/3sd/io.3sd.dummysms.git"
git_cache_setup "https://github.com/3sd/tivy.git" "$CACHE_DIR/3sd/tivy.git"
git_cache_setup "https://github.com/civicrm/org.civicrm.shoreditch.git" "$CACHE_DIR/civicrm/org.civicrm.shoreditch.git"

[ -z "$CMS_VERSION" ] && CMS_VERSION=7.x

[ -d "$WEB_ROOT.drushtmp" ] && rm -rf "$WEB_ROOT.drushtmp"
drush -y dl drupal-${CMS_VERSION} --destination="$WEB_ROOT.drushtmp" --drupal-project-rename
mv "$WEB_ROOT.drushtmp/drupal" "$WEB_ROOT"
[ -d "$WEB_ROOT.drushtmp" ] && rm -rf "$WEB_ROOT.drushtmp"

pushd "$WEB_ROOT"
  drush dl -y libraries-1.0 views-3.7

  pushd sites/all/modules
    git clone "${CACHE_DIR}/civicrm/civicrm-core.git" -b "$CIVI_VERSION" civicrm
    git clone "${CACHE_DIR}/civicrm/civicrm-drupal.git" -b "7.x-$CIVI_VERSION" civicrm/drupal
    git clone "${CACHE_DIR}/civicrm/civicrm-packages.git" -b "$CIVI_VERSION" civicrm/packages
    git clone "${CACHE_DIR}/3sd/civicrm-chatbot.git" civicrm/tools/extensions/civicrm-chatbot
    git clone "${CACHE_DIR}/3sd/io.3sd.dummysms.git" civicrm/tools/extensions/io.3sd.dummysms
    git clone "${CACHE_DIR}/civicrm/org.civicrm.shoreditch.git" civicrm/tools/extensions/org.civicrm.shoreditch

    # Use my chatbot branch of CivICRM for now
    pushd civicrm
      git remote add michael https://github.com/michaelmcandrew/civicrm-core
      git fetch michael
      git checkout chatbot
    popd

  popd

  pushd sites/all/themes
    git clone "${CACHE_DIR}/3sd/tivy.git" tivy
  popd

popd
