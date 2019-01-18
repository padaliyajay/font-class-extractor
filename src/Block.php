<?php
namespace Padaliyajay\FontClassExtractor;

class Block {
    /**
     * @type \Sabberworm\CSS\RuleSet\DeclarationBlock
     */
    private $declarationBlock;
    
    public function __construct($declarationBlock){
        if($declarationBlock instanceof \Sabberworm\CSS\RuleSet\DeclarationBlock){
            $this->declarationBlock = $declarationBlock;
        } else {
            throw new \Exception('Invalid argument! Require instance of \Sabberworm\CSS\RuleSet\DeclarationBlock');
        }
    }
    
    /**
     * Check whether ruleset has content rule or not
     * @return Boolean
     */
    private function hasContentRule(){
        $is_content_rule = false;
        
        foreach ($this->declarationBlock->getRules() as $rule) {
            if($rule->getRule() === 'content'){
                $is_content_rule = TRUE;
                break;
            }
        }
        
        return $is_content_rule;
    }
    
    /**
     * Extract and get classes of selector
     * @return Array
     */
    public function getClasses(){
        $classes = array();
        
        // Check content rule
        if(!$this->hasContentRule()){
            return array();
        }
        
        // Parser selector
        foreach($this->declarationBlock->getSelectors() as $selector){
            preg_match('/\.([\w\-]+):+(before|after)/', $selector->getSelector(), $class);
            
            if (isset($class[1])) {
                $classes[] = $class[1];
            }
        }
        
        return $classes;
    }
    
    
}

