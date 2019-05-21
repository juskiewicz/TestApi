#!/bin/bash

docker exec -it rest_php73 ms_security/bin/console doctrine:database:drop --force
docker exec -it rest_php73 ms_security/bin/console doctrine:database:create
docker exec -it rest_php73 ms_security/bin/console doctrine:schema:update --force
docker exec -it rest_php73 ms_security/bin/console doctrine:fixtures:load --no-interaction
