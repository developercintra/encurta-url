# 🔗 Encurtador de URL

Um encurtador de URL moderno com QR Code, métricas em tempo real e sistema de expiração.

## Funcionalidades

-  **Autenticação completa** (registro, login, logout)
-  **Encurtamento de URLs** com slug único de 6 caracteres
-  **Expiração de links** configurável
-  **QR Code** gerado automaticamente para cada link
-  **Dashboard em tempo real** com métricas atualizadas
-  **Rate limiting** (30 links por minuto por usuário)
-  **Segurança** com hash de IPs e autorização de usuários
-  **Analytics** detalhadas com filtros por período
-  **Interface moderna** com Tailwind CSS

## Stack Tecnológica

- **Backend**: Laravel 12
- **Database**: MySQL/SQLite
- **Frontend**: Blade Templates + Tailwind CSS
- **Autenticação**: Laravel Breeze
- **QR Code**: SimpleSoftwareIO/simple-qrcode
- **Testes**: Pest/PHPUnit
- **Cache**: File/Redis (opcional)

## Instalação

### Pré-requisitos

- PHP 8.2+
- Composer
- MySQL/SQLite
- Node.js & NPM

### Passos

1. **Clone o repositório**
```bash
git clone https://github.com/developercintra/url-shortener.git
cd url-shortener
```

2. **Instale as dependências**
```bash
composer install
npm install
```

3. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure o banco de dados**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortener
DB_USERNAME=root
DB_PASSWORD=
```

5. **Execute as migrations**
```bash
php artisan migrate
```

6. **Compile os assets**
```bash
npm run build
```

7. **Inicie o servidor**
```bash
php artisan serve
```

Acesse: http://localhost:8000

## Testes

### Executar todos os testes
```bash
php artisan test
```

### Executar com cobertura
```bash
php artisan test --coverage
```

### Executar testes específicos
```bash
php artisan test --filter=LinkControllerTest
php artisan test tests/Feature/QrCodeTest.php
```

## API Endpoints

### Autenticação
- `POST /register` - Registro de usuário
- `POST /login` - Login
- `POST /logout` - Logout

### Links
- `GET /links` - Listar links do usuário
- `POST /links` - Criar novo link (Rate limited: 30/min)
- `GET /links/{id}` - Detalhes do link
- `GET /links/{id}/qr` - QR Code do link

### Redirecionamento
- `GET /s/{slug}` - Redirecionar para URL original

### Métricas (JSON)
- `GET /metrics/summary?period={1|7|30}` - Resumo das métricas
- `GET /metrics/top?period={1|7|30}&limit={10}` - Top links

### Dashboard
- `GET /dashboard?period={1|7|30}` - Dashboard com filtros

## Estrutura do Banco

### Tabela `users`
```sql
id, name, email, password, created_at, updated_at
```

### Tabela `links`
```sql
id, user_id, original_url, slug, status, expires_at, click_count, created_at, updated_at
```

### Tabela `visits`
```sql
id, link_id, ip_hash, user_agent, created_at
```

## Casos de Uso

### 1. Encurtar URL simples
```http
POST /links
{
    "original_url": "https://exemplo.com/pagina-muito-longa"
}
```

### 2. Link com expiração
```http
POST /links
{
    "original_url": "https://evento.com/inscricoes",
    "expires_at": "2024-12-31T23:59:59"
}
```

### 3. Obter métricas
```http
GET /metrics/summary?period=7
```

## Segurança

- **IPs hasheados**: IPs são armazenados com SHA-256
- **Autorização**: Usuários só acessam seus próprios links
- **Rate limiting**: Previne spam de criação de links
- **Validação**: URLs e datas de expiração validadas
- **CSRF Protection**: Tokens CSRF em formulários

## Tempo Real

O dashboard atualiza automaticamente a cada 5 segundos usando:
- Fetch API para endpoints JSON
- JavaScript vanilla para atualizações DOM
- Cache-Control para otimização

## QR Codes

- Gerados automaticamente para cada link
- Formato PNG, 200x200px
- Cache de 1 hora
- Download disponível na interface

## Cobertura de Testes

O projeto mantém >80% de cobertura com testes para:

- Autenticação e autorização
- Criação e validação de links
- Redirecionamento e expiração
- Geração de QR codes
- Rate limiting
- Métricas e dashboard
- APIs JSON