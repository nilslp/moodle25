<?php

#require_once($CFG->dirroot.'/lib/outputrenderers.php');

class theme_foundation_core_renderer extends core_renderer {

    /**
     * Internal implementation of paging bar rendering.
     * @param paging_bar $pagingbar
     * @return string
     */
    protected function render_paging_bar(paging_bar $pagingbar) {
        $output = '';
        $html = '';
        $pagingbar = clone($pagingbar);
        $pagingbar->maxdisplay = 10;
        $pagingbar->prepare($this, $this->page, $this->target);
        
        $regex_html_tags = '/(?<=^|>)[^><]+?(?=<|$)/';
        $lt_arrow = '&laquo;';
        $rt_arrow = '&raquo;';

        if ($pagingbar->totalcount > $pagingbar->perpage) {
            #$output .= get_string('page') . ':';
            $output .= html_writer::start_tag('div', array('class'=>'paging'));
            $output .= html_writer::start_tag('ul');

            if (!empty($pagingbar->previouslink)) {
                #$output .= '&#160;(' . $pagingbar->previouslink . ')&#160;';
                $prevlink = preg_replace($regex_html_tags,$lt_arrow,$pagingbar->previouslink);
                if (!stristr($prevlink, '<a')) $prevlink = html_writer::tag('span', $prevlink);
                $output .= html_writer::tag('li', $prevlink, array('class'=>'prev'));
            }
            else {
                $output .= html_writer::tag('li', html_writer::tag('span','&laquo;'), array('class'=>'prev disabled'));
            }

            if (!empty($pagingbar->firstlink)) {
                #$output .= '&#160;' . $pagingbar->firstlink . '&#160;...';
                $output .= html_writer::tag('li', $pagingbar->firstlink, array('class'=>'first'));
                $output .= html_writer::tag('li', html_writer::tag('span', '&hellip;', array('class'=>'nolink')), array('class'=>'disabled'));
            }

            foreach ($pagingbar->pagelinks as $link) {
                #$output .= "&#160;&#160;$link";
                if (!stristr($link, '<a')) {
                    $output .= html_writer::tag('li',html_writer::tag('span', $link), array('class'=>'active'));
                }
                else {
                    $output .= html_writer::tag('li',$link);
                }
            }

            if (!empty($pagingbar->lastlink)) {
                #$output .= '&#160;...' . $pagingbar->lastlink . '&#160;';
                $output .= html_writer::tag('li', html_writer::tag('span', '&hellip;', array('class'=>'nolink')), array('class'=>'disabled'));
                $output .= html_writer::tag('li', $pagingbar->lastlink, array('class'=>'last'));
            }

            if (!empty($pagingbar->nextlink)) {
                #$output .= '&#160;&#160;(' . $pagingbar->nextlink . ')';
                $nextlink = preg_replace($regex_html_tags,$rt_arrow,$pagingbar->nextlink);
                if (!stristr($nextlink, '<a')) $nextlink = html_writer::tag('span', $nextlink);
                $output .= html_writer::tag('li', $nextlink, array('class'=>'next'));
            }
            else {
                $output .= html_writer::tag('li', html_writer::tag('span','&raquo;'), array('class'=>'next disabled'));
            }
            
            $output .= html_writer::end_tag('ul');
            $output .= html_writer::tag('div',null, array('class'=>'vc'));
            $output .= html_writer::end_tag('div');
        }

        #return html_writer::tag('ul', $output, array('class' => 'paging'));
        return $output;
    }

}