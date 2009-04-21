function twig_show_more(id) {
  jQuery('#twig_block_'+id).show();
  jQuery('#twig_show_button_'+id).hide();
  jQuery('#twig_hide_button_'+id).show();
}

function twig_hide_more(id) {
  jQuery('#twig_block_'+id).hide();
  jQuery('#twig_show_button_'+id).show();
  jQuery('#twig_hide_button_'+id).hide();
}