<?php



class HomeController{
    
    public static function main(){
        
        echo '
   <!-- Masthead-->
<header class="masthead">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-end">
                <h1 class="text-uppercase font-weight-bold m-3">Software Design</h1>
                <hr class="divider my-4" />
            </div>
            <div class="col-lg-8 align-self-baseline">
                <p class="text-white-75 mb-5">Ferramenta para criação de banco de dados a partir de uma modelagem de classes UML. Faça um teste Grátis!</p>
                <a class="btn btn-primary btn-xl js-scroll-trigger" href="#como-fazer">Como Funciona</a>
            </div>
        </div>
    </div>
</header> 


<section class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light" id="como-fazer">
  <div>




    <h1 class="display-4 font-weight-normal m-3 p-3">Como funciona!</h1>


<div class="row">
  <div class="col-sm-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Passo 1</h5>
        <p class="card-text">Utilize os formulários para inserir as classes e atributos.</p>
        <img src="./images/classes.png" alt="Diagrama De Classes" class="img-fluid">
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Passo 2</h5>
        <p class="card-text">Obtenha o código SQL de criação do banco de dados e tenha acesso a todos os recursos durante um mês.</p>
        <img src="./images/sql.png" alt="codigo SQL gerado automaticamente" class="img-fluid">
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Passo 3</h5>
        <p class="card-text">Faça uma doação se você quiser ou assine uma conta premium por R$ 9,90/mês para ter acesso a todas as funcionalidades por mais tempo.</p>
        
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="6DFNFPSADCQC8">
        <input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - A maneira fácil e segura de enviar pagamentos online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
        </form>

<br><br>

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick" />
        <input type="hidden" name="hosted_button_id" value="BXZLNT3CHZU7Q" />
        <input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
        <img alt="" border="0" src="https://www.paypal.com/pt_BR/i/scr/pixel.gif" width="1" height="1" />
        </form>


<br>

      </div>
    </div>
  </div>

</div>


<button type="button" class="btn btn-outline-secondary m-3" data-toggle="modal" data-target="#comecar">
  Quero Começar
</button>

  </div>
  <div class="product-device box-shadow d-none d-md-block"></div>
  <div class="product-device product-device-2 box-shadow d-none d-md-block"></div>
</section>
            
            
';
        echo '
<!-- Modal -->
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
    <div class="container"><div class="small text-center text-muted">Copyright © 2020 - jefponte</div></div>
</footer> 
';
        
    }
    
    
    
}