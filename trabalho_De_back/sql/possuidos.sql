create database gerenciamento_empresarial;

use gerenciamento_empresarial;

create table clientes (
cliente_id int auto_increment,
nome varchar(100) not null,
senha varchar(100) not null,
email varchar(100) not null unique,
primary key (cliente_id)
);

create table empresas (
empresa_id int auto_increment not null,
nome varchar(100) not null,
natureza_juridica varchar(30) not null,
codigo_secreto char(3) not null,
endereco varchar(100) not null,
primary key (empresa_id)
);

create table dono_empresa (
cliente_id int not null,
empresa_id int not null,
foreign key (cliente_id) references clientes(cliente_id),
foreign key (empresa_id) references empresas(empresa_id)
);

create table cargos(
cargo_id int auto_increment not null,
nome varchar(100) not null,
salario dec (10,2) not null,
primary key (cargo_id)
);

create table funcionarios (
funcionario_id int auto_increment,
nome varchar(100) not null,
email varchar(100) not null, 
cargo_id int,
empresa_id int,
primary key(funcionario_id),
foreign key (empresa_id) references empresas(empresa_id),
foreign key (cargo_id) references cargos(cargo_id)
);


insert into cargos(cargo_id, nome, salario) values (null, "diretor", 7500);
insert into cargos(cargo_id, nome, salario) values (null, "gerente", 3700);
insert into cargos(cargo_id, nome, salario) values (null, "estagiario", 900);
insert into cargos(cargo_id, nome, salario) values (null, "desenvolvedor", 3500);






