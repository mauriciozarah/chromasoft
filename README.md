# O Sistema está rodando em Laravel 11

# Com o projeto baixado, Rodar o comando:
# composer install

# Renomear o .env.example para .env

# Editar arquivo .env
# A estrutura do arquivo .env :

# DB_CONNECTION=mysql
# DB_HOST=localhost
# DB_PORT=3306
# DB_DATABASE=[nome_do_banco]
# DB_USERNAME=[usuario_do_banco]
# DB_PASSWORD=[senha_do_banco]

# Gerar chave de criptografia, rodando no terminal:
# php artisan key:generate


# Rodar a migration no terminal:
# php artisan migrate


# Gerando um usuário de teste
# Rodar no terminal:
# php artisan db:seed


# Acessar o sistema com o usuario
-- usuario : mzaha@hotmail.com
-- senha   : 102030

# Para produção mudar:
# APP_NAME=[nome_do_app]
# APP_ENV=local
# APP_KEY=base64:Cr2JQ+iSYU2IBlmSVprMPkMSWo5vcEQeUpapECrst64=
# APP_DEBUG=[false]
# APP_TIMEZONE=[America/Sao_Paulo]
# APP_URL=http://localhost