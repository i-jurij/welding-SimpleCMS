<!--<a href="{{ url()->route('client.home') }}"  class="backbutton buttons">
    <img src="{{ Vite::asset('resources/imgs/home.webp') }}" alt="Home"/>
</a>
-->
<div class="backbutton">
<input
    type="image"
    class=" buttons"
    src="{{ Vite::asset('resources/imgs/home.webp') }}"
    onclick = "window.location.assign('<?php echo url()->route('client.home'); ?>')"
/>
</div>
