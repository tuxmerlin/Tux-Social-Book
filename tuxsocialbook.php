<?php
/**
 * Tux Mini Social Bookmarking Plugin
 * @version $Id: tuxsocialbook.php 2011-10-23  $ 
 * @copyright (C) 2011 by Miguel O. A. Tuyaré. All rights reserved.
 * @Author link: http://www.joomla-gnu.com / http://www.tuxmerlin.com.ar
 * @For Joomla 1.7.x
 * @license     GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 * Consider make a donation at www.tuxmerlin.com.ar
 *
 */ 
defined('_JEXEC') or die('Restricted access');


//Load Joomla library
jimport('joomla.plugin.plugin');
jimport('joomla.environment.uri');


/**
 * Plugin Tux Social Bookmarks
 * @procedure	Main Class Helper
 * @package		Joomla.Plugin
 * @subpackage	Content.tuxsocialbook
*/
class plgContentTuxsocialbook extends JPlugin 
{

	/**
	* Plugin Tux Social Bookmarks
	* @Procedure	Constructor - Public!
	* @package		Joomla.Plugin
	* @subpackage	Content.tuxsocialbook
	*/
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();		
		
		/* For Google +1 */
		if ($this->params->def('one')){	
			$document = JFactory::getDocument();
			$gone = "<script type=\"text/javascript\" src=\"http://apis.google.com/js/plusone.js\">{lang: '".$this->params->def('onelang','en')."'}</script>";
			$document->addCustomTag($gone);
		}
	}
    
	
	/**
	* Plugin Tux Social Bookmarks
	* @Procedure	Content After Display
	* @package		Joomla.Plugin
	* @subpackage	Content.tuxsocialbook
	*/
	public function onContentAfterDisplay($context, &$row, &$params, $page=0) 
	{   
		if ($this->params->def('position') == 'dc'){
			return self::_generateHTML($row, $this->params);
		} else {
			return '';
		}
    }
	
	
	/**
	* Plugin Tux Social Bookmarks
	* @Procedure	Content Before Display
	* @package		Joomla.Plugin
	* @subpackage	Content.tuxsocialbook
	*/
	public function onContentBeforeDisplay($context, &$row, &$params, $page=0) 
	{           
        if ($this->params->def('position') == 'ac'){
			return self::_generateHTML($row, $this->params);			
		} else {
			return '';
		}
    }
	
	
	/**
	* Plugin Tux Social Bookmarks
	* @Procedure	render buttons
	* @package		Joomla.Plugin
	* @subpackage	Content.tuxsocialbook
	*/
	public static function _generateHtml(&$article, $myParams) {
        $html = '';		
        if (JFactory::getApplication() -> scope != 'com_content') {
            return $html;
        }				
    
		$document = JFactory::getDocument();
	    $view = JRequest::getCmd('view');
        if (
            !(
                $view == 'article'
                || ($myParams -> def('frontpage', true) && $view == 'featured')                
                || ($myParams -> def('category', true) && $view == 'category')
            )
        ) {
            return $html;
        }
        /*
         * Apply filters for category, article.
         */       
        $categoryIDList = array();
        $categoryMode = '';
        foreach (explode(',', $myParams -> get('categoryIDList', '')) as $num) {
            if (is_numeric($num)) {
                if ($categoryMode == '') {
                    if ($num[0] == '-') {
                        $categoryMode = '-';
                    } else {
                        $categoryMode = '+';
                    }
                }
                if ($num[0] == '-') {
                    $categoryIDList[] = -1 * (int) $num;
                } else {
                    $categoryIDList[] = (int) $num;
                }
            }
        }
        if (
            $categoryMode
            && (in_array($article -> catid, $categoryIDList) != ($categoryMode == '+'))
        ) {
            return $html;
        }
        $articleIDList = array();
        $articleMode = '';
        foreach (explode(',', $myParams -> get('articleIDList', '')) as $num) {
            if (is_numeric($num)) {
                if ($articleMode == '') {
                    if ($num[0] == '-') {
                        $articleMode = '-';
                    } else {
                        $articleMode = '+';
                    }
                }
                if ($num[0] == '-') {
                    $articleIDList[] = -1 * (int) $num;
                } else {
                    $articleIDList[] = (int) $num;
                }
            }
        }
        if (
            $articleMode
            && (in_array($article -> id, $articleIDList) != ($articleMode == '+'))
        ) {            
			return $html;
        }		
		
		/* Style params */
		$bg = $myParams -> get('boxback');
		$bz = $myParams -> get('bordersize');
		$bs = $myParams -> get('bordersep');
		$bc = $myParams -> get('bordercolor');
		$bt = $myParams -> get('bordertype');
		$boxstyle = $bz.' '.$bt.' '.$bc;		
				
		$wbutton = (int) $myParams->get('like_width') + (int) $myParams->get('count-width') + 110 + 90; 
		
		$url2 = "http://".$_SERVER['HTTP_HOST'].JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));		
		$url2 = urlencode($url2);	
		
		$html ='<!--- Tux Social Bookmarks Begin -->';		
		
		if($myParams->get('like')) $like = '<div class="faceandtweet_like" style="float:'.$myParams->get('float').'; width:'.$myParams->get('like_width').'px; height:'.$myParams ->get('like_height').'px;"><iframe src="http://www.facebook.com/plugins/like.php?href='.$url2 .'&amp;layout='.$myParams->get('like_style').'&amp;width='.$myParams->get('like_width').'&amp;show_faces=false&amp;action='.$myParams->get('like_verb').'&amp;colorscheme='.$myParams->get('like_color_scheme').'&amp;height='.$myParams->get('like_height').'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$myParams->get('like_width').'px; height:'.$myParams->get('like_height').'px;"></iframe></div>';
	
		
		if($myParams->get('retweet')) $retweet = '<div class="faceandtweet_retweet" style="float:'.$myParams->get('float').'; width:'.$myParams->get('count-width').'px;"><a href="http://twitter.com/share?url='.$url2.'&amp;text='.$article->title.'&amp;count='.$myParams->get('count').'&amp;via='.$myParams->get('twitter_account').'&amp;related='.$myParams->get('twitter_account2').'" class="twitter-share-button" >Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>';	
		
		if($myParams->get('buzz')) $buzz = '<div class="faceandtweet_retweet" style="float:'.$myParams->get('float').'; width:110px;"><a title="'.$buzzTitle.'" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="small-count" data-url="'.urldecode($url2).'"></a><script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script></div>';		
		
		if($myParams->get('digg')) $digg = '<div class="faceandtweet_retweet" style="float:'.$myParams->get('float').'; width:90px;"><a class="DiggThisButton DiggCompact" href="http://digg.com/submit?url='.$url2.'&amp;title='.$article->title.'"></a><script type="text/javascript" src="http://widgets.digg.com/buttons.js"></script></div>';
		
		if($myParams->get('one')) $one = '<div class="faceandtweet_retweet" style="float:'.$myParams->get('float').';"><g:plusone size="'.$myParams->get('onesize','').'" href="'.$url2.'"></g:plusone></div>';
		
		if ($myParams->get('posbuttons') == 0){
			$html .= '<div class="faceandtweet" style="background:'.$bg.';border: '.$boxstyle.';padding: '.$bs.';float:left;margin:5px;">'.$like.'<br /><br />'.$retweet.'<br /><br />'.$buzz.'<br /><br />'.$digg.'<br /><br />'.$one.'<div style="clear:both;"></div></div>';
		} else {
			$html .= '<div class="faceandtweet" style="background:'.$bg.';with: '.$wbutton.'px; border: '.$boxstyle.';padding: '.$bs.';float:left;margin:5px;">'.$like.$retweet.$buzz.$digg.$one.'</div><div style="clear:both;"></div>';
		}				        
		$html .='<!--- END Tux Social Bookmarks -->';		
        return $html;
	
    }


}