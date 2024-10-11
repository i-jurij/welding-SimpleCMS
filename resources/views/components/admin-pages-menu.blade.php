<?php
/*
    $admin_url = url()->route('admin.home');
    $prev_url = url()->previous();
    $current_url = url()->current();
    $current_route_name = Route::current()->getName();
    $prev = '<a href="'.$prev_url.'">'.mb_ucfirst(last(explode('/', $prev_url))).'</a>';
    $current = '<span>'.mb_ucfirst(str_replace('admin.', '', $current_route_name)).'</span>';
    if ($prev_url !== $current_url && $current_url !== $admin_url) {
        echo $prev.' / '.$current;
    } else {
        echo $current;
    }
*/
?>

<div class="adm_content text_left">
<?php
if (!empty($routes)) {
    print_r($routes);
} else {
    echo 'No routes (pages)';
} ?>
</div>
