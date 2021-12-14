<?php
/* Smarty version 3.1.34-dev-7, created on 2019-08-18 13:15:43
  from 'C:\xampp\htdocs\NoBarriersApp\smarty\templates\formactivity.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5d59335fe6edf0_67871524',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0844e964fc51eaa23481d754d762a7bea331b731' => 
    array (
      0 => 'C:\\xampp\\htdocs\\NoBarriersApp\\smarty\\templates\\formactivity.tpl',
      1 => 1566126938,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d59335fe6edf0_67871524 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="https://static.pingendo.com/bootstrap/bootstrap-4.3.1.css">
    <?php echo '<script'; ?>
 language="Javascript">
        function preview()
        {
            // prelevo l'indirizzo locale dell'immagine da caricare
            immagine_da_caricare = document.getElementById('upload_immagine').value
            // swappo l'immagine presente con quella da caricare
            // file:/// -> serve in particolare per firefox altrimenti non visualizza immagine
            document.getElementById('anteprima_immagine').src = "file:///"+immagine_da_caricare;

        }
    <?php echo '</script'; ?>
>
</head>
</head>

<body>
  <div class="alert">
    <p><?php echo $_smarty_tpl->tpl_vars['errore']->value;?>
</p>
    <div class="py-5 mx-auto bg-info">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1 class="bg-light text-center display-4" style="">Pubblica una nuova attività!</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="py-5 bg-info mx-auto">
      <div class="container">
        <div class="row">
          <form action="upload.asp" method="post" enctype="multipart/form-data"> 
          <div>Immagine principale  <input type="file" name="imgprincipale" >
          </div>
          <div> Galleria di immagini <input type="file" name="galleria" >

          <div class="form-group"><label>Aggiungi img galleria</label>
          <button type="button" class="btn btn-primary mx-auto" onclick="preview()">Aggiungi</button>
              <img id="anteprima_immagine" src="/NoBarriersApp/smarty/immagini/default.jpg" border="0" width="85" height="65"><br/><br/>
              <input type="file" size="30" onchange="preview()" id="upload_immagine">
          </div>
          </div>
         <!--  <?php echo '<script'; ?>
 type="text/javascript">
          function AggiungiImmagine() {
          var btn = document.createElement("button");
        btn.innerHTML = "Seleziona";
        document.getElementById("pulsante").appendChild(btn);
      }
-->
          </form>
        

          <div class="col-md-12">
            <form id="c_form-h" method="POST" class="mx-auto">
              <div class="form-group row"><label class="col-2">Nome Attività</label>
                <div class="col-10">
                  <div class="input-group"><input type="text" class="form-control" id="inlineFormInputGroup" style="" placeholder="Activity"></div>
                </div>
              </div>
              <div class="form-group row"><label class="col-2">Username</label>
                <div class="col-10">
                  <div class="input-group"><input type="text" class="form-control" id="inlineFormInputGroup" style="" placeholder="username"></div>
                </div>
              </div>
              <div class="form-group row"><label class="col-2">Città</label>
                <div class="col-10">
                  <div class="input-group"><input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Town"></div>
                </div>
              </div>
              <div class="form-group row"><label class="col-2">Indirizzo</label>
                <div class="col-10">
                  <div class="input-group"><input type="text" class="form-control" id="inlineFormInputGroup" placeholder="address"></div>
                </div>
              </div>
              <div class="form-group row"><label class="col-2">Categoria</label>
                <div class="col-10">
                  <div class="input-group"><input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Category"></div>
                </div>
              </div>
            </form>
            <div class="form-group"><label>Descrizione</label><textarea class="form-control mx-auto" id="form35" rows="3" placeholder="Your description"></textarea><button type="submit" class="btn btn-primary mx-auto">Submit</button></div>
          </div>
        </div>
      </div>
    </div>
    <?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" style=""><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous" style=""><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous" style=""><?php echo '</script'; ?>
>
  </div>
</body>

</html><?php }
}
