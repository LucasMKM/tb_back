
create table empresario (
    id_empresario int auto_increment,
    nome varchar(100) not null,
    email varchar(100) not null,
    primary key(id_empresario)
);


create table empresas (
    id_empresa int auto_increment,
    nome varchar (100),
    cnpj int not null,
    cpf int not null,
    id_empresario int,
    primary key(id_empresa),
    foreign key (id_empresario) references empresario(id_empresario)
    
);




