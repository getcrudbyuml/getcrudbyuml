<?php

namespace GetCrudByUML\controller;
use GetCrudByUML\dao\SoftwareDAO;
use GetCrudByUML\view\SoftwareView;
use GetCrudByUML\util\Sessao;
use GetCrudByUML\model\Usuario;
use GetCrudByUML\model\Software;
use GetCrudByUML\dao\UsuarioDAO;
use GetCrudByUML\dao\ObjetoDAO;
use GetCrudByUML\dao\AtributoDAO;
use GetCrudByUML\gerador\javaDesktopEscritor\crudMvcDao\EscritorDeSoftware as EscritorJava;
use GetCrudByUML\gerador\webPHPEscritor\crudMVCDao\crudPHP\EscritorDeSoftware as EscritorPHP;
use GetCrudByUML\gerador\webPHPEscritor\crudMVCDao\apiRestPHP\EscritorDeSoftware as EscritorAPIPHP;
use GetCrudByUML\gerador\python\EscritorDeSoftware as EscritorPython;
use GetCrudByUML\util\Zipador;

/**
 * Classe feita para manipulação do objeto Software
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class SoftwareController
{

    private $post;

    private $view;

    private $dao;

    private $selecionado;

    public static function main()
    {
        $controller = new SoftwareController();

        if (isset($_GET['deletar'])) {
            $controller->deletar();
            return;
        }
        if (isset($_GET['editar'])) {
            $controller->editar();
            return;
        }

        $controller->cadastrar();
    }

    public function __construct()
    {
        $this->dao = new SoftwareDAO();
        $this->view = new SoftwareView();
        $this->selecionado = null;
        foreach ($_POST as $chave => $valor) {
            $this->post[$chave] = $valor;
        }
    }

    public function listar()
    {
        $softwareDao = new SoftwareDAO();
        $lista = $softwareDao->retornaLista();
        $this->view->exibirLista($lista);
    }
    public function listarPorUsuario(Usuario $usuario)
    {
        $usuarioDao = new UsuarioDAO($this->dao->getConexao());
        $sessao = new Sessao();
        if($sessao->getNivelAcesso() == Sessao::NIVEL_ADM){
            if(isset($_GET['usuario_selecionado'])){
                $usuario->setId($_GET['usuario_selecionado']);
            }
        }
        $usuarioDao->buscarSoftwares($usuario);
        $this->view->exibirLista($usuario->getSoftwares());
    }
    public function excluiDir($dir){
        
        if ($dd = opendir($dir)) {
            while (false !== ($arq = readdir($dd))) {
                if($arq != "." && $arq != ".."){
                    $path = "$dir/$arq";
                    if(is_dir($path)){
                        self::excluiDir($path);
                    }elseif(is_file($path)){
                        unlink($path);
                    }
                }
            }
            closedir($dd);
        }
        rmdir($dir);
    }
    public function escrever()
    {
        foreach($this->selecionado->getObjetos() as $objeto){
            if(count($objeto->getAtributos()) == 0){
                echo '<div class="row justify-content-center">';
                echo '
           <div class="alert alert-info" role="alert">';
                if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
                    echo '
                Clique no nome de algum objeto para selecioná-lo e poder adicionar atributos.';
                }else{
                    echo '
                Click on name of an object to select it and add attributes.';
                }
                
                echo '
            </div>
            ';
                
                echo '</div>';
                return;
            }
        }
        $sessao = new Sessao();
        if (!isset($_GET['escrever'])) {
            return;
        }
        if (count($this->selecionado->getObjetos()) == 0) {
            echo '<div class="row justify-content-center">';
            echo '
            <div class="alert alert-warning" role="alert">';
            if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
                echo 'Não existem classes cadastradas, cadastre pelo menos uma classe, use o formulário acima.';
            }else{
                echo 'There are no registered classes, register at least one class, use the form above.';
            }
            
            
            echo '
            </div>
            ';
            
            echo '</div>';
            return;
        }
        
        
        foreach($this->selecionado->getObjetos() as $objeto){
            if(count($objeto->getAtributos()) == 0){
                echo '<div class="row justify-content-center">';
                echo '
            <div class="alert alert-warning" role="alert">
              <p>Existe pelo menos um objeto sem atributos. Adicione atributos.</p>
            </div>
            ';
                
                echo '</div>';
                return;
            }
        }
        if(strtolower($this->selecionado->getNomeSimples()) == 'getcrudbyuml' && $_SERVER['HTTP_HOST'] == 'localhost:888'){
            echo '
            <div class="alert alert-warning" role="alert">
              <p>Me Poupe. Tudo tem limite. Vamos parar com a palhaçada!</p>
            </div>
            ';
            return;
        }
        
        if($_SERVER['HTTP_HOST'] == 'localhost:888'){
            $diretorio = '../../'. $this->selecionado->getNomeSimples();
            
        }else{
            $diretorio = './sistemas/' . $sessao->getLoginUsuario() . '/' . $this->selecionado->getNomeSimples();
            if(is_dir('./sistemas/' . $sessao->getLoginUsuario() . '/') ){
                $this->excluiDir( './sistemas/' . $sessao->getLoginUsuario() . '/');
            }
        }
        

        
        $numeroDeArquivos = 0;
        
        if($_GET['escrever'] == "api_php"){
            EscritorAPIPHP::main($this->selecionado, $diretorio);
        }else if($_GET['escrever'] == "crud_php"){
            EscritorPHP::main($this->selecionado, $diretorio);   
        }else if($_GET['escrever'] == "crud_java"){
            EscritorJava::main($this->selecionado, $diretorio);
        }else if($_GET['escrever'] == "crud_python"){
            EscritorPython::main($this->selecionado, $diretorio);
            
        }
        else{
            return;
        }
        if($_SERVER['HTTP_HOST'] != 'localhost:888'){
            $zipador = new Zipador();
            $numeroDeArquivos = $zipador->zipaArquivo($diretorio, $diretorio.'/../'.$this->selecionado->getNomeSimples().'.zip');
        }
        

        if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
            echo '

<div class="alert alert-success" role="alert">
    O link de Download Contém um arquivo .zip com '.$numeroDeArquivos.' arquivos.
    
  
</div>
<div class="alert alert-warning" role="alert">
  <br>Considere fazer uma doação. 
    <br>PIX: jefponte@gmail.com
</div>
';
            
            
        }else{
            
            echo '
<div class="alert alert-success" role="alert">
    The Download link contains a .zip file with '.$numeroDeArquivos.' files.
</div>
';
        }
        
        
        echo '<a href="'.$diretorio.'/../'.$this->selecionado->getNome().'.zip" class="btn btn-success m-2">Download</a>';
        if($_GET['escrever'] == "crud_php"){
            echo '<a href="'.$diretorio.'/crudPHP" class="btn btn-success m-2"  target="_blank">Test</a>';
        }else if($_GET['escrever'] == "api_php"){
            echo '<a href="'.$diretorio.'/apiPHP/api" class="btn btn-success m-2"  target="_blank">Test</a>';
        }
        
        
        
                
  
    }
    public function getTextoResumo(){
        $sessao = new Sessao();
        
        $diretorio = './sistemas/' . $sessao->getLoginUsuario() . '/' . $this->selecionado->getNomeSimples();
        
        $dir= dir($diretorio.'/AppWebPHP/src/classes/model');
        $listaDeArquivos = array();
        while($arquivo = $dir-> read()){
            if($arquivo == '.' || $arquivo == '..'){
                continue;
            }
            $listaDeArquivos[] = 'model/'.$arquivo;
            break;
        }
        $dir-> close();
        $dir= dir($diretorio.'/AppWebPHP/src/classes/view');
        while($arquivo = $dir-> read()){
            if($arquivo == '.' || $arquivo == '..'){
                continue;
            }
            $listaDeArquivos[] = 'view/'.$arquivo;
            break;
        }
        $dir-> close();
        $dir= dir($diretorio.'/AppWebPHP/src/classes/controller');
        while($arquivo = $dir-> read()){
            if($arquivo == '.' || $arquivo == '..'){
                continue;
            }

            break;
        }
        $dir-> close();
        $dir= dir($diretorio.'/AppWebPHP/src/classes/dao');
        while($arquivo = $dir-> read()){
            if($arquivo == '.' || $arquivo == '..'){
                continue;
            }
            $listaDeArquivos[] = 'dao/'.$arquivo;
            break;
        }
        $dir-> close();
        $listaDeArquivos[] = '../index.php';
        
        $texto = '';
        $i = 0;
        
        $texto .= '

<div class="accordion" id="accordionApp">';
        foreach($listaDeArquivos as $arquivo){
            $i++;
            
            $texto .= '
  <div class="card">
    <div class="card-header" id="heading'.$i.'">
      <h2 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse'.$i.'" aria-expanded="false" aria-controls="collapse'.$i.'">
          '.$arquivo.'
        </button>
      </h2>
    </div>
              
    <div id="collapse'.$i.'" class="collapse" aria-labelledby="heading'.$i.'" data-parent="#accordionApp">
      <div class="card-body">
        '.$this->formataPHP(file_get_contents($diretorio.'/AppWebPHP/src/classes/'.$arquivo)).'
      </div>
    </div>
  </div>';
        }
        
        $texto .= '
  
</div>

';
        
        return $texto.'<br><br>';
    }

    public function selecionar()
    {
        
        if (! isset($_GET['selecionar'])) {
            return;
        }
        


        $this->selecionado = new Software();
        $this->selecionado->setId($_GET['selecionar']);
        $usuarioDao = new UsuarioDAO($this->dao->getConexao());
        $sessao = new Sessao();
        $usuario = new Usuario();
        $usuario->setId($sessao->getIdUsuario());
        
        if(!$usuarioDao->verificarPosse($usuario, $this->selecionado)){

            if($sessao->getNivelAcesso() != Sessao::NIVEL_ADM){
                return;
            }
            
        }
        $this->dao->pesquisaPorId($this->selecionado);
        $objetoDao = new ObjetoDAO($this->dao->getConexao());
        $objetoDao->pesquisaPorIdSoftware($this->selecionado);
        $atributoDao = new AtributoDAO($this->dao->getConexao());
        foreach ($this->selecionado->getObjetos() as $objeto) {
            $atributoDao->pesquisaPorIdObjeto($objeto);
        }
        
        echo '<div class="row">';
        echo '<div class="col-xl-9 col-lg-8 col-md-12 col-sm-12">';
        echo '<h3>Software: ' . $this->selecionado->getNome() . '</h3>';

        
        echo '<button type="button" class="btn btn-success m-2" data-toggle="modal" data-target="#modalEscrever">';
        
        if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
            echo 'Pegar Código';
        }else{
            echo 'Get Code';
        }
        echo '</button>';
        
        echo '<a href="?pagina=software&deletar=' . $this->selecionado->getId() . '" class="btn btn-danger m-2">Delete Software</a>';
        

        
        
        $this->escrever();
        
        $this->modaisSQL();

        
        echo '</div>';
        
        echo '<div class="col-xl-3 col-lg-4 col-md-12 col-sm-12">
            
        
        ';
        $objetoController = new ObjetoController();
        $objetoController->cadastrar($this->selecionado);

        echo '</div>';
        
        echo '</div>';
        
        echo '<br><br><hr>';
        

        
        echo '<div class="row justify-content-center m-3 p-3">';
        
        $this->view->mostrarSelecionado($this->selecionado);
        
        echo '</div>';
        $this->modalEscrever($this->selecionado);
    }
    
    public function modalEscrever(Software $software){
        if(!isset($_GET['selecionar'])){
            return;
        }
        echo '

<!-- Modal -->
<div class="modal fade" id="modalEscrever" tabindex="-1" aria-labelledby="modalEscreverLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEscreverLabel">Select your application</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


        <form id="form-escrever" method="get">
        <input type="hidden" name="pagina" value="software">
        <input type="hidden" name="selecionar" value="'.$software->getId().'">
        <select id="select-tipo-codigo" name="escrever" required>
            <option value="crud_php">PHP - MVC-Web</option>
            <option value="api_php">PHP - API Rest</option>
            <option value="crud_python">Python - Console Application</option>
            <option value="crud_java">Java - Console Application</option>
            <option value="">Selecione uma opção</option>
        </select>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="form-escrever" class="btn btn-primary">Get</button>
      </div>
    </div>
  </div>
</div>

';
        
    }

    public function modalSelect(){
        echo '

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalSqlManipulacao">
  SQL - Manipulação
</button>

<!-- Modal -->
<div class="modal fade" id="modalSqlManipulacao" tabindex="-1" role="dialog" aria-labelledby="labelSqlManipulacao" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelSqlManipulacao">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

';
    }
    public function modaisSQL(){
        if(!isset($_GET['escrever'])){
            return;
        }
        
        $sessao = new Sessao();
        $pathSQLPG = 'sistemas/'.$sessao->getLoginUsuario().'/'.$this->selecionado->getNomeSimples().'/'.$this->selecionado->getNomeSnakeCase().'_banco_pg.sql';
        $pathSQLITE = 'sistemas/'.$sessao->getLoginUsuario().'/'.$this->selecionado->getNomeSimples().'/'.$this->selecionado->getNomeSnakeCase().'_banco_sqlite.sql';
        if(!file_exists($pathSQLITE) || !file_exists($pathSQLPG)){
            return;
        }
        
        echo '
            <button type="button" class="btn btn-primary m-2" data-toggle="modal" data-target="#modalSqlPG">
              Create Postgres
            </button>
            ';
        echo '
            <button type="button" class="btn btn-primary m-2" data-toggle="modal" data-target="#modalSqlLite">
              Create SqlLite
            </button>
            ';
        
        
        $sqlPG = file_get_contents($pathSQLPG);
        $sqlPG = $this->formatarPG($sqlPG);
        
        $sqlSqlite= file_get_contents($pathSQLITE);
        $sqlSqlite = $this->formatarSQLITE($sqlSqlite);
        
        echo '
            
<!-- Modal -->
<div class="modal fade" id="modalSqlPG" tabindex="-1" role="dialog" aria-labelledby="labelPg" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelPg">SQL Postgres</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       '.$sqlPG.'
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <a href="'.$pathSQLPG.'" class="btn btn-primary">DOWNLOAD</a>
      </div>
    </div>
  </div>
</div>
           
';
        echo '
            
<!-- Modal -->
<div class="modal fade" id="modalSqlLite" tabindex="-1" role="dialog" aria-labelledby="labelSql" aria-hidden="true">
  <div class="modal-dialog modal-xl  modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelSql">SQL SqlLite</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       '.$sqlSqlite.'
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <a href="'.$pathSQLITE.'" class="btn btn-primary">DOWNLOAD</a>
      </div>
    </div>
  </div>
</div>
           
';
    }
    public function formatarPG($sql)
    {
        $sql = str_replace(" ", "&nbsp;", $sql);
        $sql = nl2br($sql);
        $sql = str_replace("ON&nbsp;UPDATE&nbsp;NO&nbsp;ACTION", "<span class=\"text-secondary font-weight-bold\">ON UPDATE NO ACTION</span>", $sql);
        $sql = str_replace("ON&nbsp;DELETE&nbsp;NO&nbsp;ACTION", "<span class=\"text-secondary font-weight-bold\">ON DELETE NO ACTION</span>", $sql);
        $sql = str_replace("MATCH&nbsp;SIMPLE", "<span class=\"text-secondary font-weight-bold\">MATCH&nbsp;SIMPLE</span>", $sql);

        $keyWords = array(
            "CREATE",
            "TABLE",
            "CONSTRAINT",
            "ALTER",
            "PRIMARY",
            "KEY",
            "NOT",
            "NULL",
            "FOREIGN",
            "ADD",
            "REFERENCES",
            "CONSTRAINT",
            "COLUMN"
        );
        $keyWords2 = array(
            "<span class=\"text-danger font-weight-bold\">CREATE</span>",
            "<span class=\"text-danger font-weight-bold\">TABLE</span>",
            "<span class=\"text-danger font-weight-bold\">CONSTRAINT</span>",
            "<span class=\"text-danger font-weight-bold\">ALTER</span>",
            "<span class=\"text-danger font-weight-bold\">PRIMARY</span>",
            "<span class=\"text-danger font-weight-bold\">KEY</span>",
            "<span class=\"text-danger font-weight-bold\">NOT</span>",
            "<span class=\"text-danger font-weight-bold\">NULL</span>",
            "<span class=\"text-danger font-weight-bold\">FOREIGN</span>",
            "<span class=\"text-secondary font-weight-bold\">ADD</span>",
            "<span class=\"text-secondary font-weight-bold\">REFERENCES</span>",
            "<span class=\"text-secondary font-weight-bold\">CONSTRAINT</span>",
            "<span class=\"text-secondary font-weight-bold\">COLUMN</span>"
        );

        $sql = str_replace($keyWords, $keyWords2, $sql);
        $keyWords = array();
        $keyWords2 = array();
        foreach ($this->selecionado->getObjetos() as $objeto) {
            $keyWords[] = '&nbsp;' . $objeto->getNomeSnakeCase() . '&nbsp;';
            $keyWords2[] = "&nbsp;<span class=\"text-info\">" . $objeto->getNomeSnakeCase() . "</span>&nbsp;";
        }

        $sql = str_replace($keyWords, $keyWords2, $sql);

        $keyWords = array();
        $keyWords2 = array();
        $tipos = array(
            "numeric",
            "&nbsp;integer",
            "&nbsp;serial&nbsp;",
            "character&nbsp;varying"
        );
        foreach ($tipos as $tipo) {
            $keyWords[] = $tipo;
            $keyWords2[] = "<span class=\"text-success\">" . $tipo . "</span>&nbsp;";
        }
        $sql = str_replace($keyWords, $keyWords2, $sql);

        return $sql;
    }

    public function formataPHP($cod){
        $cod = htmlentities($cod);
        $cod = str_replace(" ", "&nbsp;", $cod);
        $cod = nl2br($cod);
        
        return $cod;
    }
    public function formatarSQLITE($sql)
    {
        $sql = str_replace(" ", "&nbsp;", $sql);
        $sql = nl2br($sql);
        $sql = str_replace("ON&nbsp;UPDATE&nbsp;NO&nbsp;ACTION", "<span class=\"text-secondary font-weight-bold\">ON UPDATE NO ACTION</span>", $sql);
        $sql = str_replace("ON&nbsp;DELETE&nbsp;NO&nbsp;ACTION", "<span class=\"text-secondary font-weight-bold\">ON DELETE NO ACTION</span>", $sql);
        $sql = str_replace("MATCH&nbsp;SIMPLE", "<span class=\"text-secondary font-weight-bold\">MATCH&nbsp;SIMPLE</span>", $sql);

        $keyWords = array(
            "CREATE",
            "TABLE",
            "CONSTRAINT",
            "ALTER",
            "PRIMARY",
            "KEY",
            "NOT",
            "NULL",
            "FOREIGN",
            "ADD&nbsp;COLUMN",
            "REFERENCES",
            "CONSTRAINT"
        );
        $keyWords2 = array(
            "<span class=\"text-danger font-weight-bold\">CREATE</span>",
            "<span class=\"text-danger font-weight-bold\">TABLE</span>",
            "<span class=\"text-danger font-weight-bold\">CONSTRAINT</span>",
            "<span class=\"text-danger font-weight-bold\">ALTER</span>",
            "<span class=\"text-danger font-weight-bold\">PRIMARY</span>",
            "<span class=\"text-danger font-weight-bold\">KEY</span>",
            "<span class=\"text-danger font-weight-bold\">NOT</span>",
            "<span class=\"text-danger font-weight-bold\">NULL</span>",
            "<span class=\"text-danger font-weight-bold\">FOREIGN</span>",
            "<span class=\"text-secondary font-weight-bold\">ADD COLUMN</span>",
            "<span class=\"text-secondary font-weight-bold\">REFERENCES</span>",
            "<span class=\"text-secondary font-weight-bold\">CONSTRAINT</span>"
        );

        $sql = str_replace($keyWords, $keyWords2, $sql);
        $keyWords = array();
        $keyWords2 = array();
        foreach ($this->selecionado->getObjetos() as $objeto) {
            $keyWords[] = '&nbsp;' . $objeto->getNomeSnakeCase() . '&nbsp;';
            $keyWords2[] = "&nbsp;<span class=\"text-info\">" . $objeto->getNomeSnakeCase() . "</span>&nbsp;";
        }

        $sql = str_replace($keyWords, $keyWords2, $sql);

        $keyWords = array();
        $keyWords2 = array();
        $tipos = array(
            "TEXT",
            "NUMERIC",
            "AUTOINCREMENT",
            "&nbsp;INTEGER&nbsp;",
            "&nbsp;serial&nbsp;",
            "character&nbsp;varying"
        );
        foreach ($tipos as $tipo) {
            $keyWords[] = $tipo;
            $keyWords2[] = "<span class=\"text-success\">" . $tipo . "</span>&nbsp;";
        }
        $sql = str_replace($keyWords, $keyWords2, $sql);

        return $sql;
    }

    public function cadastrar()
    {
        $this->view->mostraFormInserir();

        if (! isset($this->post['enviar_software'])) {
            return;
        }
        if (! (isset($this->post['nome']))) {
            echo "Incompleto";
            return;
        }

        $software = new Software();
        $software->setNome($this->post['nome']);
        $strUrl = "";

        
        if(!$this->dao->inserir($software)){
            echo "Erro ao tentar inserir.";
            return;
        }
        
        $id = $this->dao->getConexao()->lastInsertId();
        $software->setId($id);
        $usuario = new Usuario();
        $sessao = new Sessao();
        $usuario->setId($sessao->getIdUsuario());
        $usuarioDao = new UsuarioDAO();
        
        
        if(!$usuarioDao->inserirSoftware($usuario, $software)){
            echo "Erro ao tentar inserir.";
            return;
        }
        $strUrl = '&selecionar=' . $software->getId();
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=software' . $strUrl . '">';
    }

    public function editar()
    {
        if (! isset($_GET['editar'])) {
            return;
        }
        
        $this->selecionado = new Software();
        $this->selecionado->setId($_GET['editar']);
        
        $usuarioDao = new UsuarioDAO($this->dao->getConexao());
        $sessao = new Sessao();
        $usuario = new Usuario();
        $usuario->setId($sessao->getIdUsuario());
        if(!$usuarioDao->verificarPosse($usuario, $this->selecionado)){
            
            return;
        }
        
        
        $this->dao->pesquisaPorId($this->selecionado);

        if (! isset($_POST['editar_software'])) {
            $this->view->mostraFormEditar($this->selecionado);
            return;
        }

        if (! (isset($this->post['nome']))) {
            echo "Incompleto";
            return;
        }

        $this->selecionado->setNome($this->post['nome']);

        if ($this->dao->atualizar($this->selecionado)) {

            echo "Sucesso";
        } else {
            echo "Fracasso";
        }
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; URL=index.php?pagina=software">';
    }

    public function deletar()
    {
        if (! isset($_GET['deletar'])) {
            return;
        }
        $this->selecionado = new Software();
        $this->selecionado->setId($_GET['deletar']);
        
        $usuarioDao = new UsuarioDAO($this->dao->getConexao());
        $sessao = new Sessao();
        $usuario = new Usuario();
        $usuario->setId($sessao->getIdUsuario());
        if(!$usuarioDao->verificarPosse($usuario, $this->selecionado)){
            
            return;
        }
        
        
        $this->dao->pesquisaPorId($this->selecionado);
        if (! isset($_POST['deletar_software'])) {
            $this->view->confirmarDeletar($this->selecionado);
            return;
        }
        if ($this->dao->excluir($this->selecionado)) {
            echo "Success";
        } else {
            echo "Error";
        }
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=software">';
    }

    public function listarJSON()
    {
        $softwareDao = new SoftwareDAO();
        $lista = $softwareDao->retornaLista();
        $listagem = array();
        foreach ($lista as $linha) {
            $listagem['lista'][] = array(
                'id' => $linha->getId(),
                'nome' => $linha->getNome()
            );
        }
        echo json_encode($listagem);
    }
}
?>