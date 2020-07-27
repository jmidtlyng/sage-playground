<?php
namespace App;

class walker_site_navigation extends \Walker_Nav_menu {
    function start_lvl(&$output, $depth = 0, $args = []) {
        if($depth < 1){
          $output .= '<div data-drawer hidden><ul class="absolute bg-black pl-3 shadow-xl">';
        } else {
          $output .= '<div data-drawer hidden><ul class="bg-black pl-3 shadow-xl">';
        }
    }

    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
      $listItem = $this->createListItem($item, $args, $depth);
      $link = $this->createLink($item, $args, $depth);
      $menuItemOutput = $listItem . $link;

      $output .= apply_filters('walker_nav_menu_start_el', $menuItemOutput, $item, $depth, $args);
    }

    function createListItem($item, $args, $depth) {
      return '<li>';
    }

    function createLink($item, $args, $depth) {
        if($args->walker->has_children){
            $linkTag .=  '<button data-toggle class="no-underline focus:underline hover:underline hover:text-gray-400">'.
              $item->title.'<span class="nav-expand font-normal text-base">(expand)</span></a></button>';
        } else {
            $linkAttributes = [];
            $linkAttributes['title'] = esc_attr($item->attr_title);
            $linkAttributes['target'] = esc_attr($item->target);
            $linkAttributes['rel'] = esc_attr($item->xnf);
            $linkAttributes['href'] = esc_attr($item->url);

            $linkAttributesCombined = '';
            foreach ($linkAttributes as $attribute => $attributeValue) {
              if (!empty($attributeValue)) {
                $linkAttributesCombined .= ' ' . $attribute . '=' . $attributeValue . ' ';
              }
            }

            //$linkAttributesCombined .= $depth ? 'class="text-gray-400 cursor-not-allowed"' : 'class="text-blue-500 hover:text-blue-800"';
            $linkAttributesCombined .= 'class="no-underline focus:underline hover:underline hover:text-gray-400"';
            $linkTag = '';
            $linkTag .= $args->before;
            $linkTag .= '<a' . $linkAttributesCombined . '>';
            $linkTag .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $linkTag .= '</a>';
            $linkTag .= $args->after;
        }

        return $linkTag;
    }
}

class walker_front_page extends \Walker_Nav_menu {
    function start_lvl(&$output, $depth = 0, $args = []) {
        $output .= '<div data-drawer hidden><ul>';
    }

    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
      $listItem = $this->createListItem($item, $args, $depth);
      $link = $this->createLink($item, $args, $depth);
      $menuItemOutput = $listItem . $link;

      $output .= apply_filters('walker_nav_menu_start_el', $menuItemOutput, $item, $depth, $args);
    }

    function createListItem($item, $args, $depth) {
      return '<li class="menu menu--linethrough">';
    }

    function createLink($item, $args, $depth) {
        $title = $depth > 0 ? ".".$item->title : $item->title;
        if($args->walker->has_children){
            $linkTag .=  '<button data-toggle><a class="menu__link" href="#" onclick="return false;">'.$title.
              '<span class="nav-expand font-normal text-base">(expand)</span></a></button>';
        } else {
            $linkAttributes = [];
            $linkAttributes['title'] = esc_attr($item->attr_title);
            $linkAttributes['target'] = esc_attr($item->target);
            $linkAttributes['rel'] = esc_attr($item->xnf);
            $linkAttributes['href'] = esc_attr($item->url);

            $linkAttributesCombined = '';
            foreach ($linkAttributes as $attribute => $attributeValue) {
              if (!empty($attributeValue)) {
                $linkAttributesCombined .= ' ' . $attribute . '=' . $attributeValue . ' ';
              }
            }

            //$linkAttributesCombined .= $depth ? 'class="text-gray-400 cursor-not-allowed"' : 'class="text-blue-500 hover:text-blue-800"';
            $linkAttributesCombined .= 'class="menu__link"';
            $linkTag = '';
            $linkTag .= $args->before;
            $linkTag .= '<a' . $linkAttributesCombined . '>';
            $linkTag .= $args->link_before . apply_filters('the_title', $title, $item->ID) . $args->link_after;
            $linkTag .= '</a>';
            $linkTag .= $args->after;
        }

        return $linkTag;
    }
}
