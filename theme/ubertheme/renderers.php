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
		
		// This function renders the block controls markup
		public function block_controls($controls) {
			if (empty($controls)) {
				return '';
			}
			$controlshtml = array();
			foreach ($controls as $control) {
				$controlshtml[] = html_writer::tag('a',
						html_writer::empty_tag('img',  array('src' => $this->pix_url($control['icon'])->out(false), 'alt' => $control['caption'])),
						array('class' => 'icon ' . $control['class'],'title' => $control['caption'], 'href' => $control['url']));
			}
			return html_writer::tag('div', implode('', $controlshtml), array('class' => 'commands'));
		}
		
		// This function renders the block content markup
		protected function block_content(block_contents $bc) {
			$output .= html_writer::start_tag('div', array( 'class' => 'block-content-wrapper')) . html_writer::start_tag('div', array('class' => 'content')); 
			if (!$bc->title && !$this->block_controls($bc->controls)) {
				$output .= html_writer::tag('div', '', array('class'=>'block_action notitle'));
			}	
			$output .= $bc->content;
			$output .= $this->block_footer($bc);
			$output .= html_writer::end_tag('div');
			$output .= html_writer::end_tag('div');
	
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
	