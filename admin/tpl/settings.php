<?php ob_start(); ?>
    <div class="container" style="max-width: 390px; margin-top: 80px;">
        <h1 class="text-center">Welcome to the URL Shortener!</h1>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Initial Setup</h2>
            </div>
            <div class="panel-body">
                <p>In order to get started, we need to set up a user.</p>
                <p>
                    Once you create your user, you'll be able to log in to the admin panel from this page:
                    <a href="<?= DOMAIN . 'admin' ?>"><?= DOMAIN . 'admin' ?></a>.
                </p>
                <p><strong>Be sure to bookmark that link!</strong></p>

                <?php if(!file_exists('../data')): ?>
                    <div class="alert alert-danger">
                        <p><strong>It looks like your data folder is missing.</strong></p>
                        <p>Please create a folder called <code>data</code> in your url-shortener directory, and make sure that it is writable by the web server</p>
                    </div>
                <?php elseif(!is_writable('../data')): ?>
                    <div class="alert alert-danger">
                        <p><strong>It looks like your data folder is not writable.</strong></p>
                        <p>Please make sure that your data folder is writable by running<br/><code>chmod data -R 774</code></p>
                    </div>
                <?php else: ?>
                    <form method="POST" action="./?action=settings" accept-charset="UTF-8" class="form" role="form">
                        <input type="hidden" name="_token" value="<?= TOKEN ?>" />
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Username" name="username" type="text">
                            </div>

                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>
                            <button type="submit" class="btn btn-lg btn-primary btn-block">Complete Setup</button>
                        </fieldset>
                    </form>
                <?php endif; ?>
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