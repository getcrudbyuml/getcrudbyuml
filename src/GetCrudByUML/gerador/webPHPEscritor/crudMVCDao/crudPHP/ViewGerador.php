<?php



namespace GetCrudByUML\gerador\webPHPEscritor\crudMVCDao\crudPHP;

use GetCrudByUML\model\Atributo;
use GetCrudByUML\model\Objeto;
use GetCrudByUML\model\Software;

class ViewGerador{
    private $software;
    private $listaDeArquivos;
    
    public static function main(Software $software){
        $gerador = new ViewGerador($software);
        return $gerador->gerarCodigo();
        
    }
    
    public function __construct(Software $software){
        $this->software = $software;
        
    }
    /**
     * Selecione uma linguagem
     * @param int $linguagem
     */
    public function gerarCodigo(){
        foreach($this->software->getObjetos() as $objeto){
            $this->geraViews($objeto);
        }
        
        return $this->listaDeArquivos;
        
    }

    private function showInsertForm(Objeto $objeto) : string {
        $codigo = '';
        
        
        $atributosComuns = array();
        $existeCampoFile = false;
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->tipoListado()){
                $atributosComuns[] = $atributo;
            }else if($atributo->isObjeto())
            {
                $atributosObjetos[] = $atributo;
            }
            if($atributo->getTipo() == Atributo::TIPO_IMAGE){
                $existeCampoFile = true;
            }
        }
        $codigo = '
    public function showInsertForm(';
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
<button type="button" class="btn btn-primary m-3" data-toggle="modal" data-target="#modalAdd'.$objeto->getNome().'">
  Adicionar
</button>

<!-- Modal -->
<div class="modal fade" id="modalAdd'.$objeto->getNome().'" tabindex="-1" role="dialog" aria-labelledby="labelAdd'.$objeto->getNome().'" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelAdd'.$objeto->getNome().'">Adicionar '.$objeto->getNomeTextual().'</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        


          <form id="insert_form_'.$objeto->getNomeSnakeCase().'" class="user" method="post"';
        if($existeCampoFile){
            $codigo .= ' enctype="multipart/form-data" ';
        }
          $codigo .= '>
            <input type="hidden" name="enviar_' . $objeto->getNomeSnakeCase() . '" value="1">                

';
        foreach ($atributosComuns as $atributo) {
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            
            $codigo .= '

                                        <div class="form-group">
                                            <label for="' . $atributo->getNomeSnakeCase(). '">' . $atributo->getNomeTextual(). '</label>
                                            '.$atributo->getFormHtml().'
                                        </div>';
        }
        
        foreach($atributosObjetos as $atributo){
            
            $strCampoPrimary = '';
            foreach($this->software->getObjetos() as $objetoDoAtributo){
                if($objetoDoAtributo->getNome() == $atributo->getTipo()){
                    foreach($objetoDoAtributo->getAtributos() as $att){
                        if($att->isPrimary()){
                            $strCampoPrimary = ucfirst($att->getNome());
                            break;
                        }
                    }
                    break;
                }
            }
            
            $codigo .= '
                                        <div class="form-group">
                                          <label for="' . $atributo->getNomeSnakeCase(). '">' . $atributo->getNomeTextual(). '</label>
                						  <select class="form-control" id="' . $atributo->getNomeSnakeCase() . '" name="' . $atributo->getNomeSnakeCase(). '">
                                            <option value="">Selecione o '.$atributo->getNomeTextual().'</option>\';
                                                
        foreach( $lista'.ucfirst($atributo->getNome()).' as $element){
            echo \'<option value="\'.$element->get'.$strCampoPrimary.'().\'">\'.$element.\'</option>\';
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
        <button form="insert_form_'.$objeto->getNomeSnakeCase().'" type="submit" class="btn btn-primary">Cadastrar</button>
      </div>
    </div>
  </div>
</div>
            
            
			
\';
	}



';
        return $codigo;
    }
    private function showList(Objeto $objeto){
        $codigo = '';
        
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
                                            
    public function showList($lista){
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
                        <th>Actions</th>';
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
                        <th>Actions</th>';
        $codigo .= '
					</tr>
				</tfoot>
				<tbody>';
        $codigo .= '\';';
        
        $codigo .= '
            
            foreach($lista as $element){
                echo \'<tr>\';';
        $i = 0;
        foreach($atributosComuns as $atributo){
            $i++;
            if($i >= 5){
                break;
            }
            $codigo .= '
                echo \'<td>\'.$element->get'.ucfirst ($atributo->getNome()).'().\'</td>\';';
        }
        $i = 0;
        foreach($atributosObjetos as $atributo){
            $i++;
            if($i >= 5){
                break;
            }
            $codigo .= '
                echo \'<td>\'.$element->get'.ucfirst ($atributo->getNome()).'().\'</td>\';';
        }
        $codigo .= '
                echo \'<td>
                        <a href="?page='.$objeto->getNomeSnakeCase().'&select=\'.$element->get'.ucfirst ($objeto->getAtributos()[0]->getNome()).'().\'" class="btn btn-info text-white">Select</a>
                        <a href="?page='.$objeto->getNomeSnakeCase().'&edit=\'.$element->get'.ucfirst ($objeto->getAtributos()[0]->getNome()).'().\'" class="btn btn-success text-white">Edit</a>
                        <a href="?page='.$objeto->getNomeSnakeCase().'&delete=\'.$element->get'.ucfirst ($objeto->getAtributos()[0]->getNome()).'().\'" class="btn btn-danger text-white">Delete</a>
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


            
        public function showSelected('.$objeto->getNome().' $'.$nomeDoObjeto.'){
            echo \'
            
	<div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card mb-4">
            <div class="card-header">
                  '.$objeto->getNomeTextual().' selecionado
            </div>
            <div class="card-body">';
        
        foreach($atributosComuns as $atributo){
            $codigo .= '
                '.ucfirst($atributo->getNomeTextual()).': \'.$'.$nomeDoObjeto.'->get'.ucfirst ($atributo->getNome()).'().\'<br>';
        }
        
        foreach($atributosObjetos as $atributo){
            $codigo .= '
                '.ucfirst($atributo->getNomeTextual()).': \'.$'.$nomeDoObjeto.'->get'.ucfirst ($atributo->getNome()).'().\'<br>';
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
    private function showEditForm(Objeto $objeto) : string {
        $codigo = '';
        
        
        $atributosComuns = array();
        $atributosObjetos = array();
        $listaParametros = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->tipoListado()){
                $atributosComuns[] = $atributo;
            }else if($atributo->isObjeto())
            {
                $atributosObjetos[] = $atributo;
                $listaParametros[] = '$lista'.ucfirst($atributo->getNome());
            }
        }
        $listaParametros[] = $objeto->getNome().' $selecionado';
        
        $codigo .= '

            
	public function showEditForm(';
       $codigo .= implode(', ', $listaParametros);
	   $codigo .= ') {
		echo \'
	    
	    
	    
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<div class="row">
	    
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Edit ' . $objeto->getNomeTextual() . '</h1>
									</div>
						              <form class="user" method="post">';
        
        
        foreach ($atributosComuns as $atributo) {
            
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            $codigo .= '
                                        <div class="form-group">
                                            <label for="'.$atributo->getNomeSnakeCase().'">'.$atributo->getNomeTextual().'</label>
                                            '.$atributo->getFormHTMLEditar().'
                						</div>';
        }
        foreach($atributosObjetos as $atributo){
            $codigo .= '
                                        <div class="form-group">
                                          <label for="' . $atributo->getNomeSnakeCase(). '">' . $atributo->getNomeTextual(). '</label>
                						  <select class="form-control" id="' . $atributo->getNomeSnakeCase() . '" name="' . $atributo->getNomeSnakeCase(). '">
                                            <option value="">Selecione o '.$atributo->getNomeTextual().'</option>\';
                                                
        foreach( $lista'.ucfirst($atributo->getNome()).' as $element){
            echo \'<option value="\'.$element->getId().\'">\'.$element.\'</option>\';
        }
            
        echo \'
                                          </select>
                						</div>';
            
        }
        
        $codigo .= '
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Alterar" name="edit_' . $objeto->getNomeSnakeCase() . '">
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
    private function confirmDelete(Objeto $objeto) : string {
        $codigo = '';
        
        
        $atributosComuns = array();

        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->tipoListado()){
                $atributosComuns[] = $atributo;
            }
        }
        
        $codigo  = '

                                            
    public function confirmDelete('.$objeto->getNome().' $'.lcfirst($objeto->getNome()).') {
		echo \'
        
        
        
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
        
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Delete ' . $objeto->getNomeTextual() . '</h1>
									</div>
						              <form class="user" method="post">';
        
        foreach ($atributosComuns as $atributo) {
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            
        }
        
        
        
        $codigo .= '                    Are you sure you want to delete this object?

                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Delete" name="delete_' . $objeto->getNomeSnakeCase() . '">
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

namespace '.$this->software->getNome().'\\\\view;
use '.$this->software->getNome().'\\\\model\\\\'.ucfirst($objeto->getNome()).';



class ' . $objeto->getNome() . 'View {';
        $codigo .= '';
        $codigo .= $this->showInsertForm($objeto);
        $codigo .= $this->showList($objeto);
        $codigo .= $this->showEditForm($objeto);
        $codigo .= $this->mostrarSelecionado($objeto);
        $codigo .= $this->confirmDelete($objeto);
        
        foreach($atributosNN as $atributoNN){
            foreach($this->software->getObjetos() as $objeto3){
                if($objeto3->getNome() == explode(' ', $atributoNN->getTipo())[2]){
                    $objetoNN = $objeto3;
                    break;
                }
            }
            foreach ($objetoNN->getAtributos() as $atributo2) {
                if(substr($atributo2->getTipo(),0,6) == 'Array '){
                    
                }else if($atributo2->getTipo() == Atributo::TIPO_INT || $atributo2->getTipo() == Atributo::TIPO_STRING || $atributo2->getTipo() == Atributo::TIPO_FLOAT)
                {
                    $atributosComuns2[] = $atributo2;
                }
            }
            
            $codigo .= '
                
    public function show'.ucfirst($atributoNN->getNome()).'('.ucfirst($objeto->getNome()).' $'.strtolower($objeto->getNome()).'){
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
            $codigo .= '<th>Actions</th>';
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
            $codigo .= '<th>Actions</th>';
            $codigo .= '
					</tr>
				</tfoot>
				<tbody>';
            $codigo .= '\';';
            
            $codigo .= '
                
            foreach($'.strtolower($objeto->getNome()).'->get'.ucfirst($atributoNN->getNome()).'() as $element){
                echo \'<tr>\';';
            $i = 0;
            foreach($atributosComuns2 as $atributo3){
                $i++;
                if($i >= 4){
                    break;
                }
                $codigo .= '
                echo \'<td>\'.$element->get'.ucfirst ($atributo3->getNome()).'().\'</td>\';';
            }
            $codigo .= 'echo \'<td>
                        <a href="?pagina='.strtolower(explode(' ', $atributoNN->getTipo())[2]).'&selecionar=\'.$element->get'.ucfirst ($objetoNN->getAtributos()[0]->getNome()).'().\'" class="btn btn-info">Selecionar</a>
                        <a href="?pagina='.strtolower($objeto->getNome()).'&selecionar=\'.$'.strtolower($objeto->getNome()).'->get'.ucfirst ($objeto->getAtributos()[0]->getNome()).'().\'&remover'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'=\'.$element->get'.ucfirst($atributosComuns2[0]->getNome()).'().\'" class="btn btn-danger">Remover</a>
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
                
    public function add'.ucfirst(explode(' ', $atributoNN->getTipo())[2]).'($lista){
        
        
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
            foreach($lista as $element){';
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
                
                                                <option value="\'.$element->get'.ucfirst($atributoChave->getNome()).'().\'">';
            foreach($atributosLabel as $atributo2){
                $codigo .= '\'.$element->get'.ucfirst($atributo2->getNome()).'().\' - ';
                
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

        
        $caminho = ucfirst($objeto->getNome()).'View.php';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
   
    
}


?>