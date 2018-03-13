<?php

class __Mustache_0c98356402ad3c12775096f5271f9994 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'timelines' section
        $value = $context->find('timelines');
        $buffer .= $this->section8751a564b25bcc65fd0d61c56e2efe8b($context, $indent, $value);

        return $buffer;
    }

    private function section587195a464ea905c8724e9c05928bcbf(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    		<div class="event event-{{category}}" data-toggle="tooltip" title="{{description}}" style="width:{{width}}%; left:{{offset}}%;">
                <span class="sr-only">{{description}}</span>
            </div>
    	';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '    		<div class="event event-';
                $value = $this->resolveValue($context->find('category'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '" data-toggle="tooltip" title="';
                $value = $this->resolveValue($context->find('description'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '" style="width:';
                $value = $this->resolveValue($context->find('width'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%; left:';
                $value = $this->resolveValue($context->find('offset'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%;">
';
                $buffer .= $indent . '                <span class="sr-only">';
                $value = $this->resolveValue($context->find('description'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '</span>
';
                $buffer .= $indent . '            </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8751a564b25bcc65fd0d61c56e2efe8b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="laps-timeline">
    	{{#.}}
    		<div class="event event-{{category}}" data-toggle="tooltip" title="{{description}}" style="width:{{width}}%; left:{{offset}}%;">
                <span class="sr-only">{{description}}</span>
            </div>
    	{{/.}}
    </div>
';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '    <div class="laps-timeline">
';
                // '.' section
                $value = $context->last('.');
                $buffer .= $this->section587195a464ea905c8724e9c05928bcbf($context, $indent, $value);
                $buffer .= $indent . '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
