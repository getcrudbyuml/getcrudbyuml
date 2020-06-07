<?php




class ViewGerador{
    private $software;
    private $listaDeArquivos;
    private $diretorio;
    public static function main(Software $software, $diretorio){
        $gerador = new ViewGerador($software, $diretorio);
        $gerador->gerarCodigo();
        
    }
    
    public function __construct(Software $software, $diretorio){
        $this->software = $software;
        $this->diretorio = $diretorio;
    }
    /**
     * Selecione uma linguagem
     * @param int $linguagem
     */
    public function gerarCodigo(){
        foreach($this->software->getObjetos() as $objeto){
            $this->geraViews($objeto);
        }
        
        $this->criarArquivos();
        
    }
    private function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/classes/view/';
        
        if(!file_exists($caminho)) {
            mkdir($caminho, 0777, true);
        }
        
        foreach ($this->listaDeArquivos as $path => $codigo) {
            
            if (file_exists($path)) {
                unlink($path);
            }
            $file = fopen($path, "w+");
            fwrite($file, stripslashes($codigo));
            fclose($file);
        }
    }
    private function formInserir(Objeto $objeto) : string {
        $codigo = '';
        
        
        $atributosComuns = array();
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->tipoListado()){
                $atributosComuns[] = $atributo;
            }else if($atributo->isObjeto())
            {
                $atributosObjetos[] = $atributo;
            }
        }
        $codigo = '
public function mostraFormInserir(';
        $i = count($atributosObjetos);
        foreach($atributosObjetos as $atributoObjeto){
            $i--;
            $codigo .= '$lista'.ucfirst($atributoObjeto->getNome());
            if($i != 0){
                $codigo .= ', ';
            }
            
        }
        $codigo .= ') {
		echo \'
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Adicionar
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Adicionar '.$objeto->getNomeTextual().'</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        


          <form id="form_enviar_'.$objeto->getNomeSnakeCase().'" class="user" method="post">
            <input type="hidden" name="enviar_' . $objeto->getNomeSnakeCase() . '" value="1">                

';
        foreach ($atributosComuns as $atributo) {
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            
            $codigo .= '

                                        <div class="form-group">
                                            <label for="' . $atributo->getNome(). '">' . $atributo->getNomeTextual(). '</label>
                                            <input type="text" class="form-control"  name="' . $atributo->getNome(). '" id="' . $atributo->getNome(). '" placeholder="' . $atributo->getNomeTextual(). '">
                                        </div>';
        }
        foreach($atributosObjetos as $atributo){
            $codigo .= '
                                        <div class="form-group">
                                          <label for="' . $atributo->getNome(). '">' . $atributo->getNomeTextual(). '</label>
                						  <select class="form-control" id="' . $atributo->getNome() . '" name="' . $atributo->getNome(). '">
                                            <option value="">Selecione o '.$atributo->getNomeTextual().'</option>\';
                                                
        foreach( $lista'.ucfirst($atributoObjeto->getNome()).' as $elemento){
            echo \'<option value="\'.$elemento->getId().\'">\'.$elemento.\'</option>\';
        }
            
        echo \'
                                          </select>
                						</div>';
            
        }
        
        $codigo .= '

						              </form>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button form="form_enviar_'.$objeto->getNomeSnakeCase().'" type="submit" class="btn btn-primary">Cadastrar</button>
      </div>
    </div>
  </div>
</div>
            
            
			
\';
	}



';
        return $codigo;
    }
    private function exibirLista(Objeto $objeto){
        $codigo = '';
        $nomeDoObjeto = strtolower($objeto->getNome());
        
        
        $atributosComuns = array();
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->tipoListado()){
                $atributosComuns[] = $atributo;
            }
            else if($atributo->isObjeto())
            {
                $atributosObjetos[] = $atributo;
            }
        }
        
        $codigo .= '                                            
                                            
    public function exibirLista($lista){
           echo \'
                                            
                                            
                                            

          <div class="card mb-4">
                <div class="card-header">
                  Lista '.$objeto->getNomeTextual().'
                </div>
                <div class="card-body">
                                            
                                            
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%"
				cellspacing="0">
				<thead>
					<tr>';
        $i = 0;
        foreach($atributosComuns as $atributo){
            $i++;
            if($i >= 5){
                break;
            }
            $codigo .= '
						<th>'.$atributo->getNomeTextual().'</th>';
            
        }
        $i = 0;
        foreach($atributosObjetos as $atributo){
            $i++;
            if($i >= 5){
                break;
            }
            $codigo .= '
						<th>'.$atributo->getNomeTextual().'</th>';
            
        }
        $codigo .= '
                        <th>Ações</th>';
        $codigo .= '
					</tr>
				</thead>
				<tfoot>
					<tr>';
        $i = 0;
        foreach($atributosComuns as $atributo){
            $i++;
            if($i >= 5){
                break;
            }
            $codigo .= '
                        <th>'.$atributo->getNomeTextual().'</th>';
        }
        $i = 0;
        foreach($atributosObjetos as $atributo){
            $i++;
            if($i >= 5){
                break;
            }
            $codigo .= '
						<th>'.$atributo->getNomeTextual().'</th>';
            
        }
        $codigo .= '
                        <th>Ações</th>';
        $codigo .= '
					</tr>
				</tfoot>
				<tbody>';
        $codigo .= '\';';
        
        $codigo .= '
            
            foreach($lista as $elemento){
                echo \'<tr>\';';
        $i = 0;
        foreach($atributosComuns as $atributo){
            $i++;
            if($i >= 5){
                break;
            }
            $codigo .= '
                echo \'<td>\'.$elemento->get'.ucfirst ($atributo->getNome()).'().\'</td>\';';
        }
        $i = 0;
        foreach($atributosObjetos as $atributo){
            $i++;
            if($i >= 5){
                break;
            }
            $codigo .= '
                echo \'<td>\'.$elemento->get'.ucfirst ($atributo->getNome()).'().\'</td>\';';
        }
        $codigo .= '
                echo \'<td>
                        <a href="?pagina='.$nomeDoObjeto.'&selecionar=\'.$elemento->get'.ucfirst ($objeto->getAtributos()[0]->getNome()).'().\'" class="btn btn-info">Selecionar</a>
                        <a href="?pagina='.$nomeDoObjeto.'&editar=\'.$elemento->get'.ucfirst ($objeto->getAtributos()[0]->getNome()).'().\'" class="btn btn-success">Editar</a>
                        <a href="?pagina='.$nomeDoObjeto.'&deletar=\'.$elemento->get'.ucfirst ($objeto->getAtributos()[0]->getNome()).'().\'" class="btn btn-danger">Deletar</a>
                      </td>\';';
        
        $codigo .= '
                echo \'</tr>\';
            }
            
        ';
        
        $codigo .= 'echo \'';
        $codigo .= '
				</tbody>
			</table>
		</div>
            
            
            
            
  </div>
</div>
            
\';
    }
            ';
        return $codigo;
    }
    private function mostrarSelecionado(Objeto $objeto) : string {
        $codigo = '';
        $nomeDoObjeto = strtolower($objeto->getNome());
        
        
        $atributosComuns = array();
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->tipoListado()){
                $atributosComuns[] = $atributo;
            }
            else if($atributo->isObjeto())
            {
                $atributosObjetos[] = $atributo;
            }
        }
        $codigo = '


            
        public function mostrarSelecionado('.$objeto->getNome().' $'.$nomeDoObjeto.'){
            echo \'
            
	<div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card mb-4">
            <div class="card-header">
                  '.$objeto->getNome().' selecionado
            </div>
            <div class="card-body">';
        
        foreach($atributosComuns as $atributo){
            $codigo .= '
                '.ucfirst($atributo->getNome()).': \'.$'.$nomeDoObjeto.'->get'.ucfirst ($atributo->getNome()).'().\'<br>';
        }
        
        foreach($atributosObjetos as $atributo){
            $codigo .= '
                '.ucfirst($atributo->getNome()).': \'.$'.$nomeDoObjeto.'->get'.ucfirst ($atributo->getNome()).'().\'<br>';
        }
        
        $codigo .= '
            
            </div>
        </div>
    </div>
            
            
\';
    }
';
        return $codigo;
    }
    private function formEditar(Objeto $objeto) : string {
        $codigo = '';
        $nomeDoObjeto = strtolower($objeto->getNome());
        
        
        $atributosComuns = array();

        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->tipoListado()){
                $atributosComuns[] = $atributo;
            }
        }
        
        $codigo = '

            
	public function mostraFormEditar('.$objeto->getNome().' $'.$nomeDoObjeto.') {
		echo \'
	    
	    
	    
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
	    
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Adicionar ' . $objeto->getNome() . '</h1>
									</div>
						              <form class="user" method="post">';
        
        
        foreach ($atributosComuns as $atributo) {
            $variavel = $atributo->getNome();
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            $codigo .= '
                                        <div class="form-group">
                						  <input type="text" class="form-control" value="\'.$'.$nomeDoObjeto.'->get'.ucfirst ($atributo->getNome()).'().\'" id="' . $variavel . '" name="' . $variavel . '" placeholder="' . $variavel . '">
                						</div>';
        }
        
        $codigo .= '
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Alterar" name="editar_' . $nomeDoObjeto . '">
                                        <hr>
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
                                            
	</div>\';
	}

';
        return $codigo;
    }
    private function confirmarDeletar(Objeto $objeto) : string {
        $codigo = '';
        $nomeDoObjeto = strtolower($objeto->getNome());
        
        
        $atributosComuns = array();

        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->tipoListado()){
                $atributosComuns[] = $atributo;
            }
        }
        
        $codigo  = '

                                            
    public function confirmarDeletar('.$objeto->getNome().' $'.$nomeDoObjeto.') {
		echo \'
        
        
        
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
        
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Deletar ' . $objeto->getNome() . '</h1>
									</div>
						              <form class="user" method="post">';
        
        foreach ($atributosComuns as $atributo) {
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            
        }
        
        
        
        $codigo .= '                    Tem Certeza que deseja deletar o';
        if(count($objeto->getAtributos()) > 1){
            $codigo .= '\'.$'.$nomeDoObjeto.'->get'.ucfirst ($objeto->getAtributos()[1]->getNome()).'().\'';
        }else{
            $codigo .= '\'.$'.$nomeDoObjeto.'->get'.ucfirst ($objeto->getAtributos()[0]->getNome()).'().\'';
        }
        
        $codigo .= '
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Deletar" name="deletar_' . $nomeDoObjeto . '">
                                        <hr>
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
                                            
                                            
	</div>\';
	}
                      

';
        return $codigo;
    }
    private function geraViews(Objeto $objeto)
    {
        $codigo = '';
        
        
        $atributosNN = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->isArrayNN()){
                
                $atributosNN[] = $atributo;
                
            }
        }
        
        $codigo = '<?php
            
/**
 * Classe de visao para ' . $objeto->getNome() . '
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
class ' . $objeto->getNome() . 'View {';
        $codigo .= '';
        $codigo .= $this->formInserir($objeto);
        $codigo .= $this->exibirLista($objeto);
        $codigo .= $this->mostrarSelecionado($objeto);
        $codigo .= $this->formEditar($objeto);
        
        foreach($atributosNN as $atributoNN){
            foreach($this->software->getObjetos() as $objeto3){
                if($objeto3->getNome() == explode(' ', $atributoNN->getTipo())[2]){
                    $objetoNN = $objeto3;
                    break;
                }
            }
            foreach ($objetoNN->getAtributos() as $atributo2) {
                if(substr($atributo2->getTipo(),0,6) == 'Array '){
                    //                     if(explode(' ', $atributo2->getTipo())[1]  == 'n:n'){
                    //                         $atributosNN2[] = $atributo2;
                    //                     }
                }else if($atributo2->getTipo() == Atributo::TIPO_INT || $atributo2->getTipo() == Atributo::TIPO_STRING || $atributo2->getTipo() == Atributo::TIPO_FLOAT)
                {
                    $atributosComuns2[] = $atributo2;
                }///Depois faremos um else if pra objeto.
            }
            
            $codigo .= '
                
    public function exibir'.ucfirst($atributoNN->getNome()).'('.ucfirst($objeto->getNome()).' $'.strtolower($objeto->getNome()).'){
        echo \'
        
    	<div class="card o-hidden border-0 shadow-lg my-5">
              <div class="card mb-4">
                <div class="card-header">
                  '.explode(" ", $atributoNN->getTipo())[2].' do '.$objeto->getNome().'
                </div>
                <div class="card-body">
                      
                      
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%"
				cellspacing="0">
				<thead>
					<tr>';
            $i = 0;
            foreach($atributosComuns2 as $atributo3){
                $i++;
                if($i >= 4){
                    break;
                }
                $codigo .= '
						<th>'.$atributo3->getNome().'</th>';
            }
            $codigo .= '<th>Ações</th>';
            $codigo .= '
					</tr>
				</thead>
				<tfoot>
					<tr>';
            $i = 0;
            foreach($atributosComuns2 as $atributo3){
                $i++;
                if($i >= 4){
                    break;
                }
                $codigo .= '
                        <th>'.$atributo3->getNome().'</th>';
            }
            $codigo .= '<th>Ações</th>';
            $codigo .= '
					</tr>
				</tfoot>
				<tbody>';
            $codigo .= '\';';
            
            $codigo .= '
                
            foreach($'.strtolower($objeto->getNome()).'->get'.ucfirst($atributoNN->getNome()).'() as $elemento){
                echo \'<tr>\';';
            $i = 0;
            foreach($atributosComuns2 as $atributo3){
                $i++;
                if($i >= 4){
                    break;
                }
                $codigo .= '
                echo \'<td>\'.$elemento->get'.ucfirst ($atributo3->getNome()).'().\'</td>\';';
            }
            $codigo .= 'echo \'<td>
                        <a href="?pagina='.strtolower(explode(' ', $atributoNN->getTipo())[2]).'&selecionar=\'.$elemento->get'.ucfirst ($objetoNN->getAtributos()[0]->getNome()).'().\'" class="btn btn-info">Selecionar</a>
                        <a href="?pagina='.strtolower($objeto->getNome()).'&selecionar=\'.$'.strtolower($objeto->getNome()).'->get'.ucfirst ($objeto->getAtributos()[0]->getNome()).'().\'&remover'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'=\'.$elemento->get'.ucfirst($atributosComuns2[0]->getNome()).'().\'" class="btn btn-danger">Remover</a>
                      </td>\';';
            
            $codigo .= '
                echo \'<tr>\';
            }
                
        ';
            
            $codigo .= 'echo \'';
            $codigo .= '
				</tbody>
			</table>
		</div>
                
                
                
                
      </div>
  </div>
</div>
                
                
                
        \';
                
    }
                
    public function adicionar'.ucfirst(explode(' ', $atributoNN->getTipo())[2]).'($lista){
        
        
        echo \'
        
        
        
    <div class="card o-hidden border-0 shadow-lg my-5">
	   <div class="card-body p-0">
		  <div class="row">
        
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Adicione '.explode(" ", $atributoNN->getTipo())[2].' ao '.$objeto->getNome().'</h1>
									</div>
						              <form class="user" method="post">';
            
            $codigo .= '
                                        <div class="form-group">
                						  <select type="text" class="form-control" id="add'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'" name="add'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'" >
                                                <option>Adicione '.explode(" ", $atributoNN->getTipo())[2].'</option>\';
';
            $codigo .= '
            foreach($lista as $elemento){';
            $atributosLabel = array();
            foreach($objetoNN->getAtributos() as $atributo2){
                if($atributo2->getIndice() == Atributo::INDICE_PRIMARY){
                    $atributoChave = $atributo2;
                }else if($atributo2->getTipo() == Atributo::TIPO_INT || $atributo2->getTipo() == Atributo::TIPO_STRING){
                    $atributosLabel[] = $atributo2;
                }
            }
            $codigo .= '
                echo \'
                
                                                <option value="\'.$elemento->get'.ucfirst($atributoChave->getNome()).'().\'">';
            foreach($atributosLabel as $atributo2){
                $codigo .= '\'.$elemento->get'.ucfirst($atributo2->getNome()).'().\' - ';
                
            }
            $codigo .= '</option>\';
                
            }
                
';
            $codigo .= '
            echo \'
                
                                          </select>
                						</div>';
            
            $codigo .= '
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Cadastrar" name="enviar_'.strtolower(explode(' ', $atributoNN->getTipo())[2]).'">
                                        <hr>
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
	   </div>\';
                                            
                                            
                                            
                                            
    }
                                            
                                            
';
            
        }
        
        $codigo .= '
}';

        
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/classes/view/'.ucfirst($objeto->getNome()).'View.php';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
   
    
}


?>