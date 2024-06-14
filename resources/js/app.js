import './bootstrap';

import Alpine from 'alpinejs';
import 'flowbite';
/* Begin - our custom JavaScript code */
import './menu'

import.meta.glob([
  '../img/**',
]);

/* End - our custom JavaScript code */

window.Alpine = Alpine;

Alpine.start();
