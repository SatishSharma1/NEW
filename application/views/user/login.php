<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="<?=base_url('assets')?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url('assets')?>/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?=base_url('assets')?>/css/animate.css" rel="stylesheet">
    <link href="<?=base_url('assets')?>/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>

                 <?php if(empty($logoname)){ ?>
                     <h1 class="logo-name" style="letter-spacing:-14px;">K-UI</h1>
                <?php }else{ ?>
              <h1>  <img src="<?=base_url()?>uploads/user_pic/<?=$logoname?>" width="300"> </h1>
                 <?php } ?>

            </div>
            <h3>Welcome to K-UI - Knowlarity</h3>
            <p>Perfectly designed and higlhly powered tool for your organisation
                <!--Continually expanded and constantly improved LMS Admin Them (IN+)-->
            </p>
            <p>Login in. To see it in action.</p>
            <form class="m-t" role="form" id="loginForm" action="<?php echo base_url('user/login')?>" method="post" name="loginForm">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="userEmail" id="userEmail" name="userEmail" required="">
                     <?php if(form_error('userEmail')){?>
                        <span class="help-inline"><?php echo "<font color=red>".form_error('userEmail')."</font>" ;?></span>
                    <?php }?>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password" required="">

                       <?php if($this->session->flashdata('forgotPasswordSuccess')){?>
                    <span class="help-inline"><?php echo "<font color=green>".$this->session->flashdata('forgotPasswordSuccess')."</font>" ;?></span>
                    <?php }?>
                    <?php if($this->session->flashdata('forgotPasswordSuccessMsg')){?>
                    <span class="help-inline"><?php echo "<font color=green>".$this->session->flashdata('forgotPasswordSuccessMsg')."</font>" ;?></span>
                    <?php }?>
                    
                    <?php if($this->session->flashdata('accountActivated')){?>
                    <span class="help-inline"><?php echo "<font color=green>".$this->session->flashdata('accountActivated')."</font>" ; ?></span>
                    <?php } ?>
                    <?php if(form_error('password')){?>
                    <span class="help-inline"><?php echo "<font color=red>".form_error('password')."</font>" ;?></span>
                    <?php }?>

                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

              <!--  <a href="#"><small>Forgot password?</small></a>  -->
            </form>
            <p class="m-t"> <small>K-UI - Knowlarity &copy; 2016</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?=base_url('assets')?>/js/jquery-2.1.1.js"></script>
    <script src="<?=base_url('assets')?>/js/bootstrap.min.js"></script>

</body>

</html>
