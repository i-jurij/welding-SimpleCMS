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
<div class="he_soz_tlf sm:flex items-center justify-center">
    <div class="he_soz me-4">
        <?php
            if (!empty($data['telegram'])) {
                echo '<a href="tg://resolve?domain='.$data['telegram'][0].'" title="Telegram" class="he_soz-tg m-2" target="_blank" rel="noopener"></a>';
            }
            if (!empty($data['vk'])) {
                echo '<a href="https://vk.com/'.$data['vk'][0].'" title="Вконтакте" class="he_soz-vk m-2" target="_blank" rel="noopener"></a>';
            }
        ?>
    </div>

    <div class="he_tlf font-bold text-lg">
        <?php
            if (!empty($data['tlf'])) {
                foreach ($data['tlf'] as $tlf) {
                    echo '<span class="me-4"><a class="hover:text-blue-900" href="tel:'.$tlf.'">'.$tlf.'</a></span> ';
                }
            }
        ?>
    </div>
</div>

<div class="he_adres m-4 ">
    <?php
        if (!empty($data['adres'])) {
            echo '<a class="inline-block mb-4 lg:inline lg:mb-0" href="'.url('/').'/map/">'.$data['adres'][0].'</a>';
        }
        ?>
</div>
