<?php
            
/**
 * Classe de visao para Auditoria
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
class AuditoriaView {
	public function mostraFormInserir() {
		echo '
            
            
            
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
            
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Adicionar Auditoria</h1>
									</div>
						              <form class="user" method="post">

                                        <div class="form-group">
                                            <label for="pagina">pagina</label>
                                            <input type="text" class="form-control"  name="pagina" id="pagina" placeholder="pagina">
                                        </div>

                                        <div class="form-group">
                                            <label for="ipVisitante">ipVisitante</label>
                                            <input type="text" class="form-control"  name="ipVisitante" id="ipVisitante" placeholder="ipVisitante">
                                        </div>

                                        <div class="form-group">
                                            <label for="infoSessao">infoSessao</label>
                                            <input type="text" class="form-control"  name="infoSessao" id="infoSessao" placeholder="infoSessao">
                                        </div>

                                        <div class="form-group">
                                            <label for="data">data</label>
                                            <input type="text" class="form-control"  name="data" id="data" placeholder="data">
                                        </div>
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Cadastrar" name="enviar_auditoria">
                                        <hr>
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
			</div>
';
	}
                                            
                                            
    public function exibirLista($lista){
           echo '
                                            
                                            
                                            
	<div class="card o-hidden border-0 shadow-lg my-5">
              <div class="card mb-4">
                <div class="card-header">
                  Lista
                </div>
                <div class="card-body">
                                            
                                            
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%"
				cellspacing="0">
				<thead>
					<tr>
						<th>id</th>
						<th>GET</th>
						<th>IP</th>
						<th>Sessão</th>
                        <th>Data/Hora</th>
					</tr>
				</thead>
				
				<tbody>';
            
            foreach($lista as $elemento){
                echo '<tr>';
                echo '<td>'.$elemento->getId().'</td>';
                echo '<td>'.$elemento->getPagina().'</td>';
                echo '<td>'.$elemento->getIpVisitante().'</td>';
                echo '<td>'.$elemento->getInfoSessao().'</td>';
                
                echo '<td>'.date("d/m/Y G:i:s", strtotime($elemento->getData())).'</td>';
                echo '</tr>';
            }
            
        echo '
				</tbody>
			</table>
		</div>
            
            
            
            
      </div>
  </div>
</div>
            
';
    }
            
            
        public function mostrarSelecionado(Auditoria $auditoria){
        echo '
            
	<div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card mb-4">
            <div class="card-header">
                  Auditoria selecionado
            </div>
            <div class="card-body">
                Id: '.$auditoria->getId().'<br>
                Pagina: '.$auditoria->getPagina().'<br>
                IpVisitante: '.$auditoria->getIpVisitante().'<br>
                InfoSessao: '.$auditoria->getInfoSessao().'<br>
                Data: '.$auditoria->getData().'<br>
            
            </div>
        </div>
    </div>
            
            
';
    }
            
	public function mostraFormEditar(Auditoria $auditoria) {
		echo '
	    
	    
	    
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
	    
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Adicionar Auditoria</h1>
									</div>
						              <form class="user" method="post">
                                        <div class="form-group">
                						  <input type="text" class="form-control" value="'.$auditoria->getPagina().'" id="pagina" name="pagina" placeholder="pagina">
                						</div>
                                        <div class="form-group">
                						  <input type="text" class="form-control" value="'.$auditoria->getIpVisitante().'" id="ipVisitante" name="ipVisitante" placeholder="ipVisitante">
                						</div>
                                        <div class="form-group">
                						  <input type="text" class="form-control" value="'.$auditoria->getInfoSessao().'" id="infoSessao" name="infoSessao" placeholder="infoSessao">
                						</div>
                                        <div class="form-group">
                						  <input type="text" class="form-control" value="'.$auditoria->getData().'" id="data" name="data" placeholder="data">
                						</div>
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Alterar" name="editar_auditoria">
                                        <hr>
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
                                            
	</div>';
	}
                                            
    public function confirmarDeletar(Auditoria $auditoria) {
		echo '
        
        
        
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
        
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Deletar Auditoria</h1>
									</div>
						              <form class="user" method="post">                    Tem Certeza que deseja deletar o'.$auditoria->getPagina().'
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Deletar" name="deletar_auditoria">
                                        <hr>
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
                                            
                                            
	</div>';
	}
                                            
    public function mensagem($mensagem) {
		echo '
                                            
                                            
                                            
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
                                            
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4">'.$mensagem.'</h1>
									</div>
                                            
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
	</div>';
	}
                                            
                                            
                                            

}