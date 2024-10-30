
# Projeto de Teste para PX Motorista

Este repositório contém o código de um sistema API desenvolvido como parte de um desafio técnico para a vaga de desenvolvedor na empresa PX Motorista. Abaixo seguem as instruções para configuração, execução e uso do sistema.

## 1. Clonar o Repositório

Para iniciar, clone o repositório com o seguinte comando:
```bash
git clone https://github.com/felipebcv2/teste-xp.git
```

## 2. Executar o Sistema com Docker

Na raiz do projeto, execute o comando abaixo para construir e iniciar os containers. Aguarde até todos os containers finalizarem todos os processos:
```bash
docker compose build --no-cache && docker compose up
```
> **Nota:** O diretório `vendor` e o arquivo `.env` foram incluídos no repositório para simplificar o processo de execução, uma vez que se trata de um sistema de teste.

## 3. Acessar a Documentação da API (Swagger)

Após iniciar o sistema, você pode acessar a documentação Swagger da API através do seguinte link:
[http://localhost:8080/api/documentation](http://localhost:8080/api/documentation)

## 4. Autenticação e Geração de Token

Para utilizar os endpoints da API, é necessário gerar um token. Esta API utiliza o Laravel Sanctum para gerenciamento de tokens. Após a geração, o token do usuário de teste pode ser inserido no Swagger:

```bash
{
    "email": "teste@px.com",
    "password": "@3das$dasst341#08787jh"
}
```

- Clique no botão **"Authorize"** no canto superior direito da interface do Swagger.
- Insira o token gerado para obter acesso aos endpoints. 
> **Nota:** O token gerado possui uma expiração de 60 minutos.

## 5. Simulação de Envio de Email

A estrutura para envio de emails foi implementada, mas deixada comentada para fins de simulação. O envio de email pode ser acompanhado no log do sistema, que é registrado no arquivo `laravel.log`.

## 6. Geração de Relatórios em CSV

A geração de relatórios `.csv` está funcional, sendo logada juntamente com o link de download para facilitar o teste. O link de download do relatório é registrado no log da aplicação e, para acessar o relatório, basta abrir o link fornecido no navegador.

> Exemplo de log: 
> ```
> Relatório pronto e pode ser baixado no link: http://localhost:8080/storage/reports/3_task_report.csv
> ```

## 7. Observações Finais

Este projeto foi desenvolvido como um desafio técnico para a vaga de Desenvolvedor na PX Motorista. Para facilitar a execução do container, os arquivos `vendor` e `.env` foram incluídos no repositório, embora seja sabido que em projetos profissionais não se deve incluir tais arquivos. Em projetos reais, recomenda-se executar `composer install` durante o build.

---

### Contato

Para dúvidas ou informações adicionais:
- **Email:** felipebcv@gmail.com
