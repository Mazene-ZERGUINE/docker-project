#!/bin/bash
sleep 3
php bin/console doctrine:migration:migrate 
exec apache2-foreground




