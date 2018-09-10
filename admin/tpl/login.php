<?php ob_start(); ?>
<div class="container" style="max-width: 390px; margin-top: 80px;">
    <h1 class="text-center">URL Shortener</h1>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Please log in</h2>
        </div>
        <div class="panel-body">
            <form method="POST" action="./?action=login" accept-charset="UTF-8" class="form" role="form">
                <input type="hidden" name="_token" value="<?= TOKEN ?>" />
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" placeholder="Username" name="username" type="text">
                    </div>

                    <div class="form-group">
                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary btn-block">Log In</button>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="text-center">
        <em>Provided by</em><br/>
        <img src="./assets/img/logo.png" class="img-responsive" style="margin: 5px auto; max-width: 200px;" />
    </div>
</div>
<?php
$content = ob_get_clean();
require_once('_layout.php');
?>