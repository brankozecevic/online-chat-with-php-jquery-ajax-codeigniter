        <form id="loginForm" action="<?= base_url().'chat/login' ?>" method="post">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <?= validation_errors() ?> 
                    <?php if($this->session->flashdata('user_failed')) : ?>
                    <p class="alert alert-danger"><?= $this->session->flashdata('user_failed') ?></p>  
                    <?php endif; ?>
                    <h1 class="text-center"><?= $title  ?></h1>
                    <div class="form-group">
                        <input type="text" id="username" class="form-control" name="username" placeholder="Username" required autofocus>
                    </div>
                    <p id="error1" class="alert alert-danger"></p>
                    <p id="error2" class="alert alert-danger"></p>
                    <button id="submit" type="submit" class="btn btn-primary btn-block">Login</button>
                </div>
                <div class="col-md-4"></div>
            </div>
        </form>
        <script src="<?= base_url() ?>js/validation.js"></script>