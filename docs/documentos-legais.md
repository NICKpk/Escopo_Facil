# Documentos do projeto acadêmico

Termos de Uso: `/termos-de-uso`. Aviso de Privacidade: `/privacidade`. Ambos são públicos e possuem URLs de versão, como `/termos-de-uso/2026-10-22`.

Os links do footer, o símbolo LGPD e os documentos do cadastro abrem um leitor em modal com blur. O cadastro permanece aberto por trás, com campos e escolha do plano preservados. Sem JavaScript, os links abrem as páginas completas.

## Identificação

Responsáveis: Guilherme Augusto Tavares Cavalcante e Nicollas Ferreira. Instituição: Centro Universitário Senac, curso Tecnologia em Sistemas para Internet. Contato exibido nos documentos: Suporte@escopofacil.com.br (`legal.support_email`).

Essas informações ficam em `config/legal.php` e podem ser configuradas com `LEGAL_RESPONSIBLE`, `LEGAL_INSTITUTION` e `LEGAL_CONTACT_EMAILS` (separados por vírgula). Não há data de encerramento definida: o aviso descreve critérios de conservação, revisão e exclusão, sem prometer eliminação automática. `LEGAL_RETENTION_END` permite informar uma data posteriormente; uma mudança de política deve gerar nova versão do documento e comunicação aos participantes.

## Aceite e versões

Execute `php artisan migrate` para adicionar `terms_version` e `privacy_notice_version` aos usuários. O cadastro exige o checkbox e versões compatíveis com as apresentadas. São registrados o horário do aceite, a versão dos termos e a versão do aviso disponibilizado. O registro do aviso não é um consentimento genérico, e não prova que o participante leu todo o texto. Contas antigas não recebem aceite ou versão retroativos.

O conteúdo da versão `2026-10-22` está em `resources/views/legal/versions/v20261022`. Para alterar as regras, mantenha os arquivos antigos, crie uma pasta com a nova versão e adicione a referência em `legal.documents`, atualizando `legal.version` e a data. Versões desconhecidas retornam 404 e formulários desatualizados são rejeitados com uma mensagem para reler os documentos. O fluxo de comunicação/novo aceite para contas existentes deve ser planejado antes de uma mudança relevante; não é aplicado silenciosamente a contas antigas.

## Limites e operação

Os documentos descrevem a demonstração atual: PIX local ou AbacatePay Dev mode sem cobrança real, e-mail local em log, cookies necessários, referências de compra no armazenamento da aba, limites de acesso e recursos de exclusão já existentes. Eles não representam auditoria, certificação ou declaração de conformidade integral com a LGPD. O SVG LGPD é um símbolo informativo original do projeto; não utiliza marca oficial da ANPD nem selo de certificação.

A equipe deve revisar com a instituição o enquadramento e as bases legais, acesso aos dados, conservação de logs/cópias, atendimento das solicitações e mudanças de provedores antes de ampliar os testes. Os textos não implementam exclusão automática de logs ou backups nem substituem esses procedimentos. A migração de projetos e tarefas do sistema permanece fora deste trabalho.

Fontes oficiais consultadas: LGPD, https://www.planalto.gov.br/ccivil_03/_ato2015-2018/2018/lei/l13709compilado.htm ; direitos dos titulares, https://www.gov.br/anpd/pt-br/assuntos/titular-de-dados/direito-dos-titulares .
