<?php



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
                GetCrudByUml is a unified visual tool for database architects, developers, and DBAs. You make the UML Class Diagram and get the application with data base acces. 
                </p>
                <p class="text-white-75 mb-5">
                GetCrudByUml é uma ferramenta visual unificada para arquitetos de banco de dados, desenvolvedores e DBAs. Você cria o diagrama de classes UML e obtém o aplicativo com acesso à base de dados.
                </p>


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
                    <h5 class="card-title">Passo 3</h5>
                    <p class="card-text">Clique em um anúncio e ajude nosso site a continuar funcionando!</p>
                    <!-- LOMADEE - BEGIN -->
                    <script src="//ad.lomadee.com/banners/script.js?sourceId=36481616&dimension=8&height=250&width=250&method=0" type="text/javascript" language="javascript"></script>
                    <!-- LOMADEE - END -->
                </div>
            </div>
        </div>
    </div>


    <button type="button" class="btn btn-outline-secondary m-3" data-toggle="modal" data-target="#comecar">
      Quero Começar
    </button>

</section>
            


<div class="modal fade" id="comecar" tabindex="-1" role="dialog" aria-labelledby="labelComecar" aria-hidden="true">
  
<form id="form-email" action=".">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelComecar">Entre com seu e-mail para obter acesso ao sistema!</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
                
            
              <div class="form-group" id="local-do-email">
                <input type="email" placeholder="Endereço de E-mail" class="form-control" id="email" required>                
              </div>
              <a href="?pagina=login">Já tenho conta.</a>


      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Enviar">
      </div>
    </div>
  </div>
</form>

</div>

                


            
<!-- Footer-->
<footer class="py-5">
    <div class="container"><div class="small text-center text-muted">Copyright © 2020 - contato@getcrudbyuml.com</div></div>
</footer> 
';
        
    }
    
    
    
}