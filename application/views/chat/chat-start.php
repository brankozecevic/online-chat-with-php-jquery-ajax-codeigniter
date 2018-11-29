<?php if($this->session->flashdata('user_loggedin')) : ?>
    <p class="alert alert-success"><?= $this->session->flashdata('user_loggedin') ?></p>  
<?php endif; ?>
<script>
    let user_id = '<?php echo $_SESSION['user_id']; ?>';
    let base_url = '<?= base_url() ?>';
</script>
    <div id="messageArea">
        <div class="row">
            <div class="col-md-4">
                <h1><?= $title ?></h1>
                <h3>Rules</h3>
                <p>Every user need to relog every 30 min.</p>
                <p>Messages older than 10 min. will be deleted from chat.</p>
                <h3>Active Users</h3>
                <ul class="list-group" id="users">
                </ul>
            </div>
            <div class="col-md-8">
                <label>Enter message: </label>
                <textarea class="form-control" id="message"></textarea>
                    <p id="error1" class="alert alert-danger"></p>
                    <p id="error2" class="alert alert-danger"></p>
                <br>
                <input id="send-message" class="btn btn-primary" type="submit" value="Send message...">
                <br><br>
                <div id="chat">
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>js/chat.js"></script>