<?php




class ViewGerador{
    private $software;
    private $listaDeArquivos;
    
    
    public function getListaDeArquivos(){
        return $this->listaDeArquivos;
    }
    public function __construct(Software $software){
        $this->software = $software;
    }
    /**
     * Selecione uma linguagem
     * @param int $linguagem
     */
    public function gerarCodigo(){
        $this->geraCodigoPHP();
        $this->geraCodigoJava();
        
    }
    private function geraCodigoJava(){
        
        $path = 'sistemas/'.$this->software->getNome().'/java/'.$this->software->getNome().'/src/main/java';
        foreach($this->software->getObjetos() as $objeto){
            $codigo = $this->geraViewsJava($objeto, $this->software);
            $caminho = $path.'/br/com/escritordesoftware/'.strtolower($this->software->getNome()).'/view/' . ucfirst($objeto->getNome()) . 'View.java';
            $this->listaDeArquivos[$caminho] = $codigo;
        }}
    private function geraCodigoPHP(){
        $path = 'sistemas/'.$this->software->getNome().'/php/src';
        foreach($this->software->getObjetos() as $objeto){
            $codigo = $this->geraViewsPHP($objeto, $this->software);
            $caminho = $path.'/classes/view/' . strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100) . 'View.php';
            $this->listaDeArquivos[$caminho] = $codigo;
        }
    }
    private function geraViewsPHP(Objeto $objeto, Software $software)
    {
        $codigo = '';
        $nomeDoObjeto = strtolower($objeto->getNome());
        $nomeDoObjetoMa = ucfirst($objeto->getNome());
        
        
        $atributosComuns = array();
        $atributosNN = array();
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if(substr($atributo->getTipo(),0,6) == 'Array '){
                if(explode(' ', $atributo->getTipo())[1]  == 'n:n'){
                    $atributosNN[] = $atributo;
                }
            }else if($atributo->getTipo() == Atributo::TIPO_INT || $atributo->getTipo() == Atributo::TIPO_STRING || $atributo->getTipo() == Atributo::TIPO_FLOAT)
            {
                $atributosComuns[] = $atributo;
            }else{
                $atributosObjetos[] = $atributo;
            }
        }
        
        $codigo = '<?php
            
/**
 * Classe de visao para ' . $nomeDoObjetoMa . '
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
class ' . $nomeDoObjetoMa . 'View {
	public function mostraFormInserir(';
        $i = count($atributosObjetos);
        foreach($atributosObjetos as $atributoObjeto){
            $i--;
            $codigo .= '$lista'.ucfirst($atributoObjeto->getTipo());
            if($i != 0){
                $codigo .= ', ';
            }
            
        }
        $codigo .= ') {
		echo \'
            
            
            
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
            
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Adicionar ' . $nomeDoObjetoMa . '</h1>
									</div>
						              <form class="user" method="post">';
        
        
        
        foreach ($atributosComuns as $atributo) {
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            
            $codigo .= '
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" id="' . $atributo->getNome() . '" name="' . $atributo->getNome(). '" placeholder="' . $atributo->getNome(). '">
                						</div>';
        }
        foreach($atributosObjetos as $atributo){
            $codigo .= '
                                        <div class="form-group">
                						  <select class="form-control form-control-user" id="' . $atributo->getNome() . '" name="' . $atributo->getNome(). '">
                                            <option>Selecione o '.$atributo->getNome().'</option>\';
                                                
        foreach( $lista'.ucfirst($atributo->getTipo()).' as $elemento){
            echo \'<option>\'.$elemento.\'</option>\';
        }
            
        echo \'
                                          </select>
                						</div>';
            
        }
        
        $codigo .= '
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Cadastrar" name="enviar_' . $nomeDoObjeto . '">
                                        <hr>
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
			</div>
\';
	}
                                            
                                            
    public function exibirLista($lista){
           echo \'
                                            
                                            
                                            
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
					<tr>';
        $i = 0;
        foreach($atributosComuns as $atributo){
            $i++;
            if($i >= 5){
                break;
            }
            $codigo .= '
						<th>'.$atributo->getNome().'</th>';
            
        }
        $i = 0;
        foreach($atributosObjetos as $atributo){
            $i++;
            if($i >= 5){
                break;
            }
            $codigo .= '
						<th>'.$atributo->getNome().'</th>';
            
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
                        <th>'.$atributo->getNome().'</th>';
        }
        $i = 0;
        foreach($atributosObjetos as $atributo){
            $i++;
            if($i >= 5){
                break;
            }
            $codigo .= '
						<th>'.$atributo->getNome().'</th>';
            
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
</div>
            
\';
    }
            
            
        public function mostrarSelecionado('.$nomeDoObjetoMa.' $'.$nomeDoObjeto.'){
        echo \'
            
	<div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card mb-4">
            <div class="card-header">
                  '.$nomeDoObjetoMa.' selecionado
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
            
	public function mostraFormEditar('.$nomeDoObjetoMa.' $'.$nomeDoObjeto.') {
		echo \'
	    
	    
	    
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
	    
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Adicionar ' . $nomeDoObjetoMa . '</h1>
									</div>
						              <form class="user" method="post">';
        
        
        foreach ($atributosComuns as $atributo) {
            $variavel = $atributo->getNome();
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            $codigo .= '
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" value="\'.$'.$nomeDoObjeto.'->get'.ucfirst ($atributo->getNome()).'().\'" id="' . $variavel . '" name="' . $variavel . '" placeholder="' . $variavel . '">
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
                                            
    public function confirmarDeletar('.$nomeDoObjetoMa.' $'.$nomeDoObjeto.') {
		echo \'
        
        
        
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
        
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Deletar ' . $nomeDoObjetoMa . '</h1>
									</div>
						              <form class="user" method="post">';
        
        foreach ($atributosComuns as $atributo) {
            $variavel = $atributo->getNome();
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            
        }
        
        $codigo .= '                    Tem Certeza que deseja deletar o \'.$'.$nomeDoObjeto.'->get'.ucfirst ($objeto->getAtributos()[1]->getNome()).'().\'
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Deletar" name="deletar_' . $nomeDoObjeto . '">
                                        <hr>
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
                                            
                                            
	</div>\';
	}
                                            
    public function mensagem($mensagem) {
		echo \'
                                            
                                            
                                            
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
                                            
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4">\'.$mensagem.\'</h1>
									</div>
                                            
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
	</div>\';
	}
                                            
                                            
                                            
';
        
        foreach($atributosNN as $atributoNN){
            foreach($software->getObjetos() as $objeto3){
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
                						  <select type="text" class="form-control form-control-user" id="add'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'" name="add'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'" >
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
        return $codigo;
    }
    private function geraViewsJava(Objeto $objeto, Software $software)
    {
        $codigo = '';
        $codigo = '
package br.com.escritordesoftware.escola.view;
import javax.swing.JFrame;
/**
 * Classe de visao para ' . ucfirst($objeto->getNome()) . '
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
@SuppressWarnings("serial")
public class ' . ucfirst($objeto->getNome()) . 'View extends JFrame {}';
        
        return $codigo;
    }
    
}


?>