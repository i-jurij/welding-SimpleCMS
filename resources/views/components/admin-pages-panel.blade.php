<?php
use Illuminate\Support\Facades\Auth;

function panel(array $variable)
{
    $res = '';
    foreach ($variable as $key => $value) {
        if (is_array($value) && !empty($value)) {
            $class = 'back shad rad pad mar display_inline_block';
            $p = '<p class="pad"><b>'.my_mb_ucfirst(str_replace('_', ' ', $key)).'</b></p>';
            if ($key == 'admin') {
                $key = '';
                $p = '';
                $class = 'back shad rad pad margin_rlb1 justify';
            }
            $res .= '<div class="'.$class.'">'.$p;
            $res .= panel($value);
            $res .= '</div>';
        }
        // all pages
        if (is_string($value) && !empty($value) && ($key !== 'admin' && $value !== 'admin.home')) {
            $res .= '<a href="'.url()->route($value).'" class="buttons">'.my_mb_ucfirst(str_replace('_', ' ', $key)).'</a>';
        }
    }

    return $res;
}

function each_routs_array(array $routes, string $user_status)
{
    foreach ($routes[$user_status] as $key => $value) {
        echo panel($value);
    }
}

function routs_array_out(array $routes)
{
    $user_status = Auth::user()->status;

    if (isset($routes[$user_status])) {
        if ($user_status === 'admin') {
            each_routs_array($routes, 'admin');
            echo '<br>';
            each_routs_array($routes, 'moder');
            echo '<br>';
            each_routs_array($routes, 'user');
        }
        if ($user_status === 'moder') {
            each_routs_array($routes, 'moder');
            each_routs_array($routes, 'user');
        }
        if ($user_status === 'user') {
            each_routs_array($routes, 'user');
        }
    }
}
?>

<div class="adm_content text_left">
<?php
if (!empty($routes)) {
    routs_array_out($routes);
} else {
    echo 'No routes (pages)';
} ?>
</div>
