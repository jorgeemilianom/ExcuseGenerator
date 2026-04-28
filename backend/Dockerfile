FROM php:8.2-cli

RUN apt-get update && apt-get install -y libcurl4-openssl-dev \
    && docker-php-ext-install curl \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY . .

EXPOSE 8000

# Asegura permisos del storage en cada inicio (necesario cuando hay volumen montado)
CMD sh -c "mkdir -p /app/storage && chmod -R 777 /app/storage && php -S 0.0.0.0:8000 -t public"
