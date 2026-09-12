#!/bin/bash

# Exit on error
set -e

# 1. Obter o IP local ativo
LOCAL_IP=$(hostname -I | awk '{print $1}')

if [ -z "$LOCAL_IP" ]; then
    echo "❌ Não foi possível obter o IP local. Verifique sua conexão com a rede."
    exit 1
fi

echo "🔍 IP local detectado: $LOCAL_IP"

# Caminhos das pastas
CERTS_DIR="./docker/nginx/certs"
NGINX_CONF="./docker/nginx/default.conf"

# Criar pasta de certificados se não existir
mkdir -p "$CERTS_DIR"

# 2. Apagar certificados antigos
echo "🧹 Limpando certificados antigos..."
rm -f "$CERTS_DIR"/*.pem

# 3. Gerar novos certificados com mkcert
echo "🔑 Gerando novos certificados para o IP: $LOCAL_IP..."
cd "$CERTS_DIR"
mkcert "$LOCAL_IP" localhost 127.0.0.1
cd - > /dev/null

# Identificar os arquivos gerados
CERT_FILE=$(ls "$CERTS_DIR" | grep -E "^${LOCAL_IP}.*\.pem$" | grep -v "\-key\.pem$")
KEY_FILE=$(ls "$CERTS_DIR" | grep -E "^${LOCAL_IP}.*-key\.pem$")

if [ -z "$CERT_FILE" ] || [ -z "$KEY_FILE" ]; then
    echo "❌ Falha ao localizar os arquivos gerados pelo mkcert."
    exit 1
fi

echo "📄 Certificado gerado: $CERT_FILE"
echo "🔑 Chave privada gerada: $KEY_FILE"

# 4. Sobreescrever a configuração do Nginx com os novos nomes de arquivo
echo "⚙️ Atualizando a configuração do Nginx ($NGINX_CONF)..."
cat <<EOF > "$NGINX_CONF"
server {
    listen 443 ssl;
    server_name _;

    ssl_certificate /etc/nginx/certs/$CERT_FILE;
    ssl_certificate_key /etc/nginx/certs/$KEY_FILE;

    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    location / {
        proxy_pass http://laravel.test:80;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto https;
        proxy_set_header X-Forwarded-Port 443;
    }
}
EOF

# 5. Reiniciar o Nginx no Sail
echo "🔄 Reiniciando o container do Nginx..."
if ./vendor/bin/sail ps | grep -q "sail-nginx"; then
    ./vendor/bin/sail restart nginx
else
    echo "🚀 Subindo os containers do Sail..."
    ./vendor/bin/sail up -d
fi

echo "=================================================="
echo "✅ SSL e Nginx atualizados com sucesso!"
echo "🌐 Acesse no seu dispositivo: https://$LOCAL_IP"
echo "=================================================="