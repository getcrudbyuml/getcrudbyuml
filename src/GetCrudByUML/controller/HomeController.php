<?php



namespace GetCrudByUML\controller;
use GetCrudByUML\util\Sessao;
use GetCrudByUML\custom\view\UsuarioCustomView;

class HomeController{
    
    public static function main(){
        
        echo '

<header class="masthead">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-end">
                <h1 class="font-weight-bold m-2">getCrudByUML</h1>
                <hr class="divider my-4" />
            </div>
            <div class="col-lg-8 align-self-baseline">
                
                <p class="text-white-75 mb-5">';
        if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
            echo '
                    GetCrudByUml é uma ferramenta que gera códigos a partir de um diagrama de classes.';
        }else{
            echo '
                    GetCrudByUml is a tool that generates code from class diagrams.';
        }
        
        echo '
                </p>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe src="https://www.youtube.com/embed/-_HPhLOIE4g" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div><br><br>
                

                <a class="btn btn-primary btn-xl js-scroll-trigger" href="#como-fazer">';
        if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
            echo 'Como Funciona';
        }else{
            echo 'How it Works';
        }
                
                echo '</a>
            </div>
        </div>
    </div>
</header> 


<section class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light" id="como-fazer">

    <h1 class="display-4 font-weight-normal m-3 p-3">';
                if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
                    echo 'Como Funciona';
                }else{
                    echo 'How it Works';
                }
                
    
    echo '</h1>
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Step 1</h5>
                <p class="card-text">';
    if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
        echo 'Utilize os formulários para inserir as classes e atributos.';
    }else{
        echo 'Use the forms to add the classes and attributes.';
    }
                
                
                echo '</p>
                <img src="';
    if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
        echo './images/classes.png';
    }else{
        echo './images/class.png';
    }
                
                echo '" alt="Diagrama De Classes" class="img-fluid">
              </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Step 2</h5>
                <p class="card-text">';
                
                
                if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
                    echo 'Obtenha os códigos necessários para ter uma aplicação CRUD.';
                }else{
                    echo 'Get the codes needed to have a CRUD application.';
                }
                
                echo '</p>

                <img src="';
                if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
                    echo './images/sql2.png';
                }else{
                    echo './images/sql.png';
                }
                
                echo '" alt="codigo SQL gerado automaticamente" class="img-fluid">
              </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3">
            <div class="card">
              <div class="card-body">
                    <h5 class="card-title">Step 3</h5>';
        echo '
        
                     <p class="card-text">';
        if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
            echo 'Por favor considere fazer uma doação!';
        }else{
            echo 'Please consider making a donation!';
        }
        //
                     
                     echo '</p>
                    
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="4M3VMZUYC8YTQ" />
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_BR/i/scr/pixel.gif" width="1" height="1" />
</form>
<br>
<br>
<img src="images/qr_code.png" height="200" alt="QR Code">
<br>
<br>

                    
                    


                </div>
            </div>
        </div>
    </div>

    ';
        $sessao = new Sessao();
        if($sessao->getNivelAcesso() != Sessao::NIVEL_DESLOGADO){
            echo 'You are logged in. ';
        }else{
            $usuarioView = new UsuarioCustomView();
            $usuarioView->mostraFormInserir();

        }
        
        
    echo '

</section>
            
<!-- Footer-->
<footer class="py-5">
    <div class="container"><div class="small text-center text-muted">Copyright © 2020 - contato@getcrudbyuml.com</div></div>
</footer> 
';
        
    }
    
    
    
}