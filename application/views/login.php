<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Vendish | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/bower_components/font-awesome/css/font-awesome.min.css"> 
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/AdminLTE.min.css"> 

 
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="<?= base_url('Login') ?>"><b>Vendish</b> Demo</a>
  </div>

  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form method="post">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="Email" id="email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="Password" id="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">

        <div class="col-xs-8">
         
        </div> 

        <div class="col-xs-4">
          <button type="button" class="btn btn-primary btn-block btn-flat" id="login">Sign In</button>
        </div> 

      </div>
    </form>

     

  </div> 
</div> 

<!-- jQuery 3 -->
<script src="<?= base_url('assets') ?>/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= base_url('assets') ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(function () {

    const Blogin = document.getElementById('login');

    Blogin.addEventListener('click', async function () { 
        let formLogin       = await getForm(); 

        const validasi      = await validasiform(formLogin);
        
        const loginProsess  = await on_login(formLogin);  

        
        await checkstatus(loginProsess)
    })
 
    async function getForm()
    {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        return {
        'email': email,
        'password': password
        }; 
    }
});

  
    async function validasiform(data) 
    {
        const messageerror = [];   
        data.email ? true :  messageerror.push('Email must required');  
        if(data.password){
            data.password.length >= 3 ? true : messageerror.push('Password minimum 3 character');
        }else{
            messageerror.push('Password  must required');
        }     
        
        const doneshow = await warningalert(messageerror);
        
        return doneshow
    }

    function warningalert(message){  
    // untuk pengkondisian
        let text = message.join('<br>');
        
        if(!text){
            return false;
        }
        const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
        });
        Toast.fire({
        icon: "warning",
        html: text
        })

        return true;
        
    }

    async function on_login(data){
        return  await fetch("<?= base_url('auth/login') ?>", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                'email': data.email,
                'password': data.password
            })
        })
        .then(response => response.json())
        .then(data => {
            return data;
        })
        .catch(error => {
            console.log(error);  
        }); 

    }

    function checkstatus(response)
    {
        if(response.status === true){
            shownotifsucces(response)
        }
        if(response.status === false){
            shownotiferror(response.message)
        } 
    }


    function shownotifsucces(response){
        const Toast = Swal.mixin({
            toast: true,
            position: "top-right",
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
            });
            Toast.fire({
            icon: "success",
            title: response.message
            }).then((result) => { 
                console.log(JSON.stringify(response))
                // localStorage.setItem('coba', JSON.stringify(response));
            window.location.href = '<?= base_url('dashboard') ?>'; //localStorage.setItem('jwtToken', token);
        });
    }


    function shownotiferror(message){
        const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
        });
        Toast.fire({
        icon: "error",
        html: message
        })
    }
</script>
</body>
</html>
