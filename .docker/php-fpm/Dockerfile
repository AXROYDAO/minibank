FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
    # БЛЯЯЯЯЯЯЯЯЯЯЯ
    # где установка композер?????????
    # я короче сейчас контейнер открыл и охуел что комозера нету
    # проект сейчас типо вообще развернуть нельзя без правок контейнера