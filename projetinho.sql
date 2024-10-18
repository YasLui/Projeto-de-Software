	create database bdExpressoR;
	use bdExpressoR;

	create table TbGerente (
	CdGerente MEDIUMINT primary key auto_increment,
	NmGerente varchar(100) not null,
	NmCidade varchar(100) not null,
	NmBairro varchar(100) not null,
	NmRua varchar(100) not null,
	NuResidencia SMALLINT,
	DsLogin varchar(100) not null,
	DsSenha varchar(100) not null
	);


	create table TbEntregador (
	CdEntregador MEDIUMINT primary key auto_increment,
	NmEntregador varchar(100) not null,
	NmCidade varchar(100) not null,
	NmBairro varchar(100) not null,
	NmRua varchar(100) not null,
	NuResidencia SMALLINT UNSIGNED,
	DsLogin varchar(100) not null,
	DsSenha varchar(100) not null,
	NuCNH int unsigned
	);


	create table TbVeiculo (
	CdEntregador MEDIUMINT,
	NuPlaca varchar(100) not null,
	DsModelo varchar (150),
	DsAno SMALLINT UNSIGNED,
	primary key (CdEntregador,NuPlaca),
	constraint fkcdentregador foreign key (CdEntregador) references TbEntregador (CdEntregador)
	);

	create table TbRotas (
	CdRotas MEDIUMINT UNSIGNED primary key auto_increment,
	DsBairro varchar(150)not null,
	DsCidade varchar(150)not null
	);

	create table TbEntrega (
	Cd_Entrega MEDIUMINT UNSIGNED primary key,
	HrChegada time,
	DtEntrega date,
	HrSaida time,
	CdRotas MEDIUMINT UNSIGNED,
	CdEntregador MEDIUMINT ,
	constraint fkcdrotas foreign key (CdRotas) references TbRotas (CdRotas),
	constraint fkcdEntregaentregador foreign key (CdEntregador) references TbEntregador (CdEntregador)
	);


		create table TBPacote (
		Cd_Pacote MEDIUMINT UNSIGNED primary key auto_increment,
		NmEmpresaParceira varchar(150)not null,za
		NmCidade varchar(150)not null,
		NmBairro varchar(150)not null,
		NmRua varchar(150)not null,
		NuResidencia SMALLINT UNSIGNED not null,
		QtTentativas TINYINT UNSIGNED not null,
		HrEntrega time, 
		NmRecebeu varchar(150),
		FoiEntrega varchar(3),
		Status VARCHAR(255) NOT NULL,
		HrChegadaPacote time not null,
		CdEntrega MEDIUMINT UNSIGNED ,
		constraint fkcdenmtregaPacote foreign key (CdEntrega) references TbEntrega (Cd_Entrega)
		);


	-- Inserindo entregadores	
	INSERT INTO TbEntregador (NmEntregador, NmCidade, NmBairro, NmRua, NuResidencia, DsLogin, DsSenha, NuCNH) VALUES
	('João Silva', 'Ipatinga', 'Centro', 'Rua 1', 10, 'joao.silva', 'senha123', 12345678901),
	('Maria Oliveira', 'Timóteo', 'Cariru', 'Rua 2', 20, 'maria.oliveira', 'senha456', 12345678902),
	('Pedro Santos', 'Coronel Fabriciano', 'Santa Rita', 'Rua 3', 30, 'pedro.santos', 'senha789', 12345678903),
	('Ana Paula', 'Coronel Fabriciano', 'Aeroporto', 'Rua 4', 40, 'ana.paula', 'senha321', 12345678904),
	('Lucas Mendes', 'Ipatinga', 'Bela Vista', 'Rua 5', 50, 'lucas.mendes', 'senha654', 12345678905);

	-- Inserindo rotas
	INSERT INTO TbRotas (DsBairro, DsCidade) VALUES
	('Centro', 'Ipatinga'),
	('Cariru', 'Ipatinga'),
	('Santa Cruz', 'Coronel Fabriciano'),
	('Centro', 'Coronel Fabriciano'),
	('Bela Vista', 'Ipatinga'),
	('Cidade Nova', 'Santana do Paraíso'),
	('Bethânia', 'Ipatinga');

	-- Inserindo entregas com datas e horários distintos
	INSERT INTO TbEntrega (Cd_Entrega, HrChegada, DtEntrega, HrSaida, CdRotas, CdEntregador) VALUES
	(1, '08:00:00', '2024-10-01', '08:30:00', 1, 1),
	(2, '09:00:00', '2024-10-01', '09:30:00', 2, 2),
	(3, '10:00:00', '2024-10-01', '10:30:00', 3, 1),
	(4, '11:00:00', '2024-10-01', '11:30:00', 4, 1),
	(5, '12:00:00', '2024-10-01', '12:30:00', 5, 2),
	(6, '13:00:00', '2024-10-02', '13:30:00', 1, 1),
	(7, '14:00:00', '2024-10-02', '14:30:00', 2, 2),
	(8, '15:00:00', '2024-10-03', '15:30:00', 3, 1),
	(9, '16:00:00', '2024-10-03', '16:30:00', 4, 1),
	(10, '17:00:00', '2024-10-04', '17:30:00', 5, 2),
	(11, '18:00:00', '2024-10-04', '18:30:00', 6, 1),
	(12, '19:00:00', '2024-10-05', '19:30:00', 7, 2);


	-- Inserindo mais pacotes correspondentes às entregas
	INSERT INTO TBPacote (NmEmpresaParceira, NmCidade, NmBairro, NmRua, NuResidencia, QtTentativas, CdEntrega) VALUES
	('Correios', 'Ipatinga', 'Centro', 'Rua Quarenta', 99, 1, 1),
	('FedEx', 'Ipatinga', 'Cariru', 'Rua Cinquenta', 88, 2, 2),
	('DHL Express', 'Coronel Fabriciano', 'Santa Cruz', 'Rua Setenta', 111, 3, 3),
	('Amazon Logística', 'Coronel Fabriciano', 'Centro', 'Rua Oitenta', 123, 1, 4),
	('Jamef Transportes', 'Ipatinga', 'Bela Vista', 'Rua Noventa', 145, 2, 5),
	('Total Express', 'Santana do Paraíso', 'Cidade Nova', 'Rua Cento e Cinquenta', 200, 1, 1),
	('Loggi', 'Ipatinga', 'Centro', 'Rua Cento e Sessenta', 250, 3, 1),
	('Jadlog', 'Ipatinga', 'Cariru', 'Rua Cento e Setenta', 175, 2, 2),
	('Braspress', 'Ipatinga', 'Cariru', 'Rua Cento e Oitenta', 190, 1, 2),
	('Sequoia Logística', 'Coronel Fabriciano', 'Santa Cruz', 'Rua Cento e Noventa', 198, 2, 3),
	('DHL Express', 'Coronel Fabriciano', 'Santa Cruz', 'Rua Duas Mil', 202, 2, 3),
	('Amazon Logística', 'Coronel Fabriciano', 'Centro', 'Rua Duas Mil e Um', 210, 1, 4),
	('Correios', 'Coronel Fabriciano', 'Centro', 'Rua Duas Mil e Dois', 220, 3, 4),
	('FedEx', 'Ipatinga', 'Bela Vista', 'Rua Duas Mil e Três', 230, 2, 5),
	('Total Express', 'Ipatinga', 'Bela Vista', 'Rua Duas Mil e Quatro', 240, 1, 5),
	('Loggi', 'Santana do Paraíso', 'Cidade Nova', 'Rua Duas Mil e Cinco', 250, 1, 1),
	('Amazon Logística', 'Santana do Paraíso', 'Cidade Nova', 'Rua Duas Mil e Seis', 260, 2, 1),
	('Correios', 'Ipatinga', 'Centro', 'Rua Duas Mil e Sete', 270, 3, 6),
	('FedEx', 'Ipatinga', 'Cariru', 'Rua Duas Mil e Oito', 280, 1, 7),
	('DHL Express', 'Coronel Fabriciano', 'Santa Cruz', 'Rua Duas Mil e Nove', 290, 2, 8),
	('Amazon Logística', 'Coronel Fabriciano', 'Centro', 'Rua Duas Mil e Dez', 300, 1, 9),
	('Jamef Transportes', 'Ipatinga', 'Bela Vista', 'Rua Duas Mil e Onze', 310, 3, 10),
	('Total Express', 'Santana do Paraíso', 'Cidade Nova', 'Rua Duas Mil e Doze', 320, 1, 11),
	('Loggi', 'Ipatinga', 'Bethânia', 'Rua Duas Mil e Treze', 330, 2, 12),
	('Jadlog', 'Ipatinga', 'Centro', 'Rua Duas Mil e Quatorze', 340, 1, 6),
	('Braspress', 'Ipatinga', 'Centro', 'Rua Duas Mil e Quinze', 350, 3, 6),
	('Sequoia Logística', 'Coronel Fabriciano', 'Santa Cruz', 'Rua Duas Mil e Dezesseis', 360, 1, 8),
	('DHL Express', 'Coronel Fabriciano', 'Centro', 'Rua Duas Mil e Dezessete', 370, 2, 9),
	('Amazon Logística', 'Ipatinga', 'Bela Vista', 'Rua Duas Mil e Dezoito', 380, 3, 10),
	('Correios', 'Santana do Paraíso', 'Cidade Nova', 'Rua Duas Mil e Dezenove', 390, 1, 11),
	('FedEx', 'Ipatinga', 'Bethânia', 'Rua Duas Mil e Vinte', 400, 2, 12);

	-- Inserindo pacotes correspondentes às entregas
	INSERT INTO TBPacote (NmEmpresaParceira, NmCidade, NmBairro, NmRua, NuResidencia, QtTentativas, HrEntrega, NmRecebeu, FoiEntrega, CdEntrega) VALUES
	('Correios', 'Ipatinga', 'Centro', 'Rua Vinte e Sete de Abril', 157, 2, '08:15:00', 'Carlos Silva', 'Sim', 1),
	('FedEx', 'Ipatinga', 'Cariru', 'Rua Juiz de Fora', 50, 1, '09:20:00', 'Fernanda Sousa', 'Sim', 2),
	('DHL Express', 'Coronel Fabriciano', 'Santa Cruz', 'Rua São Sebastião', 89, 3, '10:05:00', 'Lucas Pereira', 'Sim', 3),
	('Amazon Logística', 'Coronel Fabriciano', 'Centro', 'Rua João Pessoa', 276, 1, '11:15:00', 'Maria Santos', 'Sim', 4),
	('Jamef Transportes', 'Ipatinga', 'Bela Vista', 'Rua das Flores', 300, 2, '12:30:00', 'Roberto Lima', 'Não', 5),
	('Total Express', 'Santana do Paraíso', 'Cidade Nova', 'Rua Principal', 452, 1, '13:40:00', 'Claudia Ribeiro', 'Sim', 1),
	('Loggi', 'Ipatinga', 'Centro', 'Avenida Castelo Branco', 112, 2, '14:50:00', 'Ricardo Almeida', 'Sim', 1),
	('Jadlog', 'Ipatinga', 'Cariru', 'Rua Santo Agostinho', 78, 2, '15:55:00', 'Juliana Costa', 'Não', 2),
	('Braspress', 'Ipatinga', 'Cariru', 'Rua Carlos Drummond', 65, 1, '16:30:00', 'André Martins', 'Sim', 2),
	('Sequoia Logística', 'Coronel Fabriciano', 'Santa Cruz', 'Rua São Vicente', 142, 3, '17:15:00', 'Patrícia Gomes', 'Sim', 3),
	('DHL Express', 'Coronel Fabriciano', 'Santa Cruz', 'Rua Barão de Cocais', 201, 2, '18:25:00', 'Fernanda Alves', 'Sim', 3),
	('Amazon Logística', 'Coronel Fabriciano', 'Centro', 'Rua Bahia', 90, 1, '19:05:00', 'Marcos Oliveira', 'Não', 4),
	('Correios', 'Coronel Fabriciano', 'Centro', 'Rua São João', 57, 2, '19:45:00', 'Ana Clara', 'Sim', 4),
	('FedEx', 'Ipatinga', 'Bela Vista', 'Rua dos Lírios', 111, 1, '08:30:00', 'Robson Dias', 'Sim', 5),
	('Total Express', 'Ipatinga', 'Bela Vista', 'Rua das Acácias', 89, 3, '09:45:00', 'Juliana Mendes', 'Não', 5),
	('Loggi', 'Santana do Paraíso', 'Cidade Nova', 'Rua das Palmeiras', 176, 2, '10:30:00', 'Danilo Nascimento', 'Sim', 1),
	('Amazon Logística', 'Santana do Paraíso', 'Cidade Nova', 'Rua Três Corações', 315, 1, '11:15:00', 'Sofia Reis', 'Sim', 1),
	('Correios', 'Ipatinga', 'Centro', 'Rua Trinta de Abril', 320, 1, '12:00:00', 'Rafael Martins', 'Sim', 6),
	('FedEx', 'Ipatinga', 'Cariru', 'Rua São Paulo', 155, 2, '13:30:00', 'Aline Ferreira', 'Não', 7),
	('DHL Express', 'Coronel Fabriciano', 'Santa Cruz', 'Rua Minas Gerais', 58, 1, '14:50:00', 'Felipe Costa', 'Sim', 8),
	('Amazon Logística', 'Coronel Fabriciano', 'Centro', 'Rua Paraíba', 189, 1, '15:30:00', 'Carla Lima', 'Sim', 9),
	('Jamef Transportes', 'Ipatinga', 'Bela Vista', 'Rua Jacarandá', 244, 3, '16:00:00', 'Luana Andrade', 'Não', 10),
	('Total Express', 'Santana do Paraíso', 'Cidade Nova', 'Rua Aroeira', 390, 1, '17:20:00', 'Gustavo Pires', 'Sim', 11),
	('Loggi', 'Ipatinga', 'Bethânia', 'Rua das Laranjeiras', 112, 1, '18:00:00', 'Bianca Melo', 'Sim', 12),
	('Jadlog', 'Ipatinga', 'Centro', 'Avenida Minas Gerais', 420, 2, '18:50:00', 'Rogério Santos', 'Sim', 6),
	('Braspress', 'Ipatinga', 'Centro', 'Rua Jacinto', 35, 1, '19:40:00', 'Eduarda Silva', 'Não', 6),
	('Sequoia Logística', 'Coronel Fabriciano', 'Santa Cruz', 'Rua Vinte e Um de Abril', 299, 2, '20:30:00', 'Anderson Lima', 'Sim', 8),
	('DHL Express', 'Coronel Fabriciano', 'Centro', 'Rua XV de Novembro', 150, 2, '21:00:00', 'Julio Gomes', 'Sim', 9),
	('Amazon Logística', 'Ipatinga', 'Bela Vista', 'Rua Castanheiras', 300, 3, '22:15:00', 'Tatiane Araújo', 'Sim', 10),
	('Correios', 'Santana do Paraíso', 'Cidade Nova', 'Rua Barão do Rio Branco', 75, 1, '23:30:00', 'Sérgio Santos', 'Sim', 11),
	('FedEx', 'Ipatinga', 'Bethânia', 'Rua Sete de Setembro', 130, 1, '08:05:00', 'Karla Oliveira', 'Sim', 12);

	/*Pacotes Entregues por Entregador*/
	SELECT TbEntrega.CdEntregador,
		   TbEntregador.NmEntregador,
		   COUNT(*) AS TotalPacotesNaoEntregues
	FROM TbEntrega
	INNER JOIN TBPacote ON TbEntrega.Cd_Entrega = TBPacote.CdEntrega
	INNER JOIN TbEntregador ON TbEntrega.CdEntregador = TbEntregador.CdEntregador
	WHERE TBPacote.FoiEntrega = 'sim'
	GROUP BY TbEntrega.CdEntregador, TbEntregador.NmEntregador;


	/*Numeros de entrega por data*/
	SELECT TbEntrega.DtEntrega,
		   COUNT(DISTINCT TbEntrega.Cd_Entrega) AS Quantidade_Entregas,
		   GROUP_CONCAT(DISTINCT TbEntregador.NmEntregador) AS Entregadores
	FROM TbEntrega
	INNER JOIN TbEntregador ON TbEntrega.CdEntregador = TbEntregador.CdEntregador
	GROUP BY TbEntrega.DtEntrega;


	/*o total de pacotes entregues agrupados pela empresa parceira*/
	SELECT TBPacote.Cd_Pacote, 
		   TbEntregador.NmEntregador, 
		   TBPacote.NmEmpresaParceira, 
		   COUNT(TBPacote.Cd_Pacote) AS TotalPacotesEntregues
	FROM TBPacote
	JOIN TbEntrega ON TBPacote.CdEntrega = TbEntrega.Cd_Entrega
	JOIN TbEntregador ON TbEntrega.CdEntregador = TbEntregador.CdEntregador
	WHERE TBPacote.FoiEntrega = 'Sim'
	GROUP BY TBPacote.Cd_Pacote, TbEntregador.NmEntregador, TBPacote.NmEmpresaParceira;


