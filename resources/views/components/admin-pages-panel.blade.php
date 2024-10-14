<div class="adm_content text_left">
<?php
if (!empty($routes)) {
    routs_array_out($routes, Auth::user()->status);
} else {
    echo 'No routes (pages)';
} ?>
</div>
