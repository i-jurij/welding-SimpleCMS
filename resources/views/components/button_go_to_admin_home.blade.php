<!--
<a href="{{ url()->route('admin.home') }}"  class="backbutton buttons" id="back_button_admin">
    <img src="{{ Vite::asset('resources/imgs/home.webp') }}" alt="Admin Home"/>
</a>
-->
<div class="backbutton">
<input
    type="image"
    class=" buttons"
    src="{{ Vite::asset('resources/imgs/home.webp') }}"
    onclick = "window.location.assign('<?php echo url()->route('admin.home'); ?>')"
/>
</div>
