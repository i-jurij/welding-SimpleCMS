<?php
if (!empty($content['contacts'])) {
    $data = ['tlf' => [], 'adres' => [], 'vk' => [], 'telegram' => []];
    foreach ($content['contacts'] as $value) {
        if ($value['type'] === 'tlf') {
            $data['tlf'][] = $value['data'];
        }
        if ($value['type'] === 'adres') {
            $data['adres'][] = $value['data'];
        }
        if ($value['type'] === 'vk') {
            $data['vk'][] = $value['data'];
        }
        if ($value['type'] === 'telegram') {
            $data['telegram'][] = $value['data'];
        }
    }
} else {
    if (!empty($data['telegram']) && !empty($data['vk']) && !empty($data['tlf']) && !empty($data['adres'])) {
        $data['telegram'] = ['tg'];
        $data['vk'] = ['vk'];
        $data['tlf'] = ['+7999 777 66 55'];
        $data['adres'] = ['adres'];
    }
}

?>
<div class="he_soz_tlf flex">
        <div class="he_soz">
            <?php
                if (!empty($data['telegram'])) {
                    echo '<a href="tg://resolve?domain='.$data['telegram'][0].'" title="Telegram" class="he_soz-tg" target="_blank" rel="noopener"></a>';
                }
                if (!empty($data['vk'])) {
                    echo '<a href="https://vk.com/'.$data['vk'][0].'" title="Вконтакте" class="he_soz-vk" target="_blank" rel="noopener"></a>';
                }
?>
        </div>

        <div class="he_tlf">
            <?php
    if (!empty($data['tlf'])) {
        foreach ($data['tlf'] as $tlf) {
            echo '<a href="tel:'.$tlf.'">'.$tlf.'</a><br /> ';
        }
    }
?>
        </div>
    </div>

    <div class="he_adres">
        <?php
if (!empty($data['adres'])) {
    /*
    if (!empty($data['map'])) {
        print '<a class="he_adres_a" href="'.$data['map'].'">'.$data['adres'][0].'</a>';
    }
    else {
        print '<span class="he_adres_a">'.$data['adres'][0].'</span>';
    }
    */
    echo '<a class="he_adres_a" href="'.url('/').'/map/">'.$data['adres'][0].'</a>';
}
?>
    </div>
