CREATE DATABASE IF NOT EXISTs `leitorINPI`;

DROP TABLE IF EXISTS `cfg_despachos`;

CREATE TABLE `cfg_despachos` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `nomeXML` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

/*Data for the table `cfg_despachos` */

insert  into `cfg_despachos`(`id`,`codigo`,`nome`,`nomeXML`) values 
(1,'IPAS024','Indeferimento do pedido',NULL),
(2,'IPAS658','Indeferimento do pedido (em retificação)',NULL),
(3,'IPAS423','Notificação de oposição',NULL),
(4,'IPAS668','Notificação de oposição (em retificação)',NULL),
(5,'IPAS106','Por falta de procuração',NULL),
(6,'IPAS289','Por falta de documentos de marca de certificação',NULL),
(7,'IPAS291','Por falta de documentos de marca coletiva',NULL),
(8,'IPAS139','Por falta de cumprimento de exigência de mérito',NULL),
(9,'IPAS157','Por falta de pagamento da concessão',NULL),
(10,'IPAS270','Exame de mérito: Deferimento','Deferimento da petição'),
(11,'IPAS349','Exame de mérito: Deferimento','Deferimento parcial da petição'),
(12,'IPAS267','Exame de mérito: Exigências','Exigência de mérito (em petição)'),
(13,'IPAS271','Exame de mérito: Indeferimento','Indeferimento da petição'),
(14,'IPAS699','Ato de prejudicar petição','Ato de prejudicar petição'),
(15,'IPAS185','Arquivamento de petição por falta de procuração',NULL),
(16,'IPAS089','Exigência de pagamento (em petição)',NULL),
(17,'IPAS161','Extinção de registro pela expiração do prazo de vigência',NULL),
(18,'IPAS304','Extinção de registro pela caducidade',NULL),
(19,'IPAS400','Notificação de nulidade administrativa, por requerimento de terceiros, para manifestação','Notificação de instauração de processo de nulidade a requerimento'),
(20,'IPAS532','Requerimento não provido (mantida a concessão)','Requerimento não provido (mantida a concessão)'),
(21,'IPAS530','Decisão da nulidade: Provimento','Requerimento provido (nulo o registro)'),
(22,'IPAS428','Decisão de não conhecer da petição','Decisão de não conhecer da petição'),
(23,'IPAS338','Notificação de caducidade para manifestação','Notificação de caducidade'),
(26,'IPAS669','Exame de mérito: Deferimento parcial','Deferimento da petição de caducidade'),
(28,'IPAS534','Decisão da nulidade: Provimento parcial','Requerimento provido parcialmente (outros)'),
(29,'IPAS499','Instrução técnica: Sobrestamento','Sobrestamento da instrução técnica'),
(30,'IPAS136','Exame de mérito: exigência','Exigência de mérito');


DROP TABLE IF EXISTS `cfg_nice`;

CREATE TABLE `cfg_nice` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `classe` int(200) DEFAULT NULL,
  `especificacao` varchar(255) NOT NULL,
  `num_base` int(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4;

insert  into `cfg_nice`(`id`,`classe`,`especificacao`,`num_base`) values 
(1,35,'Administração comercial do licenciamento de produtos e serviços de terceiros',350096),
(2,35,'Aluguel de espaço publicitário',350070),
(3,35,'Aluguel de material publicitário',350035),
(4,35,'Aluguel de tempo de publicidade em meios de comunicação',350087),
(5,35,'Análise de custo',350007),
(6,35,'Apresentação de produtos em meios de comunicação para fins de comércio varejista',350092),
(7,35,'Assessoria em gestão comercial ou industrial',350025),
(8,35,'Assessoria em gestão industrial ou comercial',350025),
(9,35,'Assistência administrativa para responder a licitações',350154),
(10,35,'Assistência administrativa para responder a solicitações de propostas [RFP] de prestação de serviços',350154),
(11,35,'Atualização de material publicitário',350027),
(12,35,'Atualização e manutenção de informações em registros',350134),
(13,35,'Auditoria contábil e financeira',350144),
(14,35,'Auditoria em negócios',350017),
(15,35,'Auxílio em gestão de negócios',350001),
(16,35,'Avaliações de negócios',350032),
(17,35,'Comerciais de rádio',350040),
(18,35,'Comerciais de televisão',350044),
(19,35,'Compilação de índices de informações para fins comerciais ou publicitários',350135),
(20,35,'Consultoria em gestão de negócios',350020),
(21,35,'Consultoria em gestão de pessoal',350019),
(22,35,'Consultoria em gestão e organização de negócios',350018),
(23,35,'Consultoria em organização de negócios',350036),
(24,35,'Consultoria profissional em negócios',350062),
(25,35,'Consultoria referente a estratégias de comunicação em publicidade',350139),
(26,35,'Consultoria referente a estratégias de comunicação em relações públicas',350138),
(27,35,'Contabilidade',350015),
(28,35,'Desenvolvimento de conceitos de campanha publicitária',350121),
(29,35,'Determinação de perfis [profiling] de consumidores para fins comerciais ou de marketing',350164),
(30,35,'Distribuição de material publicitário',350008),
(31,35,'Elaboração de extratos de contas',350016),
(32,35,'Especialistas em eficiência de negócios',350029),
(33,35,'Estudos de marketing',350031),
(34,35,'Faturamento',350098),
(35,35,'Gestão administrativa terceirizada para empresas',350122),
(36,35,'Gestão computadorizada de arquivos',350061),
(37,35,'Gestão de negócios para provedores de serviços freelance',350115),
(38,35,'Gestão interina de negócios',350151),
(39,35,'Guarda-livro [contabilidade]',350015),
(40,35,'Indexação na web para fins comerciais ou publicitários',350127),
(41,35,'Investigações [estudo] sobre negócios',350033),
(42,35,'Levantamentos de informações de negócios',350002),
(43,35,'Marketing',350106),
(44,35,'Marketing direcionado',350150),
(45,35,'Marketing no âmbito de publicação de softwares',350155),
(46,35,'Negociação de contratos de negócios para terceiros',350140),
(47,35,'Negociação e conclusão de transações comerciais para terceiros',350116),
(48,35,'Organização de eventos de moda para fins promocionais',350103),
(49,35,'Organização de exposições para fins comerciais ou publicitários',350064),
(50,35,'Pesquisa de marketing',350051),
(51,35,'Pesquisa em negócios',350041),
(52,35,'Pesquisas de opinião',350066),
(53,35,'Preparação de declarações de impostos',350073),
(54,35,'Preparação de folha de pagamento',350067),
(55,35,'Previsões econômicas',350063),
(56,35,'Produção de filmes publicitários',350104),
(57,35,'Promoção de vendas para terceiros',350071),
(58,35,'Propaganda',350039),
(59,35,'Provimento de avaliações feitas por usuários para fins comerciais ou publicitários',350161),
(60,35,'Provimento de classificações feitas por usuários para fins comerciais ou publicitários',350161),
(61,35,'Provimento de críticas feitas por usuários para fins comerciais ou publicitários',350160),
(62,35,'Provimento de informação comercial e de aconselhamento a consumidores para escolha de produtos e serviços',350093),
(63,35,'Provimento de informações sobre contatos comerciais e de negócios',350110),
(64,35,'Provimento de informações de negócios',350065),
(65,35,'Provimento de informações de negócios através de um website',350119),
(66,35,'Publicação de textos publicitários',350038),
(67,35,'Publicidade',350039),
(68,35,'Publicidade de rádio',350040),
(69,35,'Publicidade de televisão',350044),
(70,35,'Publicidade externa',350152),
(71,35,'Publicidade on-line em rede de computadores',350084),
(72,35,'Publicidade pay-per-click',350113),
(73,35,'Publicidade por catálogos de vendas',350077),
(74,35,'Publicidade por mala direta',350024),
(75,35,'Redação de roteiros para fins publicitários',350132),
(76,35,'Redação de textos publicitários',350099),
(77,35,'Registro de dados e comunicação escritos',350133),
(78,35,'Relações públicas',350042),
(79,35,'Serviço de declaração de impostos',350123),
(80,35,'Serviço de intermediação de negócios com a finalidade de unir investidores particulares em potencial a empreendedores que precisem de recursos',350136),
(81,35,'Serviços de afixação de mensagens publicitárias',350003),
(82,35,'Serviços de agências de informação comercial',350006),
(83,35,'Serviços de agências de propaganda',350047),
(84,35,'Serviços de agências de publicidade',350047),
(85,35,'Serviços de assessoria de imprensa',350156),
(86,35,'Serviços de assessoria em gestão de negócios',350048),
(87,35,'Serviços de comunicação corporativa',350157),
(88,35,'Serviços de inteligência competitiva',350142),
(89,35,'Serviços de inteligência de mercado',350143),
(90,35,'Serviços de intermediação comercial',350114),
(91,35,'Serviços de layout para fins publicitários',350101),
(92,35,'Serviços de lobby comercial',350159),
(93,35,'Serviços de marcação de compromissos [funções de escritório]',350129),
(94,35,'Serviços de telemarketing',350107),
(95,35,'Terceirização [assistência empresarial]',350097),
(96,45,'Aconselhamento jurídico para responder a licitações',450235),
(97,45,'Aconselhamento jurídico para responder a solicitações de propostas [RFP] de prestação de serviços',450235),
(98,45,'Administração legal de licenças',450223),
(99,45,'Auditoria para fins de conformidade jurídica [compliance jurídico]',450247),
(100,45,'Auditoria para fins de conformidade regulatória [compliance regulatório]',450246),
(101,45,'Consultoria em propriedade intelectual',450206),
(102,45,'Gestão de direitos autorais',450207),
(103,45,'Leasing de nomes de domínio da internet',450233),
(104,45,'Licenciamento de programa de computador [serviços jurídicos]',450212),
(105,45,'Licenciamento de propriedade intelectual',450208),
(106,45,'Licenciamento [serviços jurídicos] no âmbito de publicação de softwares',450236),
(107,45,'Mediação',450201),
(108,45,'Pesquisas jurídicas',450210),
(109,45,'Registro de nomes de domínio [serviços jurídicos]',450213),
(110,45,'Serviço de monitoramento de direitos de propriedade intelectual para fins de aconselhamento jurídico',450209),
(111,45,'Serviços de arbitragem',450205),
(112,45,'Serviços de consultoria jurídica relativa a mapeamento de patentes',450239),
(113,45,'Serviços de legal advocacy [serviços jurídicos para suporte a causas de cunho social]',450240),
(114,45,'Serviços de monitoramento jurídico',450237),
(115,45,'Serviços extrajudiciais de resolução de disputas',450214),
(116,45,'Serviços jurídicos',450211),
(117,45,'Serviços jurídicos em matéria de imigração',450244),
(118,45,'Serviços jurídicos relativos à negociação de contratos para terceiros',450230);


DROP TABLE IF EXISTS `cfg_servicos`;

CREATE TABLE `cfg_servicos` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `planilha` varchar(20) NOT NULL,
  `procurador` int(1) NOT NULL DEFAULT 0,
  `procurador_processo` int(1) NOT NULL DEFAULT 1,
  `ordem` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;


insert  into `cfg_servicos`(`id`,`codigo`,`nome`,`planilha`,`procurador`,`procurador_processo`,`ordem`) values 
(1,NULL,'Indeferimento','Indeferimento',0,1,0),
(2,NULL,'Oposição','Oposição',0,1,1),
(3,NULL,'Pedidos de registro de marca definitivamente arquivados','Arquivados',0,1,5),
(4,'3831','Desistências em pedido de registro de marca','Desistências',1,0,3),
(5,NULL,'Registros de marca extintos','Extintos',0,1,4),
(6,'3361','Nulidades administrativas de registros de marca','Nulidade',0,1,2),
(7,'3371','Caducidades de registros de marca','Caducidade',1,1,6),
(8,'3911','Protocolo Interno','Nulidade',0,1,2),
(9,NULL,'Exigência de mérito','Merito',0,1,7);


DROP TABLE IF EXISTS `cfg_servicos_despachos`;

CREATE TABLE `cfg_servicos_despachos` (
  `id` bigint(200) NOT NULL AUTO_INCREMENT,
  `id_servico` int(50) NOT NULL,
  `id_despacho` int(50) DEFAULT NULL,
  `paint` int(5) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `id_servico` (`id_servico`),
  KEY `id_despacho` (`id_despacho`),
  CONSTRAINT `cfg_servicos_despachos_ibfk_1` FOREIGN KEY (`id_servico`) REFERENCES `cfg_servicos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `cfg_servicos_despachos_ibfk_2` FOREIGN KEY (`id_despacho`) REFERENCES `cfg_despachos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;


insert  into `cfg_servicos_despachos`(`id`,`id_servico`,`id_despacho`,`paint`) values 
(1,1,1,0),
(2,1,2,0),
(3,2,3,0),
(4,2,4,0),
(5,3,6,0),
(6,3,7,0),
(7,3,8,0),
(8,3,9,0),
(9,4,10,0),
(10,5,17,0),
(11,5,18,0),
(12,6,19,3),
(13,6,20,0),
(14,6,21,0),
(15,7,23,0),
(16,7,11,0),
(17,7,26,0),
(18,6,28,0),
(19,6,12,0),
(20,6,29,0),
(21,8,20,0),
(22,9,30,0);


DROP TABLE IF EXISTS `inpidespachos`;

CREATE TABLE `inpidespachos` (
  `id` bigint(200) NOT NULL AUTO_INCREMENT,
  `id_processo` bigint(200) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `textoComplementar` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_processo` (`id_processo`),
  CONSTRAINT `inpidespachos_ibfk_1` FOREIGN KEY (`id_processo`) REFERENCES `inpiprocessos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22705 DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `inpinice`;

CREATE TABLE `inpinice` (
  `id` bigint(200) NOT NULL AUTO_INCREMENT,
  `id_processo` bigint(200) NOT NULL,
  `classe` int(200) NOT NULL,
  `especificacao` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `especificacao` (`especificacao`)
) ENGINE=InnoDB AUTO_INCREMENT=274866 DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `inpiprocessos`;

CREATE TABLE `inpiprocessos` (
  `id` bigint(200) NOT NULL AUTO_INCREMENT,
  `id_revista` bigint(200) NOT NULL,
  `processo` varchar(50) NOT NULL,
  `procurador` varchar(2558) DEFAULT NULL,
  PRIMARY KEY (`id`,`id_revista`),
  KEY `id_revista` (`id_revista`),
  CONSTRAINT `inpiprocessos_ibfk_1` FOREIGN KEY (`id_revista`) REFERENCES `inpirevista` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22534 DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `inpiprocessostitulares`;

CREATE TABLE `inpiprocessostitulares` (
  `id` bigint(200) NOT NULL AUTO_INCREMENT,
  `id_processo` bigint(200) NOT NULL,
  `titular` varchar(200) NOT NULL,
  `pais` varchar(5) DEFAULT NULL,
  `estado` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_processo` (`id_processo`),
  CONSTRAINT `inpiprocessostitulares_ibfk_1` FOREIGN KEY (`id_processo`) REFERENCES `inpiprocessos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20741 DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `inpiprotocolos`;

CREATE TABLE `inpiprotocolos` (
  `id_despacho` bigint(200) NOT NULL,
  `numero` bigint(100) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `codigoServico` varchar(50) DEFAULT NULL,
  `requerente` varchar(255) DEFAULT NULL,
  `pais` varchar(5) DEFAULT NULL,
  `estado` varchar(5) DEFAULT NULL,
  `procurador` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_despacho`),
  CONSTRAINT `inpiprotocolos_ibfk_1` FOREIGN KEY (`id_despacho`) REFERENCES `inpidespachos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `inpirevista`;

CREATE TABLE `inpirevista` (
  `id` bigint(200) NOT NULL AUTO_INCREMENT,
  `numero` varchar(50) NOT NULL,
  `data` date NOT NULL,
  `ok` int(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `user_password` varchar(200) DEFAULT NULL,
  `user_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

