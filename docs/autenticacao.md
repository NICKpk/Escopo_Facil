# Autenticação em modais

As rotas de autenticação usam a landing page como fundo e abrem o componente compartilhado de autenticação com blur. A navegação entre login, cadastro e recuperação usa History API; os formulários enviam JSON via `fetch`, sem recarregar a página. O acesso ao painel após autenticar continua sendo uma navegação para `/dashboard`.

## Telas

| Rota | Tela |
| --- | --- |
| `/login` | Login |
| `/register` | Cadastro com nome, e-mail, cargo, senha, confirmação e aceite dos termos |
| `/forgot-password` | Solicitação de recuperação e confirmação da solicitação |
| `/reset-password/{token}?email=...` | Nova senha e confirmação da alteração |
| `/verify-email` | Confirmação pendente, reenvio e consulta de confirmação |
| `/verify-email/{id}/{hash}?expires=...&signature=...` | Confirmação por link assinado ou aviso de link inválido/expirado |
| `/confirm-password` | Confirmação de senha para continuar uma ação |

As rotas de confirmação e reenvio exigem uma sessão. Se um link de verificação for aberto sem sessão, o login preserva o destino assinado e continua a confirmação depois de autenticar.

## Preparação local

1. Configure o `.env` a partir do `.env.example`, caso ainda não exista.
2. Execute `composer install` e `npm install`.
3. Gere a chave uma vez com `php artisan key:generate`, caso o ambiente ainda não tenha uma.
4. Execute `php artisan migrate` para incluir `job_role` e `terms_accepted_at`.
5. Execute `npm run dev` (ou `npm run build`) e `php artisan serve`.

Use a URL correta do seu ambiente:

```dotenv
APP_NAME="Escopo Fácil"
APP_URL=http://127.0.0.1:8000
APP_LOCALE=pt_BR
MAIL_MAILER=log
MAIL_FROM_ADDRESS="nao-responda@example.test"
MAIL_FROM_NAME="${APP_NAME}"
```

O modo `log` **não entrega mensagens em caixas de entrada**. Os e-mails de confirmação e recuperação, incluindo seus links, são registrados em `storage/logs/laravel.log`. O conteúdo HTML é produzido por `resources/views/emails/auth.blade.php`.

Para testar localmente, cadastre uma conta e abra o link de confirmação registrado no log no mesmo navegador. Depois, saia e use a recuperação de senha; abra o novo link registrado no log e escolha uma nova senha.

Quando o serviço de e-mail for escolhido, configure o transporte correspondente no `.env`. Para SMTP, use `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_SCHEME`, `MAIL_USERNAME`, `MAIL_PASSWORD` e um remetente autorizado pelo provedor. As credenciais ficam somente no `.env`. Após mudar a configuração, execute `php artisan config:clear`.

## Regras e estado da sessão

- O cadastro valida e-mail único, os cargos existentes no backup, senha de pelo menos oito caracteres, confirmação da senha e aceite dos termos.
- Uma conta nova fica autenticada apenas para concluir a confirmação do e-mail. As rotas do sistema exigem `auth` e `verified`.
- A consulta do plano gratuito usa `price_monthly` e `price_yearly`; se os planos ainda não estiverem cadastrados, o vínculo fica nulo. O cadastro não concede um plano pago.
- Os links de recuperação expiram conforme `config/auth.php` e são consumidos após a troca de senha. Os de confirmação usam assinatura e prazo de validade.
- A recuperação responde com a mesma mensagem para e-mails conhecidos e desconhecidos. Login e envio de mensagens têm limites de tentativas.
- O modal atualiza os tokens CSRF quando a sessão muda, incluindo cadastro e logout.
- Os usuários do `backup_antigo` não são importados por este fluxo; a autenticação usa `users` do Laravel.

## Entrada no sistema

Após um login válido de uma conta com e-mail confirmado, o site reproduz a animação original de `backup_antigo/assets/videos/animacao_Escopo_Facil.webm` e segue para o destino retornado pelo Laravel. O destino padrão é `/dashboard`; o checkout solicitado antes do login permanece preservado. O dashboard e o backend do sistema não foram alterados por essa integração.

A animação está no componente `resources/views/components/system-entry.blade.php`, com arquivos públicos em `public/assets/system`. A tela exibe somente a animação e continua automaticamente; há uma alternativa quando o vídeo não pode ser reproduzido e limite de espera de nove segundos. Credenciais inválidas e contas aguardando confirmação permanecem nos respectivos modais. O envio tradicional do formulário, sem JavaScript, mantém o redirecionamento do Laravel.

## Arquivos principais

- `resources/views/components/login-modal.blade.php`: telas e formulários.
- `resources/views/components/auth-modal-field.blade.php`: campos compartilhados.
- `public/assets/landing/login-modal.js`: navegação, envio, estados, histórico e mensagens.
- `public/assets/landing/login-modal.css`: visual compartilhado.
- `app/Support/AuthModal.php`: respostas para o modal.
- `app/Http/Controllers/Auth`: autenticação, cadastro, recuperação e confirmação.
- `app/Providers/AppServiceProvider.php`: mensagens de e-mail e limites de requisição.
- `lang/pt_BR`: validações e mensagens em português.

## Verificação

```shell
php artisan test
```

Os testes usam SQLite em memória, notificações simuladas e não enviam e-mails externos. Os cenários específicos dos modais estão em `tests/Feature/Auth/AuthModalFlowTest.php`.
