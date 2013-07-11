<?php

require_once($CFG->dirroot.'/theme/foundation/lib.php');



function ubertheme_process_css($css, $theme) {

		/** Page Settings **/

				// Page Gradients
				$pagegradients = (!empty($theme->settings->pagegradients)) ? $theme->settings->pagegradients : false;
			
				// Font Size
				$fontsizereference = (!empty($theme->settings->fontsizereference)) ? $theme->settings->fontsizereference : null;
				$css = ubertheme_set_fontsizereference($css, $fontsizereference);
					
				// Font Family
				$fontfamilyref = (!empty($theme->settings->fontfamilyref)) ? $theme->settings->fontfamilyref : 1;
				$css = ubertheme_set_fontfamily($css, $fontfamilyref);
					
				// Link Color
				$linkc = (!empty($theme->settings->linkc)) ? $theme->settings->linkc : null;
				$css = ubertheme_set_linkc($css, $linkc);
			
				// Fixed Width Page
				$pagefixedwidth = (!empty($theme->settings->pagefixedwidth)) ? $theme->settings->pagefixedwidth : false;
				$css = ubertheme_set_pagefixedwidth($css, $pagefixedwidth);
					
				// Page Rounded Corners
				$pagerounded = (!empty($theme->settings->pagerounded)) ? $theme->settings->pagerounded : false;
				$css = ubertheme_set_pagerounded($css, $pagerounded);
					
				// Page Shadow Effect
				$pageshadow = (!empty($theme->settings->pageshadow)) ? $theme->settings->pageshadow : false;
				$css = ubertheme_set_pageshadow($css, $pageshadow);
			
				// Page Background Color
				$pagebgc = (!empty($theme->settings->pagebgc)) ? $theme->settings->pagebgc : null;
				$pagebbgc = (!empty($theme->settings->pagebbgc)) ? $theme->settings->pagebbgc : null;
				$css = ubertheme_set_pagebgc($css, $pagebgc, $pagebbgc, $pagegradients);

		/** Main Nav **/

				// Use Gradients
				$menugradient = (!empty($theme->settings->menugradient)) ? $theme->settings->menugradient : false;
			 
				// Colors
				$menubgc = (!empty($theme->settings->menubgc)) ? $theme->settings->menubgc : null;
				$menutc	= (!empty($theme->settings->menutc))	? $theme->settings->menutc	: null;
				$css = ubertheme_set_menucolors($css, $menubgc, $menutc, $menugradient);
			
				// Shadow Effect
				$menushadow = (!empty($theme->settings->menushadow)) ? $theme->settings->menushadow : false;
				$css = ubertheme_set_menushadow($css, $menushadow);
				
		/** Side Blocks **/

				// Use Gradients
				$blockgradient = (!empty($theme->settings->blockgradient)) ? $theme->settings->blockgradient : false;
					
				// Rounded Corners
				$blockrounded = (!empty($theme->settings->blockrounded)) ? $theme->settings->blockrounded : false;
				$css = ubertheme_set_blockrounded($css, $blockrounded);
					
				// Shadow Effect
				$blockshadow = (!empty($theme->settings->blockshadow)) ? $theme->settings->blockshadow : false;
				$css = ubertheme_set_blockshadow($css, $blockshadow);
					 
				// Side Block Color
				$blockstyle = (!empty($theme->settings->blockstyle)) ? $theme->settings->blockstyle : null;
				$sbbgc = (!empty($theme->settings->sbbgc)) ? $theme->settings->sbbgc : null;
				$sbtc	= (!empty($theme->settings->sbtc))	? $theme->settings->sbtc	: null;
				$css = ubertheme_set_blockcolors($css, $sbbgc, $sbtc, $blockstyle, $blockgradient);
					 
				// Block Width
				$blockwidth = (!empty($theme->settings->blockwidth)) ? $theme->settings->blockwidth : null;
				$css = ubertheme_set_blockwidth($css, $blockwidth);
				
		/** Course Progress **/
					 
				// Colors
				$mcpcompletecolor = (!empty($theme->settings->mcpcompletecolor)) ? $theme->settings->mcpcompletecolor : null;
				$mcpincompletecolor	= (!empty($theme->settings->mcpincompletecolor))	? $theme->settings->mcpincompletecolor	: null;
				$mcpnotattemptedcolor	= (!empty($theme->settings->mcpnotattemptedcolor))	? $theme->settings->mcpnotattemptedcolor	: null;
				$css = ubertheme_set_mcpcolors($css, $mcpcompletecolor, $mcpincompletecolor, $mcpnotattemptedcolor);
				
		/** Accordion Course Format **/

				$accordioncolor = (!empty($theme->settings->accordioncolor)) ? $theme->settings->accordioncolor : null;
				$accordiongradient = (!empty($theme->settings->accordiongradient)) ? $theme->settings->accordiongradient : null;
				$css = ubertheme_set_accordion_format_colors($css, $accordioncolor, $accordiongradient);
				
		/** Advanced **/

				// set the customcss
				$customcss = (!empty($theme->settings->customcss)) ? $theme->settings->customcss : null;
				$css = ubertheme_set_customcss($css, $customcss);
		
		///////////////////
		
		return $css;
		
}


///////////////


/** Page Settings **/

function ubertheme_set_fontsizereference($css, $fontsizereference) {
		$tag = '[[setting:fontsizereference]]';
		$css = str_replace($tag, $fontsizereference.'px', $css);
		return $css;
} // ubertheme_set_fontsizereference()

function ubertheme_set_fontfamily($css, $fontfamilyref) {
		$tag = '[[setting:fontfamilyref]]';
	$fontstack = array(
		1=>'Arial, sans-serif',
		2=>'Verdana, sans-serif',
		3=>'"Trebuchet MS", sans-serif',
		4=>'"Times New Roman", serif',
		5=>'Georgia, serif'
		);
	$replace = $fontstack[$fontfamilyref];
		$css = str_replace($tag, $replace, $css);
		return $css;
} // end ubertheme_set_fontfamily()

function ubertheme_set_pagerounded($css, $pagerounded) {
		$tag = '[[setting:pagerounded]]';
	$replace = ($pagerounded) ? '' : 'x';
		$css = str_replace($tag, $replace, $css);
		return $css;
} // end ubertheme_set_roundedcorners()

function ubertheme_set_pageshadow($css, $pageshadow) {
		$tag = '[[setting:pageshadow]]';
	$replace = ($pageshadow) ? '' : 'x';
		$css = str_replace($tag, $replace, $css);
		return $css;
} // end ubertheme_set_shadoweffect()

function ubertheme_set_pagefixedwidth($css, $pagefixedwidth) {
		$tag = '[[setting:pagefixedwidth]]';
	$replace = ($pagefixedwidth) ? '' : 'x';
		$css = str_replace($tag, $replace, $css);
		return $css;
} // end ubertheme_set_pagefixedwidth()

function ubertheme_set_linkc($css, $linkc) {
		$tag[0] = '[[setting:linkc]]';
		#$tag = '[[setting:linkc]]';
		$replacement[0] = $linkc;
		if (is_null($replacement[0])) { $replacement[0] = '#000'; }
		$css = str_replace($tag, $replacement, $css);
		return $css;
} // ubertheme_set_linkc()

function ubertheme_set_pagebgc($css, $pagebgc, $pagebbgc, $pagegradients) {
		$tag = '[[setting:pagebgc]]';
		$replacement = $pagebgc;
		if (is_null($replacement)) { $replacement = '#FFFFFF'; }
		$css = str_replace($tag, $replacement, $css);
		
		if ($pagegradients) {
				$pagebgc_hsl = ubertheme_rgb2hsl(ubertheme_hex2rgb($replacement));
				$pagebgc_hsl->l = ($pagebgc_hsl->l <= 0.15) ? 0 : $pagebgc_hsl->l - 0.15;
				$darker = ubertheme_hsl2rgb($pagebgc_hsl);
				$tag = '[[setting:pagebbgc]]';
				$replacement = $pagebbgc;
				if (is_null($replacement)) { $replacement = '#FFFFFF'; }
				$css = str_replace($tag, $replacement, $css);
		}
		else {
				$tag = '[[setting:pagebbgc]]';
				$replacement = $pagebgc;
				if (is_null($replacement)) { $replacement = '#FFFFFF'; }
				$css = str_replace($tag, $replacement, $css);
		}
		
		$tag = '[[setting:pagebggrad]]';
	$replace = ($pagegradients) ? '' : 'x';
		$css = str_replace($tag, $replace, $css);
		
		return $css;
} // end ubertheme_set_pagebgc()


/** Banners **/

function ubertheme_customBanner(){

		global $PAGE;
		global $OUTPUT;
		$html = '';
		$logo1 = false;
		$logo2 = false;
		$height = 120;
		$showbanners = true;
		
		$homeonly = (!empty($PAGE->theme->settings->custombannerhomeonly)) ? $PAGE->theme->settings->custombannerhomeonly : false;
		if ($homeonly && $PAGE->pagetype != 'my-index' && $PAGE->pagetype != 'site-index') { $showbanners = false; }

		// Toggle secure request to themes.learningpool.com images only
		$pattern = 'http://';
		$replacement = ($_SERVER['SERVER_PORT'] == 443) ? 'https://' : false;
		
		if (!empty($PAGE->theme->settings->logo1)) {
				$logo1 = ($replacement) ? str_ireplace($pattern, $replacement, $PAGE->theme->settings->logo1) : $PAGE->theme->settings->logo1;
		}

		if (!empty($PAGE->theme->settings->logo2)) {
				$logo2 = ($replacement) ? str_ireplace($pattern, $replacement, $PAGE->theme->settings->logo2) : $PAGE->theme->settings->logo2;
		}
		
		$banner_num = 1;
		
		$banner_setting = (!empty($PAGE->theme->settings->custombanner)) ? $PAGE->theme->settings->custombanner : false;
		$banner_slideshow = (!empty($PAGE->theme->settings->custombannerslideshow)) ? $PAGE->theme->settings->custombannerslideshow : false;
		
		// if (!$banner_setting) return '<img class="bg" src="'.$OUTPUT->pix_url('banner_default', 'theme').'" alt="banner"/>';
		if (!$banner_setting) return '<img class="bg" src="" alt="banner"/>';
		
		$ary_banner = explode("\n", str_replace("\n\r", "\n", $banner_setting));
		shuffle($ary_banner);
		$ary_banner_size = count($ary_banner);
		
		if (!empty($PAGE->theme->settings->custombannerheight)) {
				$height = intval($PAGE->theme->settings->custombannerheight);
		}
		
		if (!$showbanners && !$logo1 && !$logo2) return;
		
		// Construct HTML
		
		$html.= '<div class="banner" style="height:'.$height.'px;">';
		
		if ($logo1) {
				$html.= '<div class="logo1"><a href="/"><img src="'.$logo1.'" alt="Logo"/></a></div>';
		}
		
		if ($logo2) {
				$html.= '<div class="logo2"><img src="'.$logo2.'" alt="Logo"/></div>';
		}
		
		if ($showbanners) {
				if ($banner_slideshow) {
						$html.= '<ul class="bg">';
						foreach ($ary_banner as $b) {
								if ($replacement) $b = str_ireplace($pattern, $replacement, $b);
								$html.= "<li data-id=\"".$banner_num++."\"";
								if ($banner_num <= count($ary_banner)) { $html .= ' style="opacity:0;"'; }
								$html.= "><div style=\"background-image:url($b);\"><img src=\"$b\" alt=\"banner\"/></div></li>";
						}
						$html.= '</ul>';
				}
				else {
						$banner_img = array_shift($ary_banner);
						if ($replacement) $banner_img = str_ireplace($pattern, $replacement, $banner_img);
						$html .= '<img class="bg" src="'.$banner_img.'" alt="banner"/>';
				}
		}
		
		$html.= '</div>';

		return $html;
		
} // end customBanner()


/** Main Nav **/

function ubertheme_set_menucolors($css, $menubgc, $menutc, $menugradient) {
		
		$tag[0] = '[[setting:menubgc]]';
		$tag[1] = '[[setting:menubgc_darker]]';
		$tag[2] = '[[setting:menu_blc]]';
		$tag[3] = '[[setting:menu_brc]]';
		$tag[4] = '[[setting:menutc]]';
		$tag[5] = '[[setting:menu_hover]]';
		$tag[6] = '[[setting:menubggrad]]';
		
		$replacement[0] = $menubgc;
		if (is_null($replacement[0])) { $replacement[0] = '#CCC'; }
		
		$menubgc_rgb = ubertheme_hex2rgb($replacement[0]);
		$menubgc_hsl = ubertheme_rgb2hsl($menubgc_rgb);
		$menubgc_hsl->l = ($menubgc_hsl->l <= 0.15) ? 0 : $menubgc_hsl->l - 0.15;
		$darker_rgb = ubertheme_hsl2rgb($menubgc_hsl);
		$darker_hex = ubertheme_rgb2hex($darker_rgb);
		
		$replacement[1] = ($menugradient) ? $darker_hex : $replacement[0];
		$replacement[2] = $replacement[0];
		$replacement[3] = $darker_hex;
		// $replacement[4] = ($menubgc_hsl->l < 0.5) ? '#FFFFFF' : '#000000';
		$replacement[4] = $menutc;
		$replacement[5] = $darker_hex;
		$replacement[6] = ($menugradient) ? '' : 'x';
		
		$css = str_replace($tag, $replacement, $css);
		return $css;
		
} // end ubertheme_set_menucolors()

function ubertheme_set_menushadow($css, $menushadow) {
		$tag = '[[setting:menushadow]]';
	$replace = ($menushadow) ? '' : 'x';
		$css = str_replace($tag, $replace, $css);
		return $css;
} // ubertheme_set_shadoweffect()


/** Side Blocks **/

function ubertheme_set_blockrounded($css, $blockrounded) {
		$tag[0] = '[[setting:blockrounded]]';
		$tag[1] = '[[setting:blocksharp]]';
		$replace[0] = ($blockrounded) ? '' : 'x';
		$replace[1] = (!$blockrounded) ? '' : 'x';
		$css = str_replace($tag, $replace, $css);
		return $css;
} // ubertheme_set_blockrounded()

function ubertheme_set_blockshadow($css, $blockshadow) {
		#echo '<h1>Shadow Effect</h1>';
		$tag = '[[setting:blockshadow]]';
	$replace = ($blockshadow) ? '' : 'x';
		$css = str_replace($tag, $replace, $css);
		return $css;
} // ubertheme_set_blockshadow()

function ubertheme_set_blockcolors($css, $sbbgc, $sbtc, $blockstyle, $blockgradient) {

		$tag[0] = '[[setting:sbbgc]]';
		$tag[1] = '[[setting:sbtc]]';
		$tag[2] = '[[setting:sbtbgc]]';
		$tag[3] = '[[setting:blockgrad]]';

		$replacement[0] = $sbbgc;
		$replacement[1] = $sbtc;
		$replacement[2] = $sbbgc;
		$replacement[3] = ($blockgradient) ? '' : 'x';
		
		if (is_null($replacement[0])) $replacement[0] = '#CCCCCC';
		if (is_null($replacement[1])) $replacement[1] = '#000000';
		if (is_null($replacement[2])) $replacement[2] = '#EEEEEE';
		
		$sbbgc_hsl = ubertheme_rgb2hsl(ubertheme_hex2rgb($replacement[0]));
		$sbbgc_hsl->l = ($sbbgc_hsl->l >= 0.9) ? 1 : $sbbgc_hsl->l + 0.1;
		$lighter_color = ubertheme_rgb2hex(ubertheme_hsl2rgb($sbbgc_hsl));
		$replacement[2] = $lighter_color;
		
		$css = str_replace($tag, $replacement, $css);
		
		return $css;

} // end ubertheme_set_blockcolors()

function ubertheme_set_blockwidth($css, $blockwidth) {
	
		$tag = '[[setting:blockcolumnwidth]]';
		$css = str_replace($tag, ($blockwidth+20).'px', $css);
	
		$tag = '[[setting:minusblockcolumnwidth]]';
		$css = str_replace($tag, '-'.($blockwidth+20).'px', $css);

		$tag = '[[setting:minusdoubleblockcolumnwidth]]';
		$css = str_replace($tag, '-'.(2*$blockwidth+40).'px', $css);

		$tag = '[[setting:doubleblockcolumnwidth]]';
		$css = str_replace($tag, (2*$blockwidth+40).'px', $css);

		return $css;
} // end ubertheme_set_blockwidth()


/** My Course Progress **/

function ubertheme_set_mcpcolors($css, $com, $inc, $not) {

		$tag[0] = '[[setting:mcpcom]]';
		$tag[1] = '[[setting:mcpcomb]]';
		$tag[2] = '[[setting:mcpinc]]';
		$tag[3] = '[[setting:mcpincb]]';
		$tag[4] = '[[setting:mcpnot]]';
		$tag[5] = '[[setting:mcpnotb]]';
		
		$com_hsl = ubertheme_rgb2hsl(ubertheme_hex2rgb($com));
		$com_hsl->l = ($com_hsl->l <= 0.1) ? 0 : $com_hsl->l - 0.1;
		$comb = ubertheme_rgb2hex(ubertheme_hsl2rgb($com_hsl));
		
		$inc_hsl = ubertheme_rgb2hsl(ubertheme_hex2rgb($inc));
		$inc_hsl->l = ($inc_hsl->l <= 0.1) ? 0 : $inc_hsl->l - 0.1;
		$incb = ubertheme_rgb2hex(ubertheme_hsl2rgb($inc_hsl));
		
		$not_hsl = ubertheme_rgb2hsl(ubertheme_hex2rgb($not));
		$not_hsl->l = ($not_hsl->l <= 0.1) ? 0 : $not_hsl->l - 0.1;
		$notb = ubertheme_rgb2hex(ubertheme_hsl2rgb($not_hsl));

		$replacement[0] = $com;
		$replacement[1] = $comb;
		$replacement[2] = $inc;
		$replacement[3] = $incb;
		$replacement[4] = $not;
		$replacement[5] = $notb;
		
		if (is_null($replacement[0])) $replacement[0] = '#55B295';
		if (is_null($replacement[0])) $replacement[1] = '#55B295';
		if (is_null($replacement[1])) $replacement[2] = '#5875B5';
		if (is_null($replacement[1])) $replacement[3] = '#5875B5';
		if (is_null($replacement[2])) $replacement[4] = '#FFFFFF';
		if (is_null($replacement[2])) $replacement[5] = '#FFFFFF';
		
		$css = str_replace($tag, $replacement, $css);
		
		return $css;

} // end ubertheme_set_blockcolors()


/** Advanced **/

function ubertheme_set_accordion_format_colors($css, $c0, $grad) {

		$colors = new theme_foundation_colors;
		$colors->basecolor = $c0;

		$tag[0] = '[[setting:accordion-c0]]'; // Base Color
		$tag[1] = '[[setting:accordion-c1]]'; // Base + 20% Lighter
		$tag[2] = '[[setting:accordion-c2]]'; // Base + 50% Lighter
		$tag[3] = '[[setting:accordion-c3]]'; // Base + 60% Lighter
		$tag[4] = '[[setting:accordion-c4]]'; // Base + 75% Lighter
		$tag[5] = '[[setting:accordion-c5]]'; // Base + 90% Lighter
		$tag[6] = '[[setting:accordion-text]]'; // Black or White depending on it's contrast with Base
		$tag[7] = '[[setting:accordion-grad]]'; // Whether to use gradients or not

		$replacement[0] = $c0;
		$replacement[1] = $colors->lighter(0.20);
		$replacement[2] = $colors->lighter(0.50);
		$replacement[3] = $colors->lighter(0.60);
		$replacement[4] = $colors->lighter(0.75);
		$replacement[5] = $colors->lighter(0.80);
		$replacement[6] = $colors->text(0.90);
		$replacement[7] = ($grad) ? '' : 'x';

		$css = str_replace($tag, $replacement, $css);

		return $css;

} // end ubertheme_set_accordion_format_colors();


/** Advanced **/

function ubertheme_set_customcss($css, $customcss) {
		$pattern = 'http://';
		$replacement = ($_SERVER['SERVER_PORT'] == 443) ? 'https://' : false;
		if ($replacement) $customcss = str_ireplace($pattern, $replacement, $customcss);
		$tag = '[[setting:customcss]]';
		$css = str_replace($tag, $customcss, $css);
		return $css;
} // end ubertheme_set_customcss()



///////////////////

/*

function ubertheme_set_primebgc($css, $primebgc) {
		$tag = '[[setting:primebgc]]';
		$replacement = $primebgc;
		if (is_null($replacement)) { $replacement = '#CCC'; }
		$css = str_replace($tag, $replacement, $css);
		return $css;
} // end ubertheme_set_primebgc()

function ubertheme_set_primetc($css, $primetc) {
		$tag = '[[setting:primetc]]';
		$replacement = $primetc;
		if (is_null($replacement)) { $replacement = '#000'; }
		$css = str_replace($tag, $replacement, $css);
		return $css;
} // end ubertheme_set_primetc()
*/


/////////////////////////////////////////////////////



/////////////////////////////////////////////////////


function ubertheme_featureSlider(){

		global $PAGE;
		global $OUTPUT;
		
		$features_raw = (!empty($PAGE->theme->settings->featureslider)) ? $PAGE->theme->settings->featureslider : false;
		
		if ($features_raw) {
				$ary_features = explode("\n", str_replace("\n\r", "\n", $features_raw));
		}
		else { return false; }
		
		$html	= '';
		$lis	 = '';
		$max_h = 0;
		$cur_h = 0;
		
		foreach ($ary_features as $f) {
				if (!empty($f)) {
						$parts = explode('|', $f);
						$cur_h = getimagesize(trim($parts[0]));
						$max_h = ($cur_h[1] > $max_h) ? $cur_h[1] : $max_h; 
						// $lis	.= "<li style=\"background-image:url({$parts[0]});\">";
						$lis	.= "<li>";
						$lis	.= "<img src=\"{$parts[0]}\" alt=\"\">";
						$title = (!empty($parts[1])) ? "<b>{$parts[1]}</b>" : '';
						$lis	.= (!empty($parts[2])) ? "<a href=\"{$parts[2]}\">$title</a>" : $title;
						$lis	.= "</li>";
				}
		}
		
		$html.= '<div id="feature-slider" class="hide" style="height:'.$max_h.'px;">';
		$html.= '<ul>';
		$html.= $lis;
		$html.= '</ul>';
		$html.= '</div>';
		
		return $html;
		
		// print_r($PAGE->cm);
		// echo $PAGE->cm;
		
} // end featureSlider()


/////////////////////////////////////////////////////


function ubertheme_hex2rgb($hex=false) {

		if (!$hex) return "error";

		$error = false;
		$rgb = new Object();

		// Assure correct consistant value of XXXXXX
		switch (strlen($hex)) {
				case 7: $mhex = substr($hex,1); // remove assumed leading #
				case 6: break;
				case 4: $hex = substr($hex,1); // remove assumed leading #
				case 3: $mhex=''; for($i=0;$i<3;$i++) { $mhex .= str_repeat(substr($hex,$i,1),2); } break;
				default: $error = true;
		}
		
		if ($error) return "error";
		
		$rgb->r = hexdec(substr($mhex,0,2));
		$rgb->g = hexdec(substr($mhex,2,2));
		$rgb->b = hexdec(substr($mhex,4,2));
		
		return $rgb;

} // end ubertheme_hex2rgb()



function ubertheme_rgb2hex($rgb=false) {

		if (!$rgb) return "error";
		
		$temp->r = dechex($rgb->r);
		$temp->g = dechex($rgb->g);
		$temp->b = dechex($rgb->b);
		
		if (strlen($temp->r) == 1) $temp->r = '0'.$temp->r;
		if (strlen($temp->g) == 1) $temp->g = '0'.$temp->g;
		if (strlen($temp->b) == 1) $temp->b = '0'.$temp->b;

		$hex = '#' . $temp->r . $temp->g . $temp->b;
		return $hex;

} // end ubertheme_hex2rgb()



function ubertheme_rgb2hsl($rgb) {

		if (!$rgb) return "error";
		if (gettype($rgb) != 'object') {
				$hsl = new Object();
				$hsl->l = 0;
				$hsl->s = 0;
				$hsl->h = 0;
				return $hsl;
		}

		$r = $rgb->r / 255;
		$g = $rgb->g / 255;
		$b = $rgb->b / 255;

		$hsl = new Object();

		$var_min = min($r,$g,$b);
		$var_max = max($r,$g,$b);
		$del_max = $var_max - $var_min;

		$hsl->l = ($var_max + $var_min) / 2;

		if ($del_max == 0)
		{
				$hsl->h = 0;
				$hsl->s = 0;
		}
		else
		{
				if ($hsl->l < 0.5)	{ $hsl->s = $del_max / ($var_max + $var_min); }
				else								{ $hsl->s = $del_max / (2 - $var_max - $var_min); }

				$del_r = ((($var_max - $r) / 6) + ($del_max / 2)) / $del_max;
				$del_g = ((($var_max - $g) / 6) + ($del_max / 2)) / $del_max;
				$del_b = ((($var_max - $b) / 6) + ($del_max / 2)) / $del_max;

				if		 ($r == $var_max) { $hsl->h = $del_b - $del_g; }
				elseif ($g == $var_max) { $hsl->h = (1 / 3) + $del_r - $del_b; }
				elseif ($b == $var_max) { $hsl->h = (2 / 3) + $del_g - $del_r; }

				if ($hsl->h < 0) $hsl->h += 1;
				if ($hsl->h > 1) $hsl->h -= 1;
						
		}
		
		return $hsl;

} // end ubertheme_rgb2hsl()




function ubertheme_hsl2rgb($hsl=false) {

		if (!$hsl) return "error";

		$rgb = new Object();

		if ($hsl->s == 0)
		{
				$rgb->r = intval($hsl->l * 255);
				$rgb->g = intval($hsl->l * 255);
				$rgb->b = intval($hsl->l * 255);
		}
		else
		{
				if ($hsl->l < 0.5) { $var_2 = $hsl->l * (1 + $hsl->s); }
				else							 { $var_2 = ($hsl->l + $hsl->s) - ($hsl->s * $hsl->l); }

				$var_1 = 2 * $hsl->l - $var_2;
				$rgb->r = intval(255 * ubertheme_hue_2_rgb($var_1,$var_2,$hsl->h + (1 / 3)));
				$rgb->g = intval(255 * ubertheme_hue_2_rgb($var_1,$var_2,$hsl->h));
				$rgb->b = intval(255 * ubertheme_hue_2_rgb($var_1,$var_2,$hsl->h - (1 / 3)));
		}
		
		return $rgb;

} // end ubertheme_hsl2rgb();



function ubertheme_hue_2_rgb($v1,$v2,$vh) {

		if ($vh < 0) $vh += 1;
		if ($vh > 1) $vh -= 1;
		
		if ((6 * $vh) < 1) return ($v1 + ($v2 - $v1) * 6 * $vh);
		if ((2 * $vh) < 1) return ($v2);
		if ((3 * $vh) < 2) return ($v1 + ($v2 - $v1) * ((2 / 3 - $vh) * 6));
		
		return ($v1);
		
}

function theme_ubertheme_init_yui() {
		global $PAGE, $CFG;
		$ary_replace = array("\n\r","\r");
		$ary_with		= array("\n");

		if (file_exists($CFG->dirroot.'/theme/foundation/javascript/yui-init.js')) {
				require_once($CFG->dirroot.'/theme/foundation/lib.php');
				theme_foundation_init_yui();
		}

		// Ground Floor Scripts
		if (file_exists($CFG->dirroot . '/theme/ubertheme/javascript/yui-init.js')) {
				$customyuimods = (isset($PAGE->theme->settings->customyuimods)) ? str_replace($ary_replace, $ary_with, $PAGE->theme->settings->customyuimods) : '';
				$customyuimods = explode("\n", $customyuimods);
				$yuimods = (isset($PAGE->theme->javascripts['yuimods'])) ? $PAGE->theme->javascripts['yuimods'] : array();
				$jsconfig = array(
						'name' => 'theme_ubertheme_init',
						'fullpath' => '/theme/ubertheme/javascript/yui-init.js',
						'requires' => array_merge($yuimods, $customyuimods)
				);

				$PAGE->requires->js_init_call('M.theme_ubertheme.init', null, false, $jsconfig);
		}
}

function theme_ubertheme_custom_yui() {
		global $CFG, $PAGE;

		$collapseable = 'false';
		$courselist = 'false';

		$customyuicode = (isset($PAGE->theme->settings->customyuicode)) ? $PAGE->theme->settings->customyuicode : '';
		if ($PAGE->pagetype == 'course-index') {
				$courselist = json_encode(theme_foundation_get_course_list());
				$collapseable = (isset($PAGE->theme->settings->collapsecourselist)) ? $PAGE->theme->settings->collapsecourselist : 0;
		}

		echo '<script type="text/javascript">';
		echo 'var LP = {};';
		echo 'LP.collapsecourselist = '.$collapseable.';';
		echo 'LP.courselist = '.$courselist.';';
		echo 'M.theme_ubertheme.customyui = function(Y) {';
		echo $customyuicode;
		echo '}';
		echo '</script>';
}

function theme_ubertheme_update_settings($formdata) {
		
		global $CFG;
		
		require_once($CFG->libdir . '/sessionlib.php');
		
		$istotara = (isset($CFG->totara_build));
		$reset	= optional_param('reset', 0, PARAM_BOOL);
		
		$success = true;
		
//		>>
//		>> Attempting to upload files directly to the LMS. Couldn't get it to work so commenting out for now.
//		>>
//		$context = get_system_context();
//		$logo_upload_opts = array('accepted_types'=>array('web_image','archive'));
//		$logo1 = file_save_draft_area_files($formdata->logo1, $context->id, 'theme', 'theme_ubertheme', 0, $logo_upload_opts);
//		$logo2 = file_save_draft_area_files($formdata->logo2, $context->id, 'theme', 'theme_ubertheme', 0, $logo_upload_opts);
//		echo "<div class=\"debugging\">Result of saved file: Logo 1 > $logo1, Logo 2 > $logo2</div>";
		
		if (!set_config('fontsizereference', $formdata->fontsizereference, 'theme_ubertheme')) $success = false;
		if (!set_config('fontfamilyref', $formdata->fontfamilyref, 'theme_ubertheme')) $success = false;
		if (!set_config('pagefixedwidth', $formdata->pagefixedwidth, 'theme_ubertheme')) $success = false;
		if (!set_config('pagerounded', $formdata->pagerounded, 'theme_ubertheme')) $success = false;
		if (!set_config('pageshadow', $formdata->pageshadow, 'theme_ubertheme')) $success = false;
		if (!set_config('collapsecourselist', $formdata->collapsecourselist, 'theme_ubertheme')) $success = false;
		if (!set_config('searchwidget', $formdata->searchwidget, 'theme_ubertheme')) $success = false;
		if (!set_config('autorunsupportwidget', $formdata->autorunsupportwidget, 'theme_ubertheme')) $success = false;
		if (!set_config('linkc', $formdata->linkc, 'theme_ubertheme')) $success = false;
		if (!set_config('pagebgc', $formdata->pagebgc, 'theme_ubertheme')) $success = false;
		if (!set_config('pagegradients', $formdata->pagegradients, 'theme_ubertheme')) $success = false;
		if (!set_config('pagebbgc', $formdata->pagebbgc, 'theme_ubertheme')) $success = false;
		
		if (!set_config('custombanner', $formdata->custombanner, 'theme_ubertheme')) $success = false;
		if (!set_config('custombannerheight', $formdata->custombannerheight, 'theme_ubertheme')) $success = false;
		if (!set_config('custombannerslideshow', $formdata->custombannerslideshow, 'theme_ubertheme')) $success = false;
		if (!set_config('custombannerhomeonly', $formdata->custombannerhomeonly, 'theme_ubertheme')) $success = false;

		if (!set_config('favicon', $formdata->favicon, 'theme_ubertheme')) $success = false;
		
		if (!set_config('logo1', $formdata->logo1, 'theme_ubertheme')) $success = false;
		if (!set_config('logo2', $formdata->logo2, 'theme_ubertheme')) $success = false;
		
		if (!set_config('ticker', $formdata->ticker['text'], 'theme_ubertheme')) $success = false;
		if (!set_config('tickerlocation', $formdata->tickerlocation, 'theme_ubertheme')) $success = false;

		if (!set_config('custommenuitems', $formdata->custommenuitems)) $success = false;
		if (!set_config('menutc', $formdata->menutc, 'theme_ubertheme')) $success = false;
		if (!set_config('menubgc', $formdata->menubgc, 'theme_ubertheme')) $success = false;
		if (!set_config('menugradient', $formdata->menugradient, 'theme_ubertheme')) $success = false;
		if (!set_config('menushadow', $formdata->menushadow, 'theme_ubertheme')) $success = false;
		if (!set_config('showbreadcrumb', $formdata->showbreadcrumb, 'theme_ubertheme')) $success = false;
		
		if (!set_config('blockstyle', $formdata->blockstyle, 'theme_ubertheme')) $success = false;
		if (!set_config('blockwidth', $formdata->blockwidth, 'theme_ubertheme')) $success = false;
		if (!set_config('blockrounded', $formdata->blockrounded, 'theme_ubertheme')) $success = false;
		if (!set_config('blockshadow', $formdata->blockshadow, 'theme_ubertheme')) $success = false;
		if (!set_config('blockgradient', $formdata->blockgradient, 'theme_ubertheme')) $success = false;
		if (!set_config('sbbgc', $formdata->sbbgc, 'theme_ubertheme')) $success = false;
		if (!set_config('sbtc', $formdata->sbtc, 'theme_ubertheme')) $success = false;

		if (!set_config('accordioncolor', $formdata->accordioncolor, 'theme_ubertheme')) $success = false;
		if (!set_config('accordiongradient', $formdata->accordiongradient, 'theme_ubertheme')) $success = false;
		
		if (!set_config('customanonhomepage', $formdata->customanonhomepage, 'theme_ubertheme')) $success = false;
		if (!set_config('customanonhomepagecontent', $formdata->customanonhomepagecontent['text'], 'theme_ubertheme')) $success = false;
		
		if ($istotara) {
				if (!set_config('totaramenu', $formdata->totaramenu, 'theme_ubertheme')) $success = false;
				if (!set_config('appendcustommenuitems', $formdata->appendcustommenuitems, 'theme_ubertheme')) $success = false;
		}
		else {
				if (!set_config('mcpcompletecolor', $formdata->mcpcompletecolor, 'theme_ubertheme')) $success = false;
				if (!set_config('mcpincompletecolor', $formdata->mcpincompletecolor, 'theme_ubertheme')) $success = false;
				if (!set_config('mcpnotattemptedcolor', $formdata->mcpnotattemptedcolor, 'theme_ubertheme')) $success = false;
		}
		
		if ($success) {
				theme_reset_all_caches();
		}
		
		return $success;
}

