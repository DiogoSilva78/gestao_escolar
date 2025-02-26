# Gestão Escolar RFID

Este projeto é uma aplicação web desenvolvida em PHP puro e MySQLi que integra um sistema de gestão escolar com funcionalidades como:

- Login tradicional (email/senha) e login via RFID.
- Painéis de controle para Administrador, Funcionário e Aluno.
- Gestão de utilizadores, turmas, produtos (papelaria e bar) e transações.
- Registro de entradas e saídas via RFID.
- Design moderno com Bootstrap 5.

## Estrutura do Projeto

A estrutura completa do projeto pode ser encontrada no diretório `gestao_escolar/`.

## Configuração do Ambiente

1. Instale o [XAMPP](https://www.apachefriends.org/).
2. Configure o banco de dados e importe o arquivo SQL de criação (se disponível).
3. Atualize o arquivo `config/db.php` com as configurações do seu ambiente.
4. Garanta que o Composer esteja instalado e que as dependências do projeto (se houver) estejam configuradas.

## Uso

- **Login:** Acesse a página principal (`index.php`) para fazer login usando email/senha ou via RFID.
- **Painéis:** Após o login, o sistema redireciona para o dashboard apropriado.
- **Gestão:** O administrador pode acessar seções para gerir utilizadores, turmas, produtos (papelaria e bar) e ver o histórico de entradas.
- **RFID:** Funcionários podem registrar entradas/saídas e realizar transações via RFID.

## Tecnologias Utilizadas

- PHP 7/8 e MySQLi
- Bootstrap 5
- JavaScript (Fetch API e WebSocket)
- Dompdf (para geração de PDFs, se necessário)

## Contribuições

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues e enviar pull requests.

## Licença

Este projeto é licenciado sob a [MIT License](LICENSE).
