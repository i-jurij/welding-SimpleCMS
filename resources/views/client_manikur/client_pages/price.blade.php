@php
if (isset($page_data) && is_array($page_data) && !empty($page_data[0])) {
    $title = $page_data[0]["title"];
    $page_meta_description = $page_data[0]["description"];
    $page_meta_keywords = $page_data[0]["keywords"];
    $robots = $page_data[0]["robots"];
    $content['content'] = $page_data[0]["content"];
} else {
    $title = "Title";
    $page_meta_description = "description";
    $page_meta_keywords = "keywords";
    $robots = "INDEX, FOLLOW";
    $content['content'] = "CONTENT FOR DEL IN FUTURE";
}
@endphp


@extends("layouts/index")

@section("content")
@if (!empty($menu)) <p class="content">{{$menu}}</p> @endif

@if (!empty($data))
    <div class="zapis_usluga margin_rlb1">
<?php
if (!empty($data['serv'])) {
    foreach ($data['serv'] as $page => $cat_arr) {
        $arr = explode('#', $page);
        $page_alias = $arr[0];
        $page_name = $arr[1];
        $i = 1;
        ?>
        <div class="back shad pad margin_rlb1 price ankor" id="<?php echo $page_alias; ?>">
            <table class="table">
                <caption class=""><?php echo '<a href="'.url('/').'/'.$page_alias.'/" ><h2>'.$page_name.'</h2></a>'; ?></caption>
                <colgroup>
                <col width="10%">
                <col width="65%">
                <col width="25%">
                </colgroup>
                <thead>
                <tr>
                    <th>№</th>
                    <th>Услуга</th>
                    <th>Цена, руб.</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($cat_arr as $cat => $serv) {
                    if ($cat !== 'page_serv') {
                        ?>
                        <tr><td colspan="3"><h3><?php echo $cat; ?></h3></td></tr>
                        <?php
                        foreach ($serv as $name => $value) {
                            ?>
                            <tr>
                            <td><?php echo $i; ?></td>
                            <td style="text-align:left"><?php echo $name; ?></td>
                            <td><?php echo $value; ?></td>
                            </tr>
                            <?php
                            ++$i;
                        }
                    } else {
                        ?>
                        <tr><td colspan="3"><h3>Другие услуги</h3></td></tr>
                        <!-- <tr><td colspan="3"></td></tr> -->
                        <?php
                        foreach ($serv as $name => $value) {
                            ?>
                            <tr>
                            <td><?php echo $i; ?></td>
                            <td style="text-align:left"><?php echo $name; ?></td>
                            <td><?php echo $value; ?></td>
                            </tr>
                            <?php
                            ++$i;
                        }
                    }
                }
        ?>
            </tbody>
            </table>
        </div>
    <?php
    }
} else {
    echo '<div class="back shad pad margin_rlb1 price"">Нет прайса для отображения.</div>';
}
?>
</div>
@endif

@stop
