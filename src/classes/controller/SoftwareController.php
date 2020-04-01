<?php

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

    public function escrever()
    {
        $sessao = new Sessao();
        if (!isset($_GET['escrever'])) {
            return;
        }
        if (count($this->selecionado->getObjetos()) == 0) {
            echo "<p>Não existem classes cadastradas, cadastre pelo menos uma classe, use o formulário acima.</p>";
            return;
        }
        
        $diretorio = './sistemas/' . $sessao->getLoginUsuario() . '/' . $this->selecionado->getNomeSimples();
        if($_GET['escrever'] = 2){
            $diretorio = '../../webcriado';
        }
        EscritorDeSoftware::main($this->selecionado, $diretorio);


    }

    public function selecionar()
    {
        

        if (! isset($_GET['selecionar'])) {
            echo '<p>Bem vindo ao Escritor de Software</p>';
            echo '<p>Utilize o formulário na barra lateral a esquerda para 
                    inserir um novo software.</p>';
            return;
        }


        $this->selecionado = new Software();
        $this->selecionado->setId($_GET['selecionar']);
        $this->dao->pesquisaPorId($this->selecionado);
        $objetoDao = new ObjetoDAO($this->dao->getConexao());
        $objetoDao->pesquisaPorIdSoftware($this->selecionado);
        $atributoDao = new AtributoDAO($this->dao->getConexao());
        foreach ($this->selecionado->getObjetos() as $objeto) {
            $atributoDao->pesquisaPorIdObjeto($objeto);
        }
        
        echo '<div class="row">';
        echo '<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">';
        echo '<h3>Software: ' . $this->selecionado->getNome() . '</h3>';
        echo '<a href="?pagina=software&selecionar=' . $this->selecionado->getId() . '&escrever=1" class="btn btn-success m-2">Pegar Código</a>';
        echo '<a href="?pagina=software&deletar=' . $this->selecionado->getId() . '" class="btn btn-danger m-2">Deletar Software</a>';
        echo '</div>';
        
        echo '<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">';
        $objetoController = new ObjetoController();
        $objetoController->cadastrar($this->selecionado);
        echo '</div>';
        echo '</div>';
        
        echo '<br><br><hr>';
        
        
        $this->escrever();
        

        
        echo '<div class="row justify-content-center m-3 p-3">';
        
        $this->view->mostrarSelecionado($this->selecionado);
        
        echo '</div>';
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
        if ($this->dao->inserir($software)) {
            $strUrl = '&selecionar=7' . $this->dao->getConexao()->lastInsertId();
            echo "Sucesso";
        } else {
            echo "Fracasso";
        }
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=software' . $strUrl . '">';
    }

    public function editar()
    {
        if (! isset($_GET['editar'])) {
            return;
        }
        $selecionado = new Software();
        $selecionado->setId($_GET['editar']);
        $this->dao->pesquisaPorId($selecionado);

        if (! isset($_POST['editar_software'])) {
            $this->view->mostraFormEditar($selecionado);
            return;
        }

        if (! (isset($this->post['nome']))) {
            echo "Incompleto";
            return;
        }

        $selecionado->setNome($this->post['nome']);

        if ($this->dao->atualizar($selecionado)) {

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
        $selecionado = new Software();
        $selecionado->setId($_GET['deletar']);
        $this->dao->pesquisaPorId($selecionado);
        if (! isset($_POST['deletar_software'])) {
            $this->view->confirmarDeletar($selecionado);
            return;
        }
        if ($this->dao->excluir($selecionado)) {
            echo "excluido com sucesso";
        } else {
            echo "Errou";
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