create database clubelivro;

create table usuarios (
    id_us int primary key auto_increment,
    nome varchar(100) not null,
    email varchar(100) unique not null,
    telefone varchar(20),
    senha  varchar(255) not null
);

create table livros (
    id_livro int primary key auto_increment,
    titulo varchar(150) not null,
    autor varchar(100),
    ano int,
    genero varchar(50),
    disponibilidade enum('Disponível', 'Emprestado') default 'Disponível'
);

create table emprestimos (
    id_emprestimo int primary key auto_increment,
    id_us int,
    id_livro int,
    data_emprestimo date not null,
    data_devolucao date,
    status enum('Ativo', 'Devolvido') default 'Ativo',
    foreign key (id_us) references usuarios(id_us) on delete cascade,
    foreign key (id_livro) references livros(id_livro) on delete cascade
);