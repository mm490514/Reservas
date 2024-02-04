README - Sistema de Gerenciamento Hoteleiro

Este README fornecerá uma visão geral do projeto, instruções de configuração e detalhes sobre sua estrutura.

Descrição do Projeto
O projeto consiste em um Sistema de Gerenciamento Hoteleiro desenvolvido em PHP, utilizando o framework Bootstrap para o frontend. O sistema permite a gestão de hóspedes, quartos, reservas, categorias de quartos, formas de pagamento, transações, indicadores, entre outras funcionalidades relevantes para a administração de um hotel.

Requisitos
Servidor web (por exemplo, Apache ou Nginx)
PHP 7.0 ou superior
Banco de dados MySQL
Extensões PHP necessárias: PDO, GD
Configuração
Clone o repositório para o diretório desejado no seu servidor web.
Importe o banco de dados utilizando o script SQL fornecido (database.sql).
Configure a conexão com o banco de dados no arquivo conexao.php.
Certifique-se de que as permissões de escrita estão concedidas nas pastas necessárias (por exemplo, para uploads de imagens).
Estrutura do Projeto
index.php: Página principal que carrega os diferentes módulos do sistema.
modulos/: Contém os diferentes módulos do sistema, como hóspedes, quartos, reservas, etc.
css/: Arquivos de estilo CSS.
js/: Scripts JavaScript utilizados no projeto.
img/: Armazena imagens do sistema.
DataTables/: Biblioteca DataTables para exibição de tabelas interativas.
select2/: Biblioteca Select2 para aprimorar a experiência de seleção.
Funcionalidades Principais
Login e Perfil de Usuário:

Utiliza a sessão PHP para autenticação e controle de acesso.
Perfil de usuário exibindo dados pessoais e opções de edição.
Menus Dinâmicos:

Menus são exibidos com base no nível de acesso do usuário (administrador ou não).
Gestão de Dados:

Módulos para gerenciar hóspedes, quartos, reservas, categorias de quartos, formas de pagamento, entre outros.
Configurações do Sistema:

Modal de configurações para personalizar informações do sistema, como nome, e-mail, CNPJ, etc.
Integração com Bibliotecas Externas:

Uso do Bootstrap para a estrutura do frontend.
DataTables para exibição de tabelas interativas.
Select2 para melhorar a experiência de seleção.
Modalidades de Pagamento:

Opções para configurar a forma de pagamento, frequência automática, impressão automática, etc.
Contribuições
Contribuições são bem-vindas. Se você identificar problemas, erros ou tiver sugestões de melhorias, sinta-se à vontade para criar um pull request.

Autores
[Nome do Desenvolvedor 1]
[Nome do Desenvolvedor 2]
Licença
Este projeto está sob a Licença MIT.
