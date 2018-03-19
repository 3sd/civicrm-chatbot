#!/bin/bash
set -x
pushd `cv path -x chatbot`/chatbot-demo
cv ext:uninstall chatbot
cv ext:enable chatbot
cv scr createDemoData.php
drush role-add-perm 'civicrm user' 'access chatbot'
if [ -f secrets.sh ]; then
  bash secrets.sh
fi
popd
