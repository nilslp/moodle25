<?php

require_once($CFG->dirroot . "/blocks/settings/renderer.php");
require_once($CFG->dirroot.'/lib/outputrenderers.php');

class theme_ubertheme_block_settings_renderer extends block_settings_renderer {
	    
		// This function renders the administration block search markup
		public function search_form(moodle_url $formtarget, $searchvalue) {
			$content = html_writer::start_tag('form', array('class'=>'adminsearchform', 'method'=>'get', 'action'=>$formtarget, 'role' => 'search'));
			$content .= html_writer::start_tag('div');
			$content .= html_writer::tag('label', s(get_string('searchinsettings', 'admin')), array('for'=>'adminsearchquery', 'class'=>'accesshide'));
			$content .= html_writer::empty_tag('input', array('id'=>'adminsearchquery', 'placeholder'=>'Search Settings', 'type'=>'text', 'name'=>'query', 'value'=>s($searchvalue)));
			$content .= html_writer::tag('button', s(get_string('search')), array('type'=>'submit', 'value'=>s(get_string('search'))));
			$content .= html_writer::end_tag('div');
			$content .= html_writer::end_tag('form');
			return $content;
		}
		
}

class theme_ubertheme_core_renderer extends core_renderer{
	
		// This function renders the block header markup
		protected function block_header(block_contents $bc) {

			$title = '';
			if ($bc->title) {
				$attributes = array();
				if ($bc->blockinstanceid) {
					$attributes['id'] = 'instance-'.$bc->blockinstanceid.'-header';
				}
				$title = html_writer::tag('h2', $bc->title, $attributes);
			}
	
			$controlshtml = $this->block_controls($bc->controls);
	
			$output = '';
			if ($title || $controlshtml) {

			$output .= html_writer::tag('div', html_writer::tag('div', $title . html_writer::tag('div',  $controlshtml . html_writer::tag('div', '', array('class'=>'block_action')) , array('class' => 'actions-commands')), array('class' => 'title')), array('class' => 'header'));
			}
			return $output;
			
		}
		// This function renders the block content markup
		public function block(block_contents $bc, $region) {
			$bc = clone($bc); // Avoid messing up the object passed in.
			if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
				$bc->collapsible = block_contents::NOT_HIDEABLE;
			}
			$skiptitle = strip_tags($bc->title);
			if ($bc->blockinstanceid && !empty($skiptitle)) {
				$bc->attributes['aria-labelledby'] = 'instance-'.$bc->blockinstanceid.'-header';
			} else if (!empty($bc->arialabel)) {
				$bc->attributes['aria-label'] = $bc->arialabel;
			}
			if ($bc->collapsible == block_contents::HIDDEN) {
				$bc->add_class('hidden');
			}
			if (!empty($bc->controls)) {
				$bc->add_class('block_with_controls');
			}
	
	
			if (empty($skiptitle)) {
				$output = '';
				$skipdest = '';
			} else {
				$output = html_writer::tag('a', get_string('skipa', 'access', $skiptitle), array('href' => '#sb-' . $bc->skipid, 'class' => 'skip-block'));
				$skipdest = html_writer::tag('span', '', array('id' => 'sb-' . $bc->skipid, 'class' => 'skip-block-to'));
        }

        $output .= html_writer::start_tag('div', $bc->attributes);

        $output .= $this->block_header($bc);
        $output .= html_writer::start_tag('div', array( 'class' => 'block-content-wrapper'));
		$output .= $this->block_content($bc);
		$output .= html_writer::end_tag('div');
        $output .= html_writer::end_tag('div');

        $output .= $this->block_annotation($bc);

        $output .= $skipdest;

        $this->init_block_hider_js($bc);
        return $output;
   		}
		
		// This function renders empty separators spans on the breadcrumb
		public function navbar() {
			$items = $this->page->navbar->get_items();
			$breadcrumbs = array();
			foreach ($items as $item) {
				$item->hideicon = true;
				$breadcrumbs[] = $this->render($item);
			}
			$divider = '<span class="divider"></span>';
			$list_items = '<li>'.join(" $divider</li><li>", $breadcrumbs).'</li>';
			$title = '<span class="accesshide">'.get_string('pagepath').'</span>';
			return $title . "<ul class=\"breadcrumb\">$list_items</ul>";
    	}
	
}
	