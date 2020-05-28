# Ci4_diaporama
Gestion des menus avec Codeigniter 4

# Installation du module

<pre>
    composer require adnduweb/Ci4_diaporama
    or
    /opt/plesk/php/7.xx/bin/php /usr/lib/plesk-9.0/composer.phar require adnduweb/Ci4_diaporama

</pre>
<pre>
    php spark migrate -all
    or
    /opt/plesk/php/7.xx/bin/php spark migrate -all

    php spark db:seed \\Adnduweb\\Ci4_diaporama\\Database\\Seeds\\PageSeeder
    or
    /opt/plesk/php/7.xx/bin/php spark db:seed \\Adnduweb\\Ci4_diaporama\\Database\\Seeds\\PageSeeder


    php spark Ci4_diaporama:publish
    or
    /opt/plesk/php/7.xx/bin/php spark Ci4_diaporama:publish
    </pre>
