<?php

require_once($CFG->dirroot . "/blocks/settings/renderer.php");
require_once($CFG->dirroot . "/blocks/navigation/renderer.php");
require_once($CFG->dirroot . "/lib/outputrenderers.php");

class theme_ubertheme_block_settings_renderer extends block_settings_renderer {
		
		protected function navigation_node(navigation_node $node, $attrs=array()) {
			$items = $node->children;
	
			// exit if empty, we don't want an empty ul element
			if ($items->count()==0) {
				return '';
			}
	
			// array of nested li elements
			$lis = array();
			foreach ($items as $item) {
				if (!$item->display) {
					continue;
				}
	
				$isbranch = ($item->children->count()>0  || $item->nodetype==navigation_node::NODETYPE_BRANCH);
				$hasicon = (!$isbranch && $item->icon instanceof renderable);
	
				if ($isbranch) {
					$item->hideicon = true;
				}else{
					$item->hideicon = true;
					}
				
				$content = $this->output->render($item);
	
				// this applies to the li item which contains all child lists too
				$liclasses = array($item->get_css_type());
				$liexpandable = array();
				if (!$item->forceopen || (!$item->forceopen && $item->collapse) || ($item->children->count()==0  && $item->nodetype==navigation_node::NODETYPE_BRANCH)) {
					$liclasses[] = 'collapsed';
				}
				if ($isbranch) {
					$liclasses[] = 'contains_branch';
					$liexpandable = array('aria-expanded' => in_array('collapsed', $liclasses) ? "false" : "true");
				} else if ($hasicon) {
					$liclasses[] = 'item_with_icon';
				}
				if ($item->isactive === true) {
					$liclasses[] = 'current_branch';
				}
				$liattr = array('class' => join(' ',$liclasses)) + $liexpandable;
				// class attribute on the div item which only contains the item content
				$divclasses = array('tree_item');
				if ($isbranch) {
					$divclasses[] = 'branch';
				} else {
					$divclasses[] = 'leaf';
				}
				if (!empty($item->classes) && count($item->classes)>0) {
					$divclasses[] = join(' ', $item->classes);
				}
				$divattr = array('class'=>join(' ', $divclasses));
				if (!empty($item->id)) {
					$divattr['id'] = $item->id;
				}
				$content = html_writer::tag('p', $content, $divattr) . $this->navigation_node($item);
				if (!empty($item->preceedwithhr) && $item->preceedwithhr===true) {
				
				}
				$content = html_writer::tag('li', $content, $liattr);
				$lis[] = $content;
			}
	
			if (count($lis)) {
				return html_writer::tag('ul', implode("\n", $lis), $attrs);
			} else {
				return '';
			}
		}	

		// This function renders the administration block search markup
		public function search_form(moodle_url $formtarget, $searchvalue) {
			$content = html_writer::start_tag('form', array('class'=>'adminsearchform', 'method'=>'get', 'action'=>$formtarget, 'role' => 'search'));
			$content .= html_writer::start_tag('div');
			$content .= html_writer::tag('label', s(get_string('searchinsettings', 'admin')), array('for'=>'adminsearchquery', 'class'=>'accesshide'));
			$content .= html_writer::empty_tag('input', array('id'=>'adminsearchquery', 'placeholder'=>'Search Settings', 'type'=>'text', 'name'=>'query', 'value'=>s($searchvalue)));
			$content .= html_writer::tag('button', html_writer::tag('span', s(get_string('search')), array('type'=>'submit', 'value'=>s(get_string('search')))));
			$content .= html_writer::end_tag('div');
			$content .= html_writer::end_tag('form');
			return $content;
		}
		
}

class theme_ubertheme_block_navigation_renderer extends block_navigation_renderer{


		// This function renders the block navigation tree
		 protected function navigation_node($items, $attrs=array(), $expansionlimit=null, array $options = array(), $depth=1) {

        // exit if empty, we don't want an empty ul element
        if (count($items)==0) {
            return '';
        }

        // array of nested li elements
        $lis = array();
        foreach ($items as $item) {
            if (!$item->display && !$item->contains_active_node()) {
                continue;
            }
            $content = $item->get_content();
            $title = $item->get_title();

            $isexpandable = (empty($expansionlimit) || ($item->type > navigation_node::TYPE_ACTIVITY || $item->type < $expansionlimit) || ($item->contains_active_node() && $item->children->count() > 0));
            $isbranch = $isexpandable && ($item->children->count() > 0 || ($item->has_children() && (isloggedin() || $item->type <= navigation_node::TYPE_CATEGORY)));

            // Skip elements which have no content and no action - no point in showing them
            if (!$isexpandable && empty($item->action)) {
                continue;
            }

			// if ($hasicon) {
				// $icon = $this->output->render($item->icon);
				// $icon = '';
			// } else {
				$icon = '';
			// }

            $content = $icon.$content; // use CSS for spacing of icons
            if ($item->helpbutton !== null) {
                $content = trim($item->helpbutton).html_writer::tag('span', $content, array('class'=>'clearhelpbutton'));
            }

            if ($content === '') {
                continue;
            }

            $attributes = array();
            if ($title !== '') {
                $attributes['title'] = $title;
            }
            if ($item->hidden) {
                $attributes['class'] = 'dimmed_text';
            }
            if (is_string($item->action) || empty($item->action) ||
                    (($item->type === navigation_node::TYPE_CATEGORY || $item->type === navigation_node::TYPE_MY_CATEGORY) &&
                    empty($options['linkcategories']))) {
                $attributes['tabindex'] = '0'; //add tab support to span but still maintain character stream sequence.
                $content = html_writer::tag('span', $content, $attributes);
            } else if ($item->action instanceof action_link) {
                //TODO: to be replaced with something else
                $link = $item->action;
                $link->text = $icon.$link->text;
                $link->attributes = array_merge($link->attributes, $attributes);
                $content = $this->output->render($link);
                $linkrendered = true;
            } else if ($item->action instanceof moodle_url) {
                $content = html_writer::link($item->action, $content, $attributes);
            }

            // this applies to the li item which contains all child lists too
            $liclasses = array($item->get_css_type(), 'depth_'.$depth);
            $liexpandable = array();
            if ($item->has_children() && (!$item->forceopen || $item->collapse)) {
                $liclasses[] = 'collapsed';
            }
            if ($isbranch) {
                $liclasses[] = 'contains_branch';
                $liexpandable = array('aria-expanded' => in_array('collapsed', $liclasses) ? "false" : "true");
            } 
            if ($item->isactive === true) {
                $liclasses[] = 'current_branch';
            }
            $liattr = array('class' => join(' ',$liclasses)) + $liexpandable;
            // class attribute on the div item which only contains the item content
            $divclasses = array('tree_item');
            if ($isbranch) {
                $divclasses[] = 'branch';
            } else {
                $divclasses[] = 'leaf';
            }
            if (!empty($item->classes) && count($item->classes)>0) {
                $divclasses[] = join(' ', $item->classes);
            }
            $divattr = array('class'=>join(' ', $divclasses));
            if (!empty($item->id)) {
                $divattr['id'] = $item->id;
            }
            $content = html_writer::tag('p', $content, $divattr);
            if ($isexpandable) {
                $content .= $this->navigation_node($item->children, array(), $expansionlimit, $options, $depth+1);
            }
            if (!empty($item->preceedwithhr) && $item->preceedwithhr===true) {
                $content = html_writer::empty_tag('hr') . $content;
            }
            $content = html_writer::tag('li', $content, $liattr);
            $lis[] = $content;
        }

        if (count($lis)) {
            return html_writer::tag('ul', implode("\n", $lis), $attrs);
        } else {
            return '';
        }
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
			$output = '';
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
	