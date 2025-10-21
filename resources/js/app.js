// import 'ionicons/dist/css/ionicons.min.css';

import { Camera } from 'ionicons/icons';
import { createIcons, Heart, User, BadgeCheck, Camera } from 'lucide';

createIcons({ icons: { Heart, User, BadgeCheck, Camera } });

import 'photoswipe/dist/photoswipe.css';

import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

const lightbox = new PhotoSwipeLightbox({
  gallery: '#post-gallery',
  children: 'a',
  pswpModule: () => import('photoswipe'),
});
lightbox.init();