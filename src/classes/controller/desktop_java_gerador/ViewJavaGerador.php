<?php 


class ViewJavaGerador{
    private function geraCodigoJava(){
        
        $path = 'sistemas/'.$this->software->getNome().'/java/'.$this->software->getNome().'/src/main/java';
        foreach($this->software->getObjetos() as $objeto){
            $codigo = $this->geraViewsJava($objeto, $this->software);
            $caminho = $path.'/br/com/escritordesoftware/'.strtolower($this->software->getNome()).'/view/' . ucfirst($objeto->getNome()) . 'View.java';
            $this->listaDeArquivos[$caminho] = $codigo;
        }
    }
    private function geraViewsJava(Objeto $objeto, Software $software)
    {
        $codigo = '';
        $codigo = '
package br.com.escritordesoftware.'.strtolower($this->software->getNome()).'.view;
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