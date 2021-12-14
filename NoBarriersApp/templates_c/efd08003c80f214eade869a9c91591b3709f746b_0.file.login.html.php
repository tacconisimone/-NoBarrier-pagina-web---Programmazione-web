<?php
/* Smarty version 3.1.34-dev-7, created on 2019-08-19 19:56:28
  from 'C:\xampp\htdocs\NoBarriersApp\smarty\templates\login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5d5ae2cc9282e6_74533484',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'efd08003c80f214eade869a9c91591b3709f746b' => 
    array (
      0 => 'C:\\xampp\\htdocs\\NoBarriersApp\\smarty\\templates\\login.tpl',
      1 => 1566237385,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d5ae2cc9282e6_74533484 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- PAGE settings -->
    <link rel="icon" href="https://templates.pingendo.com/assets/Pingendo_favicon.ico">
    <title>Checkout</title>
    <meta name="description" content="Wireframe design of a checkout form by Pingendo">
    <meta name="keywords" content="Pingendo bootstrap example template wireframe checkout form">
    <meta name="author" content="Pingendo">
    <!-- CSS dependencies -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="/NoBarriersApp/smarty/templates/css/wireframe.css">
</head>

<body style="	background-image: url(/NoBarriersApp/smarty/immagini/login.png);	background-position: top left;	background-size: cover;">
<div class="alert">
    <p><?php echo $_smarty_tpl->tpl_vars['errore']->value;?>
</p>
</div>
<!--<div class="py-5 mx-3 mb-3" style="background-image: url('/NoBarriersApp/smarty/immagini/login.png');background-size:cover;">
-->
    <div class="container">
        <div class="row">
            <div class="mx-auto p-5 my-5 col-md-8">
                <h1 class="bg-light text-center display-4" style="">Login</h1>
            </div>
        </div>
    </div>
<!--
</div>
-->
<!--<div class="py-5 bg-primary mx-auto" style="">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="display-2">Login</h1>
            </div>
        </div>
    </div>
</div>
-->
<div class="bg-info m-4 mt-2 mb-4 my-2" style="">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form id="c_form-h" method="POST" >
                    <div class="form-group row"> <label for="inputusername" class="col-2 col-form-label">Username</label>
                        <div class="col-10">
                            <input type="text" name="username" class="form-control" placeholder="Username" id="inputusername"> </div>
                    </div>
                    <div class="form-group row"> <label for="inputpasswordh" class="col-2 col-form-label">Password</label>
                        <div class="col-10">
                            <input type="password" name="password" class="form-control" id="inputpasswordh" placeholder="Password"> </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="assets/js/validation.js"><?php echo '</script'; ?>
>
</body>

</html><?php }
}
