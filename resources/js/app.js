import '@/core/jquery-3.6.0.min.js';
import '@/other/jquery.inputmask.min.js';
/* import '@/other/form-recall-mask.js'; */
/* import '@/other/fancybox.umd.js'; */
import '@/adm/adm.js';

import './bootstrap';

import.meta.glob([
    '../imgs/**',
    '../fonts/**',
  ]);

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
