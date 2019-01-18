<?php
namespace Padaliyajay\FontClassExtractor;

class FontClassExtractor{
    private $css_parser;
    
    public function __construct($font_css) {
        $this->css_parser = new \Sabberworm\CSS\Parser($font_css);
    }
    
    /**
     * Get list of font classes
     * @return Array
     */
    public function getClasses() {
        $classes = array();
        
        foreach ($this->getBlocks($this->css_parser->parse()) as $block) {
            $block_classes = $block->getClasses();
            
            if($block_classes){
                $classes = array_merge($classes, $block_classes);
            }
        }
        
        return $classes;
    }
    
    /**
     * Extract and get list of available css blocks
     * @param \Sabberworm\CSS\CSSList\CSSList $css_list
     * @return Array
     */
    private function getBlocks(\Sabberworm\CSS\CSSList\CSSList $css_list){
        $blocks = array();
        
        foreach($css_list->getContents() as $content){
            // DeclarationBlock
            if($content instanceof \Sabberworm\CSS\RuleSet\DeclarationBlock){
                $blocks[] = new \Padaliyajay\FontClassExtractor\Block($content);
            }
            
            // CSSList
            if($content instanceof \Sabberworm\CSS\CSSList\CSSList) { 
                $blocks = array_merge($blocks, $this->getBlocks($content));
            }
        }
        
        return $blocks;
    }
}