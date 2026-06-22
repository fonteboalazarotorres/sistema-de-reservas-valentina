# Sistema de Reservas VALENTINA

## 📘 Descrição

O **Sistema de Reservas VALENTINA** foi desenvolvido para gerenciar e otimizar as reservas e empréstimos das salas de estudos em biblioteca universitária. A aplicação possibilita agendamentos de forma simples, prática e transparente, informando a disponibilidade em tempo real, evitando sobreposições e facilitando a gestão dos espaços.

O nome **"Valentina"** surgiu em homenagem à sobrinha de Fonte‑Boa, dá‑se assim o nome **Sistema Valentina**.

**Principais objetivos:**

- Agilizar o processo de empréstimo e reserva de salas.
- Oferecer visualização em tempo real da disponibilidade das salas.
- Garantir acessibilidade digital e inclusão de todos os usuários.
- Gerar relatórios automáticos para apoio à gestão.

***

## 👨‍💻 Autoria e Direitos

**Desenvolvimento:** Fonte-Boa Lázaro Torres  
**Idealização:** Robson de Paula Araújo  
**Contato:** [http://lattes.cnpq.br/4623045728159220](http://lattes.cnpq.br/4623045728159220)

***

## ⚖️ Licença de Uso e Direitos Autorais

Este software é **propriedade intelectual do desenvolvedor original, Fonte-Boa Lázaro Torres**, sendo **idealizado por Robson de Paula Araújo**.

O uso, modificação, redistribuição ou comercialização deste sistema é **permitido apenas mediante os seguintes termos**:

1. **Créditos obrigatórios** devem ser mantidos em todas as versões, modificações ou redistribuições do código, citando explicitamente:  
   > “Desenvolvido por Fonte-Boa Lázaro Torres (http://lattes.cnpq.br/4623045728159220), idealizado por Robson de Paula Araújo (https://lattes.cnpq.br/1572390366115265).”

2. **É proibida** a venda, distribuição, hospedagem, ou uso comercial do sistema (ou de partes dele) **sem autorização prévia e por escrito** do desenvolvedor original.

3. **É permitida** a modificação ou adaptação do sistema **somente para uso interno e institucional**, desde que sejam **mantidos os créditos originais** e **não seja feita nenhuma exploração comercial sem autorização**.

4. Qualquer uso indevido, apropriação de autoria, omissão de créditos ou tentativa de comercialização **poderá resultar em medidas legais e sanções conforme a Lei nº 9.610/1998 (Lei de Direitos Autorais - Brasil)**.

***

## 🛠️ Tecnologias Utilizadas

- Backend: PHP (autenticação, permissões, processamento de formulários)
- Banco de dados: MySQL (consultas, filtragem e vínculos entre reservas, salas e equipamentos)
- Frontend: HTML, CSS, JavaScript, Bootstrap 5 (responsividade)
- Interatividade: AJAX para atualizações dinâmicas e validação em tempo real

***

## ⚙️ Requisitos para Instalação

Antes de instalar o sistema, certifique-se de que o ambiente possui:

- Servidor web local ou remoto, como Apache, Nginx, XAMPP, WAMP, Laragon ou similar.
- PHP compatível com PDO e extensão para MySQL habilitada.
- MySQL ou MariaDB instalado e em execução.
- Navegador web atualizado.
- Arquivo de estrutura do banco de dados `db_structure.sql` disponível no projeto.

***

## 🚀 Instalação do Sistema

### 1. Obter os arquivos do projeto

Você pode obter o sistema de duas formas:

#### Opção A: clonar o repositório

```bash
git clone <URL_DO_REPOSITORIO>
```

#### Opção B: baixar o projeto manualmente

- Baixe o arquivo `.zip` do projeto.
- Extraia os arquivos em uma pasta no seu computador.

***

### 2. Colocar o projeto no diretório do servidor

Depois de clonar ou extrair o projeto, copie a pasta para o diretório do seu servidor local.

Exemplos comuns:

- **XAMPP:** `htdocs/`
- **WAMP:** `www/`
- **Laragon:** `www/`
- **Servidor Linux Apache/Nginx:** diretório configurado como raiz do projeto

Exemplo:

```bash
htdocs/sistema_valentina
```

***

### 3. Criar o banco de dados

No MySQL, crie um banco de dados com o nome:

```sql
CREATE DATABASE sistema_valentina;
```

***

### 4. Importar a estrutura do banco

Após criar o banco, importe o arquivo `db_structure.sql` para dentro do banco `sistema_valentina`.

#### Opção A: via phpMyAdmin

- Acesse o phpMyAdmin.
- Crie o banco `sistema_valentina`.
- Selecione o banco criado.
- Clique em **Importar**.
- Escolha o arquivo `db_structure.sql`.
- Execute a importação.

#### Opção B: via terminal

```bash
mysql -u root -p sistema_valentina < db_structure.sql
```

***

### 5. Configurar as credenciais do banco de dados

Edite o arquivo `config.php` e informe as credenciais corretas do seu ambiente.

Exemplo:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'sua_senha');
define('DB_NAME', 'sistema_valentina');
```

Campos que devem ser ajustados:

- `host`
- `user`
- `password`
- `database`

***

### 6. Acessar o sistema

Com o servidor web e o MySQL em execução, abra o navegador e acesse:

**URL local:** [http://localhost:8000](http://localhost:8000)  
*(ou conforme a configuração do seu servidor)*

**URL da Biblioteca:** [http://200.144.255.52:6080/](http://200.144.255.52:6080/)

***

## 🔎 Funcionalidades Principais

- Reserva/Empréstimo de salas com verificação automática de horários, evitando sobreposições.
- Limitação por capacidade da sala para prevenir superlotação.
- Campos padrão para empréstimo: Nome, Número USP, Vínculo, Data, Quantidade de pessoas, Hora de entrada, Hora de saída, Necessidade de equipamentos, Sala.
- Painel administrativo: edição/cancelamento de reservas, aceite de solicitações, encerramento de empréstimos.
- Gestão de salas: nome/numeração, capacidade, status e vinculação a manutenção.
- Estatísticas e relatórios mensais exportáveis.
- Tela/tutorial com manual e vídeos para usuários e atendentes.
- Envio automático de relatórios por e-mail à administração.

***

## ♿ Acessibilidade e padrões

O sistema foi desenvolvido seguindo as diretrizes WCAG 2.1 e a NBR 9050:2020, com atenção a:

- Alto contraste entre texto e fundo.
- Marcação semântica para leitores de tela.
- Navegação completa por teclado.
- Alternativas textuais para ícones.
- Controles acessíveis para pessoas com mobilidade reduzida.

Esses recursos visam permitir uso independente por pessoas com deficiência visual, baixa visão e outras necessidades especiais.

***

## 📄 Notas Importantes

- O sistema pode ser aperfeiçoado, mas todas as alterações, integrações externas ou redistribuições devem **respeitar os termos de uso e manter os créditos originais**.
- Alterações estruturais ou uso comercial exigem autorização prévia por escrito do desenvolvedor.
- Qualquer alteração estrutural, integração externa ou redistribuição deve ser **comunicada e aprovada previamente** pelo desenvolvedor original.
- Existem planos de registro formal do software junto aos órgãos competentes.

***

## 📩 Contato

Para solicitações de uso, autorização, parceria ou customização, contate o desenvolvedor:  
[fonteboa@usp.br](mailto:fonteboa@usp.br) (assunto: "Sistema Valentina")

**Fonte-Boa Lázaro Torres**  
🔗 [http://lattes.cnpq.br/4623045728159220](http://lattes.cnpq.br/4623045728159220)

***

© 2025 — Todos os direitos reservados.  
**Sistema de Reservas VALENTINA**  
Desenvolvido por **Fonte-Boa Lázaro Torres**, idealizado por **Robson de Paula Araújo**.
=======
# Sistema de Reserva de Salas USP

Sistema web simples para reserva de salas da USP, desenvolvido em PHP, HTML, JavaScript e MySQL, com dashboard administrativo e área pública de reservas sem necessidade de login. o prejeto foi inicialmente apresentado pelo meu supervisor de estágio Robson de Paula Araujo que quis fazer uma planilha no google forms para tentar excluir a forma anterior na qual se faziam as rezervas que era pelo papel. Mas, daí ele falou comigo e apresentou sua ideia e em mais ou menos 3 semnas o projeto estava 80% pronto pra testes reais depois de muitos bugs, e finalemnte funcionando.

## Funcionalidades

- Reserva de salas com controle automático de disponibilidade e bloqueio de horários conflitantes  
- Suporte para 15 salas com diferentes capacidades (até 4 pessoas e acima de 4 pessoas)  
- Coleta de dados detalhados: nome, número USP, vínculo, data, horário, equipamentos necessários  
- Dashboard administrativo com estatísticas, gráficos de reservas por mês e horários mais reservados  
- Aprovação, rejeição, edição e cancelamento de reservas pelo administrador  
- Exportação de relatórios em PDF e Excel  
- Sistema responsivo e acessível, seguindo identidade visual USP  
- Implementado para rodar em servidores comuns com cPanel  

## Tecnologias utilizadas

- PHP 7.x  
- MySQL  
- JavaScript (Chart.js para gráficos)  
- TCPDF (geração de PDFs)  
- PHPSpreadsheet (exportação Excel)  
- HTML5, CSS3 (com design inspirado na USP)  

## Instalação

1. Clone este repositório:  

git clone https://github.com/fonteboalazarotorres/biblioteca-central-usp-ribeirao-preto-sistema-de-reservas-de-salas.git


2. Configure o banco de dados MySQL:  
- Crie o banco `reserva_salas`  
- Importe o arquivo SQL com as tabelas e dados iniciais (fornecido no projeto)  
- Use o gerador de hash para gerar sua senha

3. Ajuste as credenciais do banco no arquivo `includes/config.php`:

define('DB_HOST', 'localhost');
define('DB_NAME', 'reserva_salas');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');


4. Faça upload dos arquivos para seu servidor cPanel ou ambiente local com suporte PHP e MySQL.

5. Acesse:  
- Área pública de reservas: `http://seusite.com/reserva_salas/index.php`  
- Dashboard administrativo: `http://seusite.com/reserva_salas/admin/`  
  (Usuário padrão: `admin`; Senha: `admin123` — altere após o primeiro acesso)

## Uso

- Usuários realizam reservas simples sem login, escolhendo sala, data, horário e equipamentos.  
- Administradores aprovam, rejeitam, editam e cancelam reservas via dashboard.  
- Estatísticas e gráficos ajudam no acompanhamento do uso das salas.  
- Exportação de relatórios em PDF facilita a análise e arquivamento.

## Contribuição

Contribuições são bem-vindas! Para sugerir melhorias ou corrigir bugs, abra uma issue ou envie um pull request.

## Licença


Este projeto está licenciado sob a licença CC BY-NC 4.0.
>>>>>>> fc66e454e6b4da9575f2252dfdc7bf544e9b1f52
