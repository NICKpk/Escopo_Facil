<h2>1. Escopo e responsabilidade</h2>
<p>Este aviso explica o tratamento de dados na demonstração acadêmica do Escopo Fácil. O responsável, a instituição e o contato são identificados no início do documento. A natureza acadêmica não autoriza o uso indiscriminado de dados: o enquadramento e as bases legais devem ser definidos pela equipe e pela instituição conforme as atividades efetivamente realizadas.</p>
<p>Para o fornecimento da conta solicitada, a equipe considera a necessidade de executar os procedimentos de acesso e de participação acordados com você. O cumprimento de obrigações legais e o exercício regular de direitos poderão fundamentar tratamentos específicos, quando aplicáveis. Eventuais atividades opcionais baseadas em consentimento exigirão uma manifestação própria e específica; o aceite dos termos não substitui essa manifestação.</p>

<h2>2. Dados e finalidades</h2>
<div class="legal-table-wrap"><table><thead><tr><th>Dados</th><th>Para que são utilizados</th></tr></thead><tbody>
<tr><td>Nome, e-mail e cargo informado</td><td>Criar e identificar sua conta, apresentar seu perfil e permitir a participação na demonstração.</td></tr>
<tr><td>Senha protegida por hash, confirmação de e-mail e tokens temporários</td><td>Autenticar o acesso, confirmar o endereço e permitir recuperação de senha. A senha não é armazenada em texto aberto.</td></tr>
<tr><td>Data de aceite, versão dos termos e versão do aviso apresentado</td><td>Registrar quais documentos foram disponibilizados no cadastro e a concordância com as regras de uso.</td></tr>
<tr><td>Identificadores de sessão, IP, informações do navegador e registros técnicos</td><td>Manter o acesso, limitar tentativas indevidas, diagnosticar erros e proteger o ambiente.</td></tr>
<tr><td>Plano, período, valor demonstrativo, identificador e estado do pedido</td><td>Executar a simulação de pagamento e demonstrar a liberação do plano.</td></tr>
<tr><td>Conteúdo inserido voluntariamente nos recursos disponíveis</td><td>Demonstrar e avaliar a organização de projetos, tarefas e equipes.</td></tr>
</tbody></table></div>
<p>Não são solicitados dados de cartão, conta bancária, CPF ou documentos pessoais pelo fluxo atual de pagamento. Evite inserir dados sensíveis e informações de terceiros. A utilização de dados reais em novas atividades deverá ser previamente avaliada pela instituição.</p>

<h2>3. Cookies e armazenamento no navegador</h2>
<p>São utilizados recursos necessários para manter a sessão e proteger os formulários contra requisições indevidas. A sessão possui prazo de inatividade de {{ (int) config('session.lifetime') }} minutos na configuração atual; registros técnicos podem permanecer armazenados para manutenção e segurança.</p>
<p>A aba do navegador também guarda temporariamente a escolha do plano e uma referência ao pedido para retomar a compra ao atualizar a página. Essas informações não armazenam sua senha. Você pode apagar os dados do site nas configurações do navegador, o que poderá encerrar a sessão e impedir a retomada automática do pedido.</p>
<p>A landing page não inclui ferramentas de publicidade ou análise de audiência nesta versão. A inclusão futura de recursos opcionais deverá ser informada e avaliada antes da ativação.</p>

<h2>4. Acesso, compartilhamento e serviços externos</h2>
<p>A equipe técnica poderá acessar os dados necessários à manutenção e ao suporte da demonstração. Apresentações e avaliações devem priorizar dados fictícios ou anonimizados. Não há comercialização de dados prevista nesta versão.</p>
<p>Na simulação local de PIX, o QR Code é gerado no próprio navegador. Se a integração de desenvolvimento da AbacatePay estiver habilitada, o servidor envia os dados da cobrança simulada, como valor, descrição do plano e referência do pedido, para obter e consultar o QR Code de teste.</p>
<p>Na configuração local de e-mail, as mensagens de confirmação e recuperação podem ser registradas em logs acessíveis à equipe técnica. Se um provedor de e-mail ou hospedagem for contratado, seus dados necessários à entrega ou à operação poderão ser tratados por ele. A equipe deverá identificar os provedores e avaliar eventual transferência internacional antes dessa alteração.</p>

<h2>5. Conservação e exclusão</h2>
@if(config('legal.retention_end'))
<p>A data prevista pela equipe para encerrar a retenção dos dados de teste é {{ config('legal.retention_end') }}. A equipe deverá realizar a exclusão ou anonimização dos dados que não tenham outra justificativa válida de conservação e informar eventuais alterações desse prazo.</p>
@else
<p>Os testes ainda não têm uma data de encerramento definida. Os dados necessários à participação poderão ser mantidos enquanto a conta for utilizada na demonstração e a finalidade permanecer válida. Você pode solicitar o encerramento da conta antes disso. A equipe deverá revisar a necessidade de conservação dos dados e comunicar o encerramento dos testes e o procedimento de exclusão ou anonimização quando ele for definido.</p>
@endif
<p>Você pode solicitar a exclusão da conta pela área de <a href="{{ route('profile.edit') }}" target="_blank" rel="noopener">perfil</a>, quando autenticado e com e-mail confirmado, ou procurar os responsáveis indicados acima. A exclusão da conta também remove os pedidos e assinaturas de teste vinculados no banco principal. Registros técnicos, mensagens e eventuais cópias de segurança precisam ser avaliados separadamente pela equipe; a exclusão da conta não garante sua eliminação imediata desses locais.</p>
<p>Conservação adicional somente deverá ocorrer quando houver justificativa aplicável, com acesso limitado e pelo tempo necessário. A equipe não deve manter dados pessoais indefinidamente apenas porque o projeto é acadêmico.</p>

<h2>6. Seus direitos e como solicitar atendimento</h2>
<p>Conforme o enquadramento aplicável, você pode solicitar informações sobre o tratamento, acesso e correção de seus dados, e pedir bloqueio, anonimização ou eliminação de dados desnecessários ou tratados irregularmente. Quando houver tratamento baseado em consentimento, poderá revogá-lo. Outros direitos previstos na legislação também podem ser exercidos.</p>
<p>Procure o contato indicado no início deste aviso, descrevendo o pedido e informando o e-mail da sua conta. Não envie sua senha. A equipe poderá solicitar apenas informações necessárias para confirmar sua identidade e encaminhar o atendimento, sem cobrança pelo exercício dos direitos.</p>
<p>Informações oficiais sobre esses direitos estão disponíveis na <a href="https://www.gov.br/anpd/pt-br/assuntos/titular-de-dados/direito-dos-titulares" target="_blank" rel="noopener noreferrer">página da ANPD</a>.</p>

<h2>7. Segurança e atualizações</h2>
<p>O sistema utiliza hash de senhas, controle de sessão, confirmação de e-mail, proteção de formulários e limites de tentativas em fluxos de autenticação. Essas medidas não representam garantia absoluta de segurança. Se identificar acesso indevido ou exposição de dados, avise a equipe pelo contato informado.</p>
<p>Este aviso tem versão identificada por data. Alterações de finalidade, serviços externos, prazos ou responsáveis deverão ser comunicadas de forma clara. O símbolo “LGPD” no footer é informativo e leva a este aviso; não representa certificação ou aprovação por uma autoridade.</p>
<p>Referência normativa: <a href="https://www.planalto.gov.br/ccivil_03/_ato2015-2018/2018/lei/l13709compilado.htm" target="_blank" rel="noopener noreferrer">Lei nº 13.709/2018 — LGPD</a>.</p>
