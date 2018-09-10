<?php
ob_start();

// Load Redirects
// ---------------------------------------
$links = [];
$files = glob(DATA_DIR . '*.url');
foreach($files as $file){
    $fileName = str_replace([DATA_DIR, '.url'], '', $file);
    $links[ $fileName ] = file_get_contents($file);
}
?>
<nav role="navigation" class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header"><a href="https://segmetrics.io/" class="navbar-brand">
                <span class="provided">Provided By</span>
                <img src="./assets/img/logo-white.png" alt="SegMetrics">
            </a></div>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="./?action=logout" data-method="POST">Logout</a></li>
        </ul>

    </div>
</nav>

<section class="hero text-center image black shadow">
    <div class="container container-sm">
        <h1>Custom URL Shortener</h1>
        <p>Track and manage every shortlink, <strong>without</strong> losing your tracking information</p>

        <form method="POST" action="./?action=create" id="create-link" accept-charset="UTF-8" class="form" role="form">
            <div class="input-group input-group-lg" style="margin-top: 20px;">
                <input type="hidden" name="_token" value="<?= TOKEN ?>" />
                <input type="text" class="form-control" name="dest" placeholder="Paste in your link to shorten" autocomplete="off">
                <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">Shorten!</button>
            </span>
            </div><!-- /input-group -->
        </form>
    </div>
</section>
<section class="white">
    <div class="container">

        <?php if(empty($links)):?>
        <h3>It looks like you don't have any links.</h3>
        <p>Enter a link in the form above to generate a short link!</p>
        <?php endif ?>

        <div class="alert alert-info"><strong>Hint:</strong> You can click "edit" on a short link to customize the shortened URL.</div>
        <table class="table table-condensed table-striped" id="link-table">
            <thead>
            <tr>
                <th></th>
                <th></th>
                <th style="width: 120px;"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($links as $link => $destination): ?>
            <tr id="row-<?= $link ?>">
                <td>
                    <button class="btn btn-xs btn-primary-outline" data-toggle="copy" data-target="#link-<?= $link ?>">Copy</button>&nbsp;&nbsp;
                    <span id="link-<?= $link ?>"><?= DOMAIN . $link ?></span>
                </td>
                <td><a id="dest-<?= $link ?>" href="<?= $destination ?>" target="_blank"><?= $destination ?></a></td>
                <td class="text-right">

                    <button class="btn btn-xs btn-info" data-toggle="edit" data-target="<?= $link ?>">Edit</button>&nbsp;&nbsp;
                    <button class="btn btn-xs btn-danger" data-toggle="delete" data-target="<?= $link ?>">Delete</button>

                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>



    </div>
</section>

<section id="footer" class="footer">
    <div class="container">
        <h3>Thanks for using the URL Shortener!</h3>
        <hr/>
        <div>The URL Shortener is provided under the <a href="../LICENSE">MIT License</a> by SegMetrics</div>
        <div><a href="https://segmetrics.io">SegMetrics</a> | <a href="https://github.com/SegMetrics/url-shortener">Github</a></div>
        <img src="./assets/img/logo-white.png" alt="SegMetrics" style="height: 30px; margin-top: 50px;">
    </div>
</section>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edit Shortlink</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" id="edit-link" action="./?action=edit" accept-charset="UTF-8" class="form" role="form">
                        <input type="hidden" name="_token" value="<?= TOKEN ?>" />
                        <input type="hidden" name="old" />
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Choose a slug (numbers, letters, hyphens and dashes only)" name="link" type="text" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <input class="form-control" placeholder="Paste in your link to shorten" name="dest" type="text" value="" autocomplete="off">
                            </div>

                            <button type="submit" class="btn btn-lg btn-primary btn-block">Update Link</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
$content = ob_get_clean();
require_once('_layout.php');
?>