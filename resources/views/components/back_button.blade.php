<!--
    <a href="{{ url()->previous() }}"  class="backbutton buttons" id="back_button">
    <img src="{{ Vite::asset('resources/imgs/back.webp') }}" alt="Back"/>
</a>
-->
<div class="backbutton" id="back_button">
<input
    type="image"
    class=" buttons"
    src="{{ Vite::asset('resources/imgs/home.webp') }}"
    onclick = "window.location.assign('<?php echo url()->previous(); ?>')"
/>
</div>
