<?php




class ViewCustomGerador{
    private $software;
    private $listaDeArquivos;
    private $diretorio;
    public static function main(Software $software, $diretorio){
        $gerador = new ViewCustomGerador($software, $diretorio);
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
        
        $caminho = $this->diretorio.'/AppWebPHP/src/classes/custom/view/';
        
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
    
    private function geraViews(Objeto $objeto)
    {
        $codigo = '';

        $codigo = '<?php
            
/**
 * Classe de visao para ' . $objeto->getNome() . '
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
class ' . $objeto->getNome() . 'CustomView extends ' . $objeto->getNome() . 'View {

    ////////Digite seu código customizado aqui.

';
        
        $codigo .= '
}';

        
        $caminho = $this->diretorio.'/AppWebPHP/src/classes/custom/view/'.ucfirst($objeto->getNome()).'CustomView.php';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
   
    
}


?>