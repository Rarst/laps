<?php

class __Mustache_a04731de0bb9e043ecde3814b3a901bb extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'timelines' section
        $value = $context->find('timelines');
        $buffer .= $this->sectionAdac57c50f147efcda3ea1c17e7a6c61($context, $indent, $value);

        return $buffer;
    }

    private function section9f1840fed1e29fb14453f0ea2bfe30ad(Mustache_Context $context, $indent, $value)
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

    private function sectionAdac57c50f147efcda3ea1c17e7a6c61(Mustache_Context $context, $indent, $value)
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
                $buffer .= $this->section9f1840fed1e29fb14453f0ea2bfe30ad($context, $indent, $value);
                $buffer .= $indent . '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
