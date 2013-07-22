<?php

require_once($CFG->dirroot . "/blocks/settings/renderer.php");
require_once($CFG->dirroot. "/lib/outputrenderers.php");

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
		
		// This function renders the paging bar as a list item
		protected function render_paging_bar(paging_bar $pagingbar) {
			$output = '';
			$pagingbar = clone($pagingbar);
			$pagingbar->prepare($this, $this->page, $this->target);
			$current_page = 1 + $pagingbar->page;
						
			if ($pagingbar->totalcount > $pagingbar->perpage) {
				#$output .= html_writer::tag('li', get_string('page'), array('class'=>'paging-title'));

			
				if (!empty($pagingbar->firstlink)) {
					$output .= html_writer::tag('li', ($pagingbar->firstlink . "<span class=\"ellipsis\">&#160;&#8230;</span>"), array('class'=>'first-page'));
					}
				
				foreach ($pagingbar->pagelinks as $link) {
					
					if ($current_page == $link) {
						$output .= html_writer::tag('li', $link,array('class'=>'current-page')); 
						}
						
					else {
						$output .= html_writer::tag('li', $link,array('class'=>'page-link')); 
						}				
				}

				if (!empty($pagingbar->lastlink)) {
					$output .= html_writer::tag('li', ("<span class=\"ellipsis\">&#8230;&#160;</span>" . $pagingbar->lastlink), array('class'=>'last-page'));
					}
				
				$output .= html_writer::start_tag('ul', array('class'=>'prev-next')); 
					
					if (!empty($pagingbar->previouslink)) {
						$output .= html_writer::tag('li', ($pagingbar->previouslink), array('class'=>'previous-page'));
						}
				
					if (!empty($pagingbar->nextlink)) {
						$output .= html_writer::tag('li', ($pagingbar->nextlink), array('class'=>'next-page'));
						}
						
				$output .= html_writer::end_tag('ul'); 	
			}
			
			return html_writer::tag( 'div', html_writer::tag( 'ul', $output, array('class' => 'paging')), array('class'=>'pager-wrapper') );
		}

		/**
	     * Renders tabtree
	     *
	     * @param tabtree $tabtree
	     * @return string
	     */
	    protected function render_tabtree(tabtree $tabtree) {
	        if (empty($tabtree->subtree)) {
	            return '';
	        }
	        $firstrow = $secondrow = '';
	        foreach ($tabtree->subtree as $tab) {
	            $firstrow .= $this->render($tab);
	            if (($tab->selected || $tab->activated) && !empty($tab->subtree) && $tab->subtree !== array()) {
	                $secondrow = $this->tabtree($tab->subtree);
	            }
	        }
	        return html_writer::tag('ul', $firstrow, array('class' => 'nav nav-tabs')) . $secondrow;
	    }

	    /**
	     * Renders tabobject (part of tabtree)
	     *
	     * This function is called from {@link core_renderer::render_tabtree()}
	     * and also it calls itself when printing the $tabobject subtree recursively.
	     *
	     * @param tabobject $tabobject
	     * @return string HTML fragment
	     */
	    protected function render_tabobject(tabobject $tab) {
	        if ($tab->selected or $tab->activated) {
	            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'active'));
	        } else if ($tab->inactive) {
	            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'disabled'));
	        } else {
	            if (!($tab->link instanceof moodle_url)) {
	                // backward compartibility when link was passed as quoted string
	                $link = "<a href=\"$tab->link\" title=\"$tab->title\">$tab->text</a>";
	            } else {
	                $link = html_writer::link($tab->link, $tab->text, array('title' => $tab->title));
	            }
	            return html_writer::tag('li', $link);
	        }
	    }

}
	