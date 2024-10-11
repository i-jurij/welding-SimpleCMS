<!--<a href="{{ url()->route('client.home') }}"  class="backbutton buttons">
    <img src="{{ Vite::asset('resources/imgs/home.png') }}" alt="Home"/>
</a>
-->
<div class="backbutton">
<input
    type="image"
    class=" buttons"
    src="{{ Vite::asset('resources/imgs/home.png') }}"
    onclick = "window.location.assign('<?php echo url()->route('client.home'); ?>')"
/>
</div>
