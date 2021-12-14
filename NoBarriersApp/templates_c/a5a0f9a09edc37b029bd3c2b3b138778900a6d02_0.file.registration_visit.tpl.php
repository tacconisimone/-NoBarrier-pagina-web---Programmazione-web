<?php
/* Smarty version 3.1.34-dev-7, created on 2019-07-27 12:08:22
  from 'C:\xampp\htdocs\NoBarriersApp\smarty\templates\registration_visit.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5d3c229609adf0_10368152',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a5a0f9a09edc37b029bd3c2b3b138778900a6d02' => 
    array (
      0 => 'C:\\xampp\\htdocs\\NoBarriersApp\\smarty\\templates\\registration_visit.tpl',
      1 => 1564220080,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d3c229609adf0_10368152 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="https://static.pingendo.com/bootstrap/bootstrap-4.3.1.css">
</head>

<body>
<div class="alert">
    <p><?php echo $_smarty_tpl->tpl_vars['errore']->value;?>
</p>
</div>
<div class="py-5 mx-auto bg-info">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="bg-light text-center display-4" contenteditable="true" style="">Registrazione del visitatore</h1>
            </div>
        </div>
    </div>
</div>
<div class="py-5 bg-info mx-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form id="c_form-h" class="mx-auto" method="POST" action="RegistrazVisit.php">
                    <div class="form-group row"> <label for="inputmailh" class="col-2 col-form-label">E-mail</label>
                        <div class="col-10">
                            <input type="email" name="email" class="form-control" id="inputmailh" placeholder="mail@example.com"> </div>
                    </div>
                    <div class="form-group row"> <label for="inputpasswordh" class="col-2 col-form-label">Password</label>
                        <div class="col-10">
                            <input type="password" name="password" class="form-control" id="inputpasswordh" placeholder="Password"> </div>
                    </div>
                    <div class="form-group row"><label class="col-2">Nome</label>
                        <div class="col-10">
                            <div class="input-group"><input type="text" name="nome" class="form-control" id="inlineFormInputGroup" style="" placeholder="Name" ></div>
                        </div>
                    </div>
                    <div class="form-group row"><label class="col-2">Cognome</label>
                        <div class="col-10">
                            <div class="input-group"><input type="text" name="cognome" class="form-control" id="inlineFormInputGroup" placeholder="Surname" ></div>
                        </div>
                    </div>
                    <div class="form-group row"><label class="col-2">Username</label>
                        <div class="col-10">
                            <div class="input-group"><input type="text" name="username" class="form-control" id="inlineFormInputGroup" placeholder="Username"></div>
                        </div>
                    </div><button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous" style=""><?php echo '</script'; ?>
>
</body>

</html><?php }
}
