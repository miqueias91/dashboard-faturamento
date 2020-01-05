#Automated Email System
# faturamento
#Copy this SQL to make the system work



SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE TABLE `acess_user` (
  `id_acess_user` int(11) NOT NULL,
  `nome` varchar(256) NOT NULL,
  `usuario` varchar(256) NOT NULL,
  `senha` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `status` enum('sim','nao') NOT NULL DEFAULT 'sim',
  `token_user` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nome_cliente` varchar(256) NOT NULL,
  `email_cliente` varchar(256) DEFAULT NULL,
  `celular_cliente` varchar(12) NOT NULL,
  `logradouro_cliente` varchar(256) NOT NULL,
  `numero_cliente` varchar(256) DEFAULT NULL,
  `complemento_cliente` varchar(256) DEFAULT NULL,
  `bairro_cliente` varchar(256) NOT NULL,
  `cidade_cliente` varchar(256) NOT NULL,
  `estado_cliente` varchar(256) NOT NULL,
  `cep_cliente` varchar(256) NOT NULL,
  `data_nascimento_cliente` date NOT NULL,
  `sexo_cliente` enum('M','F','N/I') NOT NULL DEFAULT 'N/I',
  `cpf_cliente` varchar(14) NOT NULL,
  `id_representante_financeiro_cliente` int(11) DEFAULT NULL,
  `profissao_cliente` varchar(256) DEFAULT NULL,
  `status_cliente` enum('ativo','inativo') NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `configarquivobancario` (
  `id_configarquivobancario` int(11) NOT NULL,
  `descricaoconfig` varchar(256) NOT NULL,
  `nomecedente` varchar(256) NOT NULL,
  `cnpjcedente` varchar(14) NOT NULL,
  `codigobanco` varchar(5) NOT NULL,
  `numeroagencia` varchar(6) NOT NULL,
  `codigoagencia` varchar(1) NOT NULL,
  `numeroconta` varchar(20) NOT NULL,
  `digitoconta` varchar(1) NOT NULL,
  `carteira` varchar(5) NOT NULL,
  `codigocedente` varchar(20) NOT NULL,
  `numerodocumentoatual` varchar(20) NOT NULL,
  `padrao` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `ultimoIDparcelaformapagamentoboleto` int(11) NOT NULL,
  `layout` enum('240','400') NOT NULL,
  `jurosmensal` float(11,2) NOT NULL,
  `multavencimento` float(11,2) NOT NULL,
  `idcontabancaria` int(11) NOT NULL,
  `boleto` enum('sim','nao') NOT NULL DEFAULT 'sim',
  `pagamento` enum('sim','nao') NOT NULL DEFAULT 'nao',
  `status_configarquivobancario` enum('ativo','inativo') NOT NULL DEFAULT 'inativo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `contabancaria` (
  `id_contabancaria` int(11) NOT NULL,
  `numeroconta` varchar(50) DEFAULT NULL,
  `descricaocontabancaria` varchar(1024) NOT NULL,
  `status` enum('ativo','inativo') NOT NULL,
  `banco` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `contrato` (
  `id_contrato` int(11) NOT NULL,
  `tipomovimento` enum('receita','despesa') NOT NULL,
  `idcliente` int(11) NOT NULL,
  `datacadastro` datetime NOT NULL,
  `datainicio` date NOT NULL,
  `datafinal` date NOT NULL,
  `compinicial` varchar(7) NOT NULL,
  `numparcela` int(11) NOT NULL,
  `valorparcela` float(11,2) NOT NULL,
  `diavencimento` int(2) UNSIGNED ZEROFILL NOT NULL,
  `observacao` text NOT NULL,
  `nomeanexo` varchar(255) DEFAULT NULL,
  `enderecoanexo` varchar(255) DEFAULT NULL,
  `status_contrato` enum('Ativo','Inativo','Pendente') CHARACTER SET latin1 NOT NULL DEFAULT 'Ativo',
  `contrato_antigo` int(11) DEFAULT NULL COMMENT 'id do contrato antigo',
  `tipo_contrato` enum('fixo','variavel') NOT NULL DEFAULT 'fixo',
  `tipopagamento` enum('boleto','cartao','deposito','outro') DEFAULT 'outro',
  `idconfigarquivobancario` int(11) DEFAULT NULL,
  `diavencimentodesconto` int(2) UNSIGNED ZEROFILL DEFAULT NULL,
  `idservico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `contrato_desconto` (
  `id_contratodesconto` int(11) NOT NULL,
  `idcontrato` int(11) NOT NULL,
  `idtipodesconto` int(11) NOT NULL,
  `valordesconto` float(11,2) NOT NULL,
  `compdesconto` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `contrato_movimento` (
  `id_contrato_movimento` int(11) NOT NULL,
  `idcontrato` int(11) NOT NULL,
  `idmovimento` int(11) DEFAULT NULL,
  `datacadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `formamovimento` enum('principal','auxiliar') NOT NULL DEFAULT 'principal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `descontoclassificacao` (
  `id_descontoclassificacao` int(11) NOT NULL,
  `descricaoclassificacao` varchar(256) NOT NULL,
  `persisteaposvencimento` enum('nao','sim') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `descontotipo` (
  `id_descontotipo` int(11) NOT NULL,
  `descricaotipodesconto` varchar(256) NOT NULL,
  `iddescontoclassificacao` int(11) NOT NULL,
  `percentualmaximo` float(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `movimento` (
  `id_movimento` int(11) NOT NULL,
  `tipomovimento` enum('receita','despesa') NOT NULL,
  `numeromovimento` int(11) DEFAULT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `serie` varchar(5) DEFAULT NULL,
  `especie` varchar(5) DEFAULT NULL,
  `idcliente` int(11) NOT NULL,
  `descricaohistorico` text,
  `valortotal` double(11,2) NOT NULL,
  `emissao` date NOT NULL,
  `entrada` date NOT NULL,
  `comp` varchar(256) DEFAULT NULL,
  `datalancamento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tokenuser` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `movimento_log` (
  `id_movimento_log` int(11) NOT NULL,
  `operacao` enum('incluir','excluir','alterar') NOT NULL,
  `idmovimento` int(11) NOT NULL,
  `datahoralog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `observacao` varchar(256) NOT NULL,
  `tokenuser` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `movimento_parcela` (
  `id_movimento_parcela` int(11) NOT NULL,
  `idmovimento` int(11) NOT NULL,
  `vencimento` date NOT NULL,
  `vencimentooriginal` date NOT NULL,
  `valor` double(11,2) NOT NULL,
  `valorbruto` double(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `movimento_parceladesconto` (
  `id_movimentoparceladesconto` int(11) NOT NULL,
  `idmovimentoparcela` int(11) NOT NULL,
  `idtipodesconto` int(11) NOT NULL,
  `valordesconto` float(11,2) NOT NULL,
  `vencimento_desconto` date DEFAULT NULL COMMENT 'data NULL significa que o desconto persiste apos o vencimento'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `movimento_parcelaformapagamento` (
  `id_movimento_parcelaformapagamento` int(11) NOT NULL,
  `idparcela` int(11) NOT NULL,
  `documento` varchar(50) DEFAULT NULL,
  `complemento` varchar(10) DEFAULT NULL,
  `valor` double(11,2) DEFAULT NULL,
  `despesa` double(11,2) DEFAULT '0.00',
  `desconto` double(11,2) DEFAULT '0.00',
  `juros` double(11,2) DEFAULT '0.00',
  `multa` double(11,2) DEFAULT '0.00',
  `perda` double(11,2) DEFAULT '0.00',
  `outrasdeducoes` double(11,2) DEFAULT '0.00',
  `pagamento` date DEFAULT NULL,
  `valorpago` double(11,2) DEFAULT '0.00',
  `idcontabancaria` int(11) DEFAULT NULL,
  `datalancamento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipopagamento` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `movimento_parcelaformapagamentoboleto` (
  `movimento_parcelaformapagamentoboleto` int(11) NOT NULL,
  `idparcelaformapagamento` int(11) NOT NULL,
  `numerodocumento` varchar(20) NOT NULL,
  `nosso_numero` varchar(20) NOT NULL,
  `idconfigarquivobancario` int(11) NOT NULL,
  `dataemissao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DELIMITER $$
CREATE TRIGGER `bkp_boletos` BEFORE DELETE ON `movimento_parcelaformapagamentoboleto` FOR EACH ROW BEGIN
   insert into
       movimento_parcelaformapagamentoboleto_bkp
   
   SELECT
       null,
       cli.id_cliente,
       cli.nome_cliente nomecliente,
       bol.nosso_numero,
       p.valor,
       bol.dataemissao,
       p.vencimento,
       fp.pagamento
   FROM `movimento_parcelaformapagamentoboleto` bol
   INNER JOIN movimento_parcelaformapagamento fp ON bol.idparcelaformapagamento = fp.id_movimento_parcelaformapagamento
   INNER JOIN movimento_parcela p ON p.id_movimento_parcela = fp.idparcela
   INNER JOIN movimento m ON m.id_movimento = p.idmovimento
   INNER JOIN cliente cli ON cli.id_cliente = m.idcliente
   WHERE bol.movimento_parcelaformapagamentoboleto = OLD.movimento_parcelaformapagamentoboleto;
END
$$
DELIMITER ;
CREATE TABLE `movimento_parcelaformapagamentoboleto_bkp` (
  `movimento_parcelaformapagamentoboleto_bkp` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `nomecliente` varchar(256) NOT NULL,
  `nosso_numero` varchar(50) NOT NULL,
  `valor` float(11,2) NOT NULL,
  `emissao` date NOT NULL,
  `vencimento` date NOT NULL,
  `pagamento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `movimento_parcelaformapagamentodesconto` (
  `id_movimento_parcelaformapagamentodesconto` int(11) NOT NULL,
  `idparcelaformapagamento` int(11) NOT NULL,
  `iddescontoclassificacao` int(11) NOT NULL,
  `iddescontotipo` int(11) NOT NULL,
  `persisteaposvencimento` enum('nao','sim') NOT NULL,
  `valordesconto` float(11,2) NOT NULL,
  `datadesconto` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `movimento_sequencial` (
  `id_movimento_sequencial` int(11) NOT NULL,
  `tipomovimento` enum('receita','despesa') NOT NULL,
  `tokenuser` varchar(256) NOT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `servico` (
  `id_servico` int(11) NOT NULL,
  `descricao_servico` varchar(256) NOT NULL,
  `status_servico` enum('ativo','inativo') NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `acess_user`
  ADD PRIMARY KEY (`id_acess_user`);
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);
ALTER TABLE `configarquivobancario`
  ADD PRIMARY KEY (`id_configarquivobancario`),
  ADD KEY `ultimoIDparcelaformapagamentoboleto` (`ultimoIDparcelaformapagamentoboleto`),
  ADD KEY `idcontabancaria` (`idcontabancaria`);
ALTER TABLE `contabancaria`
  ADD PRIMARY KEY (`id_contabancaria`),
  ADD KEY `numeroconta` (`numeroconta`),
  ADD KEY `descricaocontabancaria` (`descricaocontabancaria`(767));
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`id_contrato`),
  ADD KEY `idcliente` (`idcliente`),
  ADD KEY `contrato_antigo` (`contrato_antigo`),
  ADD KEY `idconfigboleeto` (`idconfigarquivobancario`),
  ADD KEY `id_servico` (`idservico`);
ALTER TABLE `contrato_desconto`
  ADD PRIMARY KEY (`id_contratodesconto`),
  ADD KEY `idtipodesconto` (`idtipodesconto`),
  ADD KEY `idcontrato` (`idcontrato`);

--
-- Indexes for table `contrato_movimento`
--
ALTER TABLE `contrato_movimento`
  ADD PRIMARY KEY (`id_contrato_movimento`),
  ADD KEY `idcontrato` (`idcontrato`),
  ADD KEY `idmovimento` (`idmovimento`);

--
-- Indexes for table `descontoclassificacao`
--
ALTER TABLE `descontoclassificacao`
  ADD PRIMARY KEY (`id_descontoclassificacao`);

--
-- Indexes for table `descontotipo`
--
ALTER TABLE `descontotipo`
  ADD PRIMARY KEY (`id_descontotipo`),
  ADD KEY `iddescontoclassificacao` (`iddescontoclassificacao`);

--
-- Indexes for table `movimento`
--
ALTER TABLE `movimento`
  ADD PRIMARY KEY (`id_movimento`),
  ADD KEY `idcliente` (`idcliente`),
  ADD KEY `numeromovimento` (`numeromovimento`),
  ADD KEY `tipomovimento` (`tipomovimento`),
  ADD KEY `documento` (`documento`),
  ADD KEY `iduser` (`tokenuser`);

--
-- Indexes for table `movimento_log`
--
ALTER TABLE `movimento_log`
  ADD PRIMARY KEY (`id_movimento_log`);

--
-- Indexes for table `movimento_parcela`
--
ALTER TABLE `movimento_parcela`
  ADD PRIMARY KEY (`id_movimento_parcela`),
  ADD KEY `idmovimento` (`idmovimento`);

--
-- Indexes for table `movimento_parceladesconto`
--
ALTER TABLE `movimento_parceladesconto`
  ADD PRIMARY KEY (`id_movimentoparceladesconto`),
  ADD KEY `idtipodesconto` (`idtipodesconto`),
  ADD KEY `idmovimentoparcela` (`idmovimentoparcela`);

--
-- Indexes for table `movimento_parcelaformapagamento`
--
ALTER TABLE `movimento_parcelaformapagamento`
  ADD PRIMARY KEY (`id_movimento_parcelaformapagamento`),
  ADD KEY `idparcela` (`idparcela`),
  ADD KEY `idcontabancaria` (`idcontabancaria`);

--
-- Indexes for table `movimento_parcelaformapagamentoboleto`
--
ALTER TABLE `movimento_parcelaformapagamentoboleto`
  ADD PRIMARY KEY (`movimento_parcelaformapagamentoboleto`),
  ADD UNIQUE KEY `numerodocumento` (`numerodocumento`,`idconfigarquivobancario`),
  ADD KEY `idparcelaformapagamento` (`idparcelaformapagamento`),
  ADD KEY `idconfigarquivobancario` (`idconfigarquivobancario`);

--
-- Indexes for table `movimento_parcelaformapagamentoboleto_bkp`
--
ALTER TABLE `movimento_parcelaformapagamentoboleto_bkp`
  ADD PRIMARY KEY (`movimento_parcelaformapagamentoboleto_bkp`);

--
-- Indexes for table `movimento_parcelaformapagamentodesconto`
--
ALTER TABLE `movimento_parcelaformapagamentodesconto`
  ADD PRIMARY KEY (`id_movimento_parcelaformapagamentodesconto`),
  ADD KEY `iddescontotipo` (`iddescontotipo`),
  ADD KEY `iddescontoclassificacao` (`iddescontoclassificacao`),
  ADD KEY `idparcelaformapagamento` (`idparcelaformapagamento`);

--
-- Indexes for table `movimento_sequencial`
--
ALTER TABLE `movimento_sequencial`
  ADD PRIMARY KEY (`id_movimento_sequencial`);

--
-- Indexes for table `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`id_servico`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acess_user`
--
ALTER TABLE `acess_user`
  MODIFY `id_acess_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `configarquivobancario`
--
ALTER TABLE `configarquivobancario`
  MODIFY `id_configarquivobancario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `contabancaria`
--
ALTER TABLE `contabancaria`
  MODIFY `id_contabancaria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `contrato`
--
ALTER TABLE `contrato`
  MODIFY `id_contrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `contrato_desconto`
--
ALTER TABLE `contrato_desconto`
  MODIFY `id_contratodesconto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `contrato_movimento`
--
ALTER TABLE `contrato_movimento`
  MODIFY `id_contrato_movimento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `descontoclassificacao`
--
ALTER TABLE `descontoclassificacao`
  MODIFY `id_descontoclassificacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `descontotipo`
--
ALTER TABLE `descontotipo`
  MODIFY `id_descontotipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `movimento`
--
ALTER TABLE `movimento`
  MODIFY `id_movimento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `movimento_log`
--
ALTER TABLE `movimento_log`
  MODIFY `id_movimento_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `movimento_parcela`
--
ALTER TABLE `movimento_parcela`
  MODIFY `id_movimento_parcela` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `movimento_parceladesconto`
--
ALTER TABLE `movimento_parceladesconto`
  MODIFY `id_movimentoparceladesconto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `movimento_parcelaformapagamento`
--
ALTER TABLE `movimento_parcelaformapagamento`
  MODIFY `id_movimento_parcelaformapagamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `movimento_parcelaformapagamentoboleto`
--
ALTER TABLE `movimento_parcelaformapagamentoboleto`
  MODIFY `movimento_parcelaformapagamentoboleto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `movimento_parcelaformapagamentoboleto_bkp`
--
ALTER TABLE `movimento_parcelaformapagamentoboleto_bkp`
  MODIFY `movimento_parcelaformapagamentoboleto_bkp` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `movimento_parcelaformapagamentodesconto`
--
ALTER TABLE `movimento_parcelaformapagamentodesconto`
  MODIFY `id_movimento_parcelaformapagamentodesconto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `movimento_sequencial`
--
ALTER TABLE `movimento_sequencial`
  MODIFY `id_movimento_sequencial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `servico`
--
ALTER TABLE `servico`
  MODIFY `id_servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `configarquivobancario`
--
ALTER TABLE `configarquivobancario`
  ADD CONSTRAINT `configarquivobancario_ibfk_1` FOREIGN KEY (`idcontabancaria`) REFERENCES `contabancaria` (`id_contabancaria`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `contrato`
--
ALTER TABLE `contrato`
  ADD CONSTRAINT `contrato_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`id_cliente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `contrato_ibfk_5` FOREIGN KEY (`idconfigarquivobancario`) REFERENCES `configarquivobancario` (`id_configarquivobancario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `contrato_ibfk_6` FOREIGN KEY (`idservico`) REFERENCES `servico` (`id_servico`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `contrato_desconto`
--
ALTER TABLE `contrato_desconto`
  ADD CONSTRAINT `contrato_desconto_ibfk_1` FOREIGN KEY (`idtipodesconto`) REFERENCES `descontotipo` (`id_descontotipo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `contrato_desconto_ibfk_2` FOREIGN KEY (`idcontrato`) REFERENCES `contrato` (`id_contrato`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `contrato_movimento`
--
ALTER TABLE `contrato_movimento`
  ADD CONSTRAINT `contrato_movimento_ibfk_3` FOREIGN KEY (`idcontrato`) REFERENCES `contrato` (`id_contrato`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contrato_movimento_ibfk_5` FOREIGN KEY (`idmovimento`) REFERENCES `movimento` (`id_movimento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `descontotipo`
--
ALTER TABLE `descontotipo`
  ADD CONSTRAINT `descontotipo_ibfk_1` FOREIGN KEY (`iddescontoclassificacao`) REFERENCES `descontoclassificacao` (`id_descontoclassificacao`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `movimento`
--
ALTER TABLE `movimento`
  ADD CONSTRAINT `movimento_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`id_cliente`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `movimento_parcela`
--
ALTER TABLE `movimento_parcela`
  ADD CONSTRAINT `movimento_parcela_ibfk_1` FOREIGN KEY (`idmovimento`) REFERENCES `movimento` (`id_movimento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `movimento_parceladesconto`
--
ALTER TABLE `movimento_parceladesconto`
  ADD CONSTRAINT `movimento_parceladesconto_ibfk_1` FOREIGN KEY (`idmovimentoparcela`) REFERENCES `movimento_parcela` (`id_movimento_parcela`) ON UPDATE CASCADE,
  ADD CONSTRAINT `movimento_parceladesconto_ibfk_2` FOREIGN KEY (`idtipodesconto`) REFERENCES `descontotipo` (`id_descontotipo`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `movimento_parcelaformapagamento`
--
ALTER TABLE `movimento_parcelaformapagamento`
  ADD CONSTRAINT `movimento_parcelaformapagamento_ibfk_1` FOREIGN KEY (`idparcela`) REFERENCES `movimento_parcela` (`id_movimento_parcela`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `movimento_parcelaformapagamentoboleto`
--
ALTER TABLE `movimento_parcelaformapagamentoboleto`
  ADD CONSTRAINT `movimento_parcelaformapagamentoboleto_ibfk_1` FOREIGN KEY (`idparcelaformapagamento`) REFERENCES `movimento_parcelaformapagamento` (`id_movimento_parcelaformapagamento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movimento_parcelaformapagamentoboleto_ibfk_2` FOREIGN KEY (`idconfigarquivobancario`) REFERENCES `configarquivobancario` (`id_configarquivobancario`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `movimento_parcelaformapagamentodesconto`
--
ALTER TABLE `movimento_parcelaformapagamentodesconto`
  ADD CONSTRAINT `movimento_parcelaformapagamentodesconto_ibfk_1` FOREIGN KEY (`iddescontotipo`) REFERENCES `descontotipo` (`id_descontotipo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `movimento_parcelaformapagamentodesconto_ibfk_2` FOREIGN KEY (`iddescontoclassificacao`) REFERENCES `descontotipo` (`iddescontoclassificacao`) ON UPDATE CASCADE,
  ADD CONSTRAINT `movimento_parcelaformapagamentodesconto_ibfk_3` FOREIGN KEY (`idparcelaformapagamento`) REFERENCES `movimento_parcelaformapagamento` (`id_movimento_parcelaformapagamento`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
