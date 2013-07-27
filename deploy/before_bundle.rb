# create file  is not exist
if !Dir.exist?(shared_path + "/app/Config") then
  run "mkdir -p #{shared_path}/app/Config/"
  run "cp #{release_path}/app/Config/core.php #{shared_path}/app/Config/core.php"
  run "cp #{release_path}/app/Config/install.inc.php #{shared_path}/app/Config/install.inc.php"
  run "cp -R #{release_path}/app/Plugin -p #{shared_path}/app/Plugin"
end

# prepare shared files and directories
run "rm #{release_path}/app/Config/core.php && ln -s #{shared_path}/app/Config/core.php #{release_path}/app/Config/core.php"
run "rm #{release_path}/app/Config/install.inc.php && ln -s #{shared_path}/app/Config/install.inc.php #{release_path}/app/Config/install.inc.php"
run "rm -r #{release_path}/app/Plugin && ln -s #{shared_path}/app/Plugin #{release_path}/app/Plugin"

# update nginx custom config
sudo "cp #{release_path}/deploy/nginx-custom.conf /etc/nginx/servers/#{config.app}/custom.conf"
sudo "/etc/init.d/nginx restart"

# set timezone in php.ini
sudo "echo 'date.timezone = Asia/Tokyo' > /etc/php/cgi-php5.4/ext-active/timezone.ini"
sudo "echo 'date.timezone = Asia/Tokyo' > /etc/php/cli-php5.4/ext-active/timezone.ini"
sudo "echo 'date.timezone = Asia/Tokyo' > /etc/php/fpm-php5.4/ext-active/timezone.ini"

# set allow_url_fopen = On
sudo "echo 'allow_url_fopen = On' > /etc/php/cgi-php5.4/ext-active/allow_url.ini"
sudo "echo 'allow_url_fopen = On' > /etc/php/cli-php5.4/ext-active/allow_url.ini"
sudo "echo 'allow_url_fopen = On' > /etc/php/fpm-php5.4/ext-active/allow_url.ini"
            
# run "cp /etc/nginx/servers/" + app  + ".conf.tmp /etc/nginx/servers/" + app  + ".conf"