﻿<!DOCTYPE html>
<?php
	$id = $_GET['id'];


	// execulta a query e conta quantas linhas e traz somente um usuario
	$consulta = mysql_query("SELECT * FROM tb_manutencao m 	
								INNER JOIN tb_usuario u on m.CDUSUARIO = u.CDUSUARIO
								INNER JOIN tb_fornecedor f on m.CDFORNECEDOR = f.CDFORNECEDOR 
                                WHERE m.CDMANUTENCAO = '$id' LIMIT 1 ");
	
	$manutencao = mysql_fetch_assoc($consulta);

	$consulta_equipamentos = mysql_query("SELECT * FROM tb_equipamento_manutencao JOIN tb_equipamento ON tb_equipamento.CDEQUIPAMENTO = tb_equipamento_manutencao.CDEQUIPAMENTO WHERE tb_equipamento_manutencao.CDMANUTENCAO = '$id'");



	// while($atividades = mysql_fetch_assoc($consulta_atividades)){
	// 	echo $atividades[DSTPATENDIMENTO];
	// }
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br">
	<script type='text/javascript' src='http://files.rafaelwendel.com/jquery.js'></script>
	<script type='text/javascript' src='js/modal.js'></script>
	<script type='text/javascript' src='js/functions.js'></script>
	<script src="http://momentjs.com/downloads/moment-with-locales.js"></script>
	<script src="http://momentjs.com/downloads/moment-timezone-with-data.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.min.css">

	<head>
		<script LANGUAGE="JavaScript">
			

			function select(VALOR) {
				var $this = VALOR;
				var codigo = VALOR.value;
				var nome = VALOR.options[VALOR.selectedIndex].innerHTML
				var tr = '<tr>'+
				      '<td>'+codigo+'<input type="hidden" name="codigoequipamento[]" value="'+codigo+'" />'+
				    '<input type="hidden" name="nomeequipamento[]" value="'+nome+'" />'+'</td>'+
				      '<td>'+nome+'</td>'+'<td><button type="button" name="remover" onclick="RemoveTableRow(this)"  class="btn btn-danger">Remover </button> </td>'+
				      '</tr>'
				    $('#grid').find('tbody').append( tr );

				var hiddens = '<input type="hidden" name="codigomanutencao[]" value="'+codigo+'" />'+
				    '<input type="hidden" name="nomemanutencao[]" value="'+nome+'" />';


				    $('#form_insert').find('fieldset').append( hiddens );

				return false;
			};

			function mascara(t, mask){
			 var i = t.value.length;
			 var saida = mask.substring(1,0);
			 var texto = mask.substring(i)
			 if (texto.substring(0,1) != saida){
			 t.value += texto.substring(0,1);
			 }
		 }

		 (function($) {

			  RemoveTableRow = function(handler) {
			    var tr = $(handler).closest('tr');

			    tr.fadeOut(400, function(){ 
			      tr.remove(); 
			    }); 

			    return false;
			  };
			})(jQuery);
		</script>
	</head>
	<body>
		<div id="wrapper">     
			<div id="page-wrapper">
				<div class="container-fluid">
				<!-- Page Heading -->
					<div class="row">
						<div class="col-lg-12">
							<h1 class="page-header">
								Editar  Manutenção<small></small>
							</h1>
							<ol class="breadcrumb">
								<li class="active">
									<i class="glyphicon glyphicon-menu-right"></i> Página Inicial
								</li>
								<li class="active">
									<i class="glyphicon glyphicon-menu-right"></i> Controle de Manutenção
								</li>
								<li class="">
									<i class="glyphicon glyphicon-menu-right"></i>  Manutenção
								</li>
							</ol>
						</div>
					</div>
				</div>
				<!-- /.container-fluid -->
				<div class="container-fluid">
					<div class="row">
						<div>
							<form class="form-horizontal" role="form"  method = "POST" action = "processa/proc_editar_manutencao.php">
								<legend><font color="red">*</font> Campos Obrigatórios</legend>
								<div class="form-group">
									<label class="control-label col-sm-2" for="instrutor"><font color="red">*</font> Responsável:</label>
									<div class="col-sm-3">
										<select class="form-control" name="instrutor" readonly><option>Selecionar Responsável</option>
											<?php 
												$query = mysql_query ("select * FROM tb_usuario");
												while($res=mysql_fetch_array($query)){
													
													echo "<option value='{$res["CDUSUARIO"]}' ".(($manutencao['CDUSUARIO'] == $res["CDUSUARIO"])? 'selected' : '').">{$res["NMUSUARIO"]}</option>";

												}
											?>		
										</select>										
									</div>

									<label class="control-label col-sm-2" for="fornecedor"><font color="red">*</font> Fornecedor:</label> 
									<div class="col-sm-3"> 
										<select class="form-control" name="fornecedor" readonly><option>Selecionar Fornecedor</option>
											<?php 
												$query = mysql_query ("select * FROM tb_fornecedor");
												while($res=mysql_fetch_array($query)){

													echo "<option value='{$res["CDFORNECEDOR"]}' ".(($manutencao['CDFORNECEDOR'] == $res["CDFORNECEDOR"])? 'selected' : '').">{$res["NMFORNECEDOR"]}</option>";
												}
											?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-2" for="dtsaida"><font color="red">*</font> Data da Saída:</label>
									<div class="col-sm-2">
										<input type="text" class="form-control"  name="dtsaida" onkeypress="mascara(this, '##/##/####')" value="<?php echo $manutencao['DTSAIDA']; ?>" readonly maxlength="10">
									</div> 
									<label class="control-label col-sm-2 col-md-offset-1" for="dtretorno"><font color="red">*</font> Data do Retorno:</label>
									<div class="col-sm-2">
										<input type="text" class="form-control" id="dtretorno" value="<?php echo $manutencao['DTRETORNO']; ?>" name="dtretorno" onkeypress="mascara(this, '##/##/####')" maxlength="10">
									</div>
								</div>
								<div class="form-group">
									<div id="divmanutencao">
										<label class="control-label col-sm-2" for="descricao"><font color="red">*</font> Descrição da Manutenção:</label>
										<div class="col-sm-3">
									 	 <textarea class="form-control" rows="3"  name="descricao" id="obs" readonly required><?php echo $manutencao['DSMANUTENCAO']; ?></textarea>
									 	</div>

									 	<label class="control-label col-sm-2" for="descricao"><font color="red">*</font> Obs Retorno:</label>
										<div class="col-sm-3">
									 	 <textarea class="form-control" rows="3" name="obsretorno" id="obs" required><?php echo $manutencao['OBS']; ?></textarea>
									 	</div>
										
									
									</div> 

								</div><br>
							
							<div class="form-group">
								<label class="control-label col-md-2" for="equipamentos"><font color="red">* </font>Equipamento:</label>
								<div class="col-md-3 "> 
									<select class="form-control" onchange="select(this)" id="form_prepare" name="equipamentos"><option>Selecionar Equipamento</option>
										<?php 
											$query = mysql_query ("select * FROM tb_equipamento order by DSEQUIPAMENTO");
											while($res=mysql_fetch_array($query)){
												echo "<option value='{$res["CDEQUIPAMENTO"]}'>{$res["DSEQUIPAMENTO"]}</option>";
											}
										?>
									</select>
								</div>
								<a data-toggle="modal" data-target="#manutencao-modal" class='btn btn-xs btn-success' href=''><type='' class="glyphicon glyphicon-plus"/></a>
							</div>

								<div class="row">
									<div class="col-xs-6 col-md-6 col-md-offset-5 ">
										<table class = "table table-striped" id="grid">
											<thead>
												<tr class="info">
													<th width="130" aling="center">Nº</th>
													<th width="500" aling="center"><span class="style9"><b>Equipamento</b></span></th>  
													<th width="200" aling="center"><span class="style9"><b>Ação</b></span></th>
													
												</tr>
											</thead>
											
											<tbody>

														<?php while($equipamento = mysql_fetch_assoc($consulta_equipamentos)){
																		echo "<tr>";
																			echo '<td>'.$equipamento[CDEQUIPAMENTO].'</td>';
																			echo '<td>'.$equipamento[DSEQUIPAMENTO].'</td>';
																			//echo'<td><button type="button" name="remover" onclick="RemoveTableRow(this)"  class="btn btn-danger">Remover </button> </td>';
																		echo "</tr>";
																 } ?>
																							
											</tbody>
										</table>
									</div> <!-- fim da div col-xs-12 col-md-12 -->
								</div><!-- /.row -->

								<input type="hidden" name="id" value=" <?php echo $manutencao['CDMANUTENCAO']; ?>" >

								<div class="col-sm-offset-9 col-sm-10">
									<button type="submit"  class="btn btn-warning">Salvar Alteração</button>
									<button type="button"  onClick="JavaScript: window.history.back();" class="btn btn-primary">Cancelar</button>
								</div>
							</form>
							<!-- Modal manutencao-->
							<div class="modal fade" id="manutencao-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
												<span aria-hidden="true">&times;</span>
											</button>
											<h4 class="modal-title" id="modalLabel">Adicionar Equipamentos</h4>
										</div>
										<div class="modal-body">
											<form class="form-horizontal" role="form" method = "POST" id="ajax_formmanutencao" action = "">
												<fieldset>
													<div class="form-group">
														<label class="control-label col-sm-3" for="nome">Equipamento:</label> <font color="red">*</font>
														<div class="col-sm-4">
															<input type="text" class="form-control" name="dsequipamento" id="dsequipamento" placeholder="Equipamento" required> 
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-3" for="estconserva">Estado de Conservação:</label> <font color="red">*</font>
														<div class="col-sm-4">
															<input type="text" class="form-control" name="estconserva " id="estconserva" placeholder="Estado de Conservação" required> 
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-3" for="patrimonio">Nº Patrimonio:</label> <font color="red">*</font>
														<div class="col-sm-4">
															<input type="text" class="form-control" name="nupatrimonio" id="nupatrimonio" placeholder="Numero de patrimonio" required> 
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-3" for="patrimonio">Observação:</label> <font color="red">*</font>
														<div class="col-sm-4">
															<input type="text" class="form-control" name=" obs" id="obs" placeholder="Observação" required> 
														</div>
													</div>
												</fieldset>
												<div class="modal-footer">
													<button type="submit" id="closemodalmanutencao" class="btn btn-success">Cadastrar</button>	
												</div>
											</form>
										</div>
									</div>
								</div>
							</div> <!-- /.modal manutencao-->
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- jQuery -->
		<script src="js/jquery.js"></script>
		<script src = "js/formulario.js"></script>
		<!-- Bootstrap Core JavaScript -->
		<script src="js/bootstrap.min.js"></script>
		<!-- Morris Charts JavaScript -->
		<script src="js/jquery-ui.min.js"></script>
	    <script src="js/datepicker-pt-BR.js"></script>
	    <script>
	    		
	    		$("#dtretorno").datepicker($.datepicker.regional[ "pt-br" ]);

	    </script>
		<script src="js/plugins/morris/raphael.min.js"></script>
		<script src="js/plugins/morris/morris.min.js"></script>
		<script src="js/plugins/morris/morris-data.js"></script>
	</body>
</html>
