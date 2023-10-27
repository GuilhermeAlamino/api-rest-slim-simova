# Desafio Simova

Regra de negocios:

## Funcionarios

- Após a criação de um funcionario ele tem um código unico.

- Para atualizar um funcionario ele precisa existir.

## Apontamentos

- Após criar um apontamento precisamos calcular o tempo que levou a anterior mas se não existe apontamento anterior ele pega o atual. 

- Após criar um apontamento precisamos calcular o tempo então com base na nova data com novo horário pegamos o começo da anterior e somamos com a inicial assim vamos ter a diferença. 

- É possivel ver todos os apontamentos que estão ativos. 

- Tempo total de tarefas em segundos.

- E o total de apontamentos.

- Após a atualização é calculado novamente a data com horário novo com o tempo de inicio da ultima tarefa.

## Requisitos Básicos

- Composer
- PHP 7.4 ou superior
- Configuração do Banco de Dados

É necessário criar um banco e depois configurar o acesso ao banco de dados, siga a etapa abaixo:

Dentro do arquivo /config/db.php, configure as informações de acesso ao banco de dados, incluindo o nome do banco de dados, o nome de usuário e a senha. Por exemplo:

```dosini
  private $host = 'localhost';
  private $user = 'root';
  private $pass = '';
  private $dbname = 'employee_time_tracking';
```

Execute as queries:
```
CREATE TABLE `employee` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
  `code` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code` (`code`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4;

CREATE TABLE `appointment` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`seq` BIGINT(19) NULL DEFAULT NULL,
	`id_employe` INT(10) UNSIGNED NULL DEFAULT NULL,
	`description_work` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`enabled` TINYINT(1) NOT NULL DEFAULT '1',
	`total_time` BIGINT(19) NOT NULL DEFAULT '0',
	`start_date` DATETIME NULL DEFAULT NULL,
	`end_date` DATETIME NULL DEFAULT NULL,
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `id_employe` (`id_employe`) USING BTREE,
	CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`id_employe`) REFERENCES `employee` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=16;
```

## Instale os pacotes e dependências

`Composer install`

## Execute o projeto com o seguinte comando, optei por rodar na porta 8080.

php -S localhost:8080 -t public

Está é URL que está rodando a aplicação acesse:
`http://localhost:8080`

## Rotas criadas

| Rota                          | Descrição                       | Tipo  |
| ----------------------------- | ------------------------------- | ----- |
| api/appointment               | Lista de Apontamentos           | GET   |
| api/appointment/store         | Criação de Apontamento          | POST  |
| api/appointment/edit/{id}     | Atualização de Apontamento      | PUT   |
| api/employee                  | Lista de Funcionarios           | GET   |
| api/employee/store            | Criação de Funcionarios         | POST  |
| api/employee/edit/{id}        | Atualização de Funcionarios     | PUT   |

###  Frameworks

- Php >= 7.4
- Slim
- Composer
- MySQL
- PDO

### Design Patterns

- MVC

### Comunicação API

- JSON