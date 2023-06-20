#!/bin/bash
if [ ! -f "./public/swagger" ]; then
    mkdir ./public/swagger
fi

php ./vendor/bin/openapi --bootstrap ./docs/swagger/swagger-constants.php --output ./public/swagger/openapi.yaml ./docs/swagger/swagger-v1.php ./app/Http/Controllers/