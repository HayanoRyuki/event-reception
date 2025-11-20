<?php
add_filter('excerpt_length', function($length) {
  return is_post_type_archive('help') ? 40 : $length;
}, 999);
