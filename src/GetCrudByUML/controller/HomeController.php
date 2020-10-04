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
                <h1 class="font-weight-bold m-2">getCrud ByUML</h1>
                <hr class="divider my-4" />
            </div>
            <div class="col-lg-8 align-self-baseline">
                
                <p class="text-white-75 mb-5">
                GetCrudByUml é uma ferramenta visual unificada para arquitetos de banco de dados, desenvolvedores e DBAs. Você cria o diagrama de classes UML e obtém o aplicativo com acesso à base de dados.
                </p>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe src="https://www.youtube.com/embed/-_HPhLOIE4g" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div><br><br>
                

                <a class="btn btn-primary btn-xl js-scroll-trigger" href="#como-fazer">Como Funciona</a>
            </div>
        </div>
    </div>
</header> 


<section class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light" id="como-fazer">

    <h1 class="display-4 font-weight-normal m-3 p-3">Como funciona!</h1>
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Passo 1</h5>
                <p class="card-text">Utilize os formulários para inserir as classes e atributos.</p>
                <img src="./images/classes.png" alt="Diagrama De Classes" class="img-fluid">
              </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Passo 2</h5>
                <p class="card-text">Obtenha os códigos necessários para ter um CRUD em funcionamento.</p>
                <img src="./images/sql.png" alt="codigo SQL gerado automaticamente" class="img-fluid">
              </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3">
            <div class="card">
              <div class="card-body">
                    <h5 class="card-title">Passo 3</h5>';
        echo '
        
                     <p class="card-text">Faça uma doação!</p>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick" />
                    <input type="hidden" name="hosted_button_id" value="NFR6NM3C2LW9L" />
                    <input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Faça doações com o botão do PayPal" />
                    <img alt="" border="0" src="https://www.paypal.com/pt_BR/i/scr/pixel.gif" width="1" height="1" />
                    </form><br><br>

                    <p class="card-text">Ou clique em um anúncio e ajude nosso site a continuar funcionando!</p>
                    <!-- LOMADEE - BEGIN -->
                    <script src="//ad.lomadee.com/banners/script.js?sourceId=36481616&dimension=8&height=250&width=250&method=0" type="text/javascript" language="javascript"></script>
                    <!-- LOMADEE - END -->



                </div>
            </div>
        </div>
    </div>

    ';
        $sessao = new Sessao();
        if($sessao->getNivelAcesso() != Sessao::NIVEL_DESLOGADO){
            echo 'Você está logado. ';
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